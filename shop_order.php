<?php session_start(); ?>
<?php echo file_get_contents("shop_order_header.php"); ?>

<script type="text/javascript">
function search(){
  <!--document.getElementById("demo").innerHTML = "hi";-->

  var status_input = document.getElementById("status_input");
  var shop_input = document.getElementById("shop_input");
  var wanted_status = status_input.options[status_input.options.selectedIndex].value;
  var wanted_shop = shop_input.options[shop_input.options.selectedIndex].value;

  var table = document.getElementById("OrderList");
  var tr = table.getElementsByTagName("tr");
  var td1, td_status, td2, td_shop;
  for (var i = 0; i < tr.length; i++) {
    td1 = tr[i].getElementsByTagName("td")[2]; // status
    td2 = tr[i].getElementsByTagName("td")[5]; // shop
    if(td1) {
      td_status = td1.textContent || td1.innerText;
      td_shop = td2.textContent || td2.innerText;
      if((wanted_status == "init" && wanted_shop == "init") || (wanted_status == "init" && td_shop == wanted_shop) || (wanted_shop == "init" && td_status == wanted_status) || (td_status == wanted_status && td_shop == wanted_shop)){
        tr[i].style.display = "";
      }
      else{
        tr[i].style.display = "none";
      }
    }
  }
}

function check_checkbox(){
  var table = document.getElementById("OrderList");
  var tr = table.getElementsByTagName("tr");
  var td1, td_status, td2;
  for(var i = 0; i < tr.length; i++){
    td1 = tr[i].getElementsByTagName("td")[2];
    td2 = tr[i].getElementsByTagName("td")[0];
    if(td1){
      td_status = td1.textContent || td1.innerText;
      if(td_status != "incomplete"){
        td2.style.visibility = "hidden";
      }
    }
  }
}

function finished_selected_orders(){

  <!--location.href='done_order.php?O_id=' + 13;-->
  <!--document.getElementById("demo").innerHTML = "hi";-->

  var table = document.getElementById("OrderList");
  var tr = table.getElementsByTagName("tr");
  var td1, td_checked, td2, td_O_id;
  var O_id = [];
  for(var i = 0; i < tr.length; i++){
    td1 = tr[i].getElementsByTagName("td")[0];
    td2 = tr[i].getElementsByTagName("td")[1];
    if(td1){
      td_O_id = td2.textContent || td2.innerText;
      if(document.getElementById(td_O_id).checked){
        O_id.push(td_O_id);
      }
    }
  }

  $.ajax({
       url: 'done_all_order.php',
       cache: false,
       dataType: 'json',
       type:'POST',
       data: {O_id : JSON.stringify(O_id)},
       error:function(){
         alert('error');
         location.replace("shop_order.php");
       },
       success: function(res){
         alert('Done all orders!');
         location.replace("shop_order.php");
       }
  });


}

function cancel_selected_orders(){
  var table = document.getElementById("OrderList");
  var tr = table.getElementsByTagName("tr");
  var td1, td_checked, td2, td_O_id;
  var O_id = [];
  for(var i = 0; i < tr.length; i++){
    td1 = tr[i].getElementsByTagName("td")[0];
    td2 = tr[i].getElementsByTagName("td")[1];
    if(td1){
      td_O_id = td2.textContent || td2.innerText;
      if(document.getElementById(td_O_id).checked){
        O_id.push(td_O_id);
      }
    }
  }

  $.ajax({
       url: 'cancel_all_order.php',
       cache: false,
       dataType: 'json',
       type:'POST',
       data: {O_id : JSON.stringify(O_id)},
       error:function(){
         alert('error');
         location.replace("shop_order.php");
       },
       success: function(res){
         alert('Cancelled all orders!');
         location.replace("shop_order.php");
       }
  });

}
</script>

<?php
if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
  echo <<< EOT
  <script>
  alert("Not logged in.");
  window.location.replace("index.php");
  </script>
  EOT;
}

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$account = $_SESSION["account"];
$phone = $_SESSION["phone"];
$U_id = $_SESSION["U_id"];

