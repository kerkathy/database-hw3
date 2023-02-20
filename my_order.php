<?php session_start(); ?>
<?php echo file_get_contents("my_order_header.php"); ?>

<script type="text/javascript">
	function filterOrder(){
		// window.alert("filtering");
		// document.getElementById('demo').innerHTML = "You pressed."
		var status_input = document.getElementById("status_input");
		var wanted_status = status_input.options[status_input.options.selectedIndex].value;
		var table = document.getElementById("OrderList");
		var tr = table.getElementsByTagName("tr");
		var td1, td_status;
		for (var i = 0; i < tr.length; i++) {
			td1 = tr[i].getElementsByTagName("td")[2]; // status
			if(td1) {
				td_status = td1.textContent || td1.innerText;
				if(wanted_status == "init" || td_status == wanted_status){
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
	         location.replace("my_order.php");
	       },
	       success: function(res){
	         alert('Cancelled all orders!');
	         location.replace("my_order.php");
	       }
	  });

	}

</script>

<?php

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

try {
	if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
		echo <<< EOT
		<script>
		alert("Not logged in.");
		window.location.replace("index.php");
		</script>
		EOT;
	}
	$account = $_SESSION["account"];
	$phone = $_SESSION["phone"];
	$U_id = $_SESSION["U_id"];

	// echo '<p id = "demo"> demo </p>';

	echo <<< EOT
		<h2>My Order</h2>
	EOT;

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT DISTINCT status from orders WHERE customer = ?");
	$stmt->execute(array($U_id));

	echo '
	<table class="table">
	<tr>
		<th>Status</th>
		<td>
		<select id="status_input">';
	echo '<option value="init"></option>';

	while($row = $stmt->fetch()){
		echo '<option value = "'. $row['status']. '">' . $row['status'] . '</option>';
	}
	echo '
		</td>
	</tr>
	<tr>
		<td>
		<button onclick="filterOrder()" class="btn btn-primary mb-2" type="button">Search</button>
		</td>
	</tr>
	</table>';


	echo'<button type="button" class="btn btn-danger" onclick="cancel_selected_orders()">Cancel selected orders</button>';
  echo '<br><br>';

	$stmt = $conn->prepare("SELECT O_id, shop.shopname as shopname, status, customer, finisher, price, amount, start_time, end_time
							from orders left join shop on shop.S_id = orders.S_id");
	$stmt->execute();
	echo '<table id = "OrderList" class="table">';
	echo '<tr><th></th><th>O_id</th><th>Status</th><th>Start</th><th>End</th><th>Shop</th><th>Total Price</th><th>Action</th></tr>';
	while($row = $stmt->fetch()){
		if($row['customer'] == $U_id) {
			$stmt2 = $conn->prepare("SELECT account from user where U_id = :U_id");
			$stmt2->execute(array( 'U_id' => $row['customer'] ));
			$customer = $stmt2->fetch();
			echo '<tr><td><input type="checkbox" id="'.$row['O_id'].'" value="'.$row['O_id'].'"></td><td>' . $row['O_id'] . '</td><td>'. $row['status'] . '</td><td>'. $row['start_time'] . '<br>' . $customer[0] . '</td><td>';
			if($row['status'] == 'incomplete') {
				echo '-';
			}
			else {
				$stmt3 = $conn->prepare("SELECT account from user where U_id = :U_id");
				$stmt3->execute(array( 'U_id' => $row['finisher'] ));
				$finisher = $stmt3->fetch();
				echo $row['end_time'] . '<br>' . $finisher[0];
			}
			// TODO: action
			echo '</td><td>' . $row['shopname'] . '</td><td>$' . $row['amount'] * $row['price'] . '<br>(' . $row['amount'] . ' * $' . $row['price'] . ')</td><td>';
			if($row['status'] == 'incomplete') {
				echo
				'<form action="cancel_order.php">
				<input type="hidden" name="O_id" value="' . $row["O_id"] . '"</input>
				<input type="hidden" name="amount" value="' . $row["amount"] . '"</input>
				<input type="submit" value="X" class="btn btn-danger"></form>';

				// '<button id="cancelOrder" class="btn btn-danger mb-2" type="button">X</button>';
			}
			echo '</td></tr>';
		}
	}
	echo '</table>';

	echo'<script>check_checkbox();</script>';

}

catch (PDOException $e) {
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