echo <<< EOT
  <h2>Shop Order</h2>
EOT;

try{
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT shopname FROM Shop INNER JOIN Job ON Shop.S_id = Job.S_id WHERE Job.U_id = ?");
	$stmt->execute(array($U_id));

  echo 'Shop &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<select id="shop_input">';
	echo '<option value="init">All</option>';
	while($row = $stmt->fetch()){
		echo '<option value = "'. $row['shopname']. '">' . $row['shopname'] . '</option>';
	}
	echo '</select><br><br>';

  echo 'Status &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<select id="status_input">';
	echo '<option value="init">All</option>';
	echo '<option value="incomplete">incomplete</option>';
  echo '<option value="finished">finished</option>';
  echo '<option value="cancelled">cancelled</option>';
	echo '</select><br><br>';

  echo'<button type="button" class="btn btn-primary mb-2" onclick="search()">Search</button>';

  echo'<br><br>';
  echo'<br><br>';

  echo'<button type="button" class="btn btn-success" onclick="finished_selected_orders()">Finished selected orders</button>';
  echo '<p>&nbsp&nbsp&nbsp</p>';
  echo'<button type="button" class="btn btn-danger" onclick="cancel_selected_orders()">Cancel selected orders</button>';
  echo '<br><br>';

  echo '<p id = "demo"></p>';

  $stmt = $conn->prepare("SELECT O_id, status, start_time, end_time, customer, finisher, shopname, price, amount, account FROM orders left JOIN Shop ON Shop.S_id = orders.S_id left JOIN Job ON orders.S_id = Job.S_id left JOIN User ON orders.customer = User.U_id WHERE Job.U_id = ?");
	$stmt->execute(array($U_id));

  echo '<table id = "OrderList" class="table">';
  echo '<tr><th> </th><th>O_id</th><th>Status</th><th>Start</th><th>End</th><th>Shop</th><th>Total Price</th><th>Action</th></tr>';


  while($row = $stmt->fetch()){
    echo '<tr><td><input type="checkbox" id="'.$row['O_id'].'" value="'.$row['O_id'].'"></td><td>' . $row['O_id'] . '</td><td>'. $row['status'] . '</td><td>'. $row['start_time'] . '<br>' . $row['account'] . '</td><td>';
    if($row['status'] == 'incomplete') {
      echo '-';
    }
    else {
      $stmt1 = $conn->prepare("SELECT account from user where U_id = :U_id");
      $stmt1->execute(array( 'U_id' => $row['finisher'] ));
      $finisher = $stmt1->fetch();
      echo $row['end_time'] . '<br>' . $finisher[0];
    }
    echo '</td><td>' . $row['shopname'] . '</td><td>$' . $row['amount'] * $row['price'] . '<br>(' . $row['amount'] . ' * $' . $row['price'] . ')</td><td>';
    if($row['status'] == 'incomplete') {
      echo
      '
      <form action="done_order.php">
      <input type="hidden" name="O_id" value="' . $row["O_id"] . '"</input>
      <input type="hidden" name="amount" value="' . $row["amount"] . '"</input>
      <input type="submit" value="Done" class="btn btn-success"></form>

      <form action="cancel_order_inshoporder.php">
      <input type="hidden" name="O_id" value="' . $row["O_id"] . '"</input>
      <input type="hidden" name="amount" value="' . $row["amount"] . '"</input>
      <input type="submit" value="X" class="btn btn-danger"></form>';

    }
    echo '</td></tr>';
	}

  echo '</table>';

  echo'<script>check_checkbox();</script>';

}
catch(PDOException $e){
  session_unset();
	session_destroy();

	echo <<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("Internal Error. :$msg");
			window.location.replace("index.php");
			</script>
		</body>
	</html>
EOT;
}
?>


</div>

</body>
</html>
