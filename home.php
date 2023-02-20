<?php session_start(); ?>
<?php echo file_get_contents("home_header.php"); ?>
<?php
#ini_set("display_errors","On");
#error_reporting(E_ALL);

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


	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	$postperpage = 5;

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("SELECT count(*) from shop");
	$stmt->execute();
	$row = $stmt->fetch();
	$totalpage = ceil($row[0]/$postperpage);



	echo <<< EOT
		<h2>Profile</h2>
		<h5>Account&nbsp&nbsp&nbsp&nbsp$account</h5>
		<h5>Phone&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$phone</h5>
		<br>
		<h2>Shop List</h2>
		Shop &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="shop_input" onkeyup="filter()"><br><br>
	EOT;

	$stmt = $conn->prepare("SELECT distinct city from shop");
	$stmt->execute();
	echo 'City &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<select id="city_input" onchange="filter()">';
	echo '<option value="init"></option>';
	while($row = $stmt->fetch()){
		echo '<option value = "'. $row['city']. '">' . $row['city'] . '</option>';
	}
	echo '</select><br><br>';

	echo 'Price &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="price_input1" onkeyup="filter()"> ~ <input type="text" id="price_input2" onkeyup="filter()"><br><br>';

	echo 'Amount &nbsp&nbsp<select id="amount_input" onchange="filter()">';
	echo '<option value = "init"></option>';
	echo '<option value = "sold_out">(售完)0</option>';
	echo '<option value = "rare">(稀少)1~99</option>';
	echo '<option value = "sufficient">(充足)100+</option>';
	echo '</select><br><br>';

	$stmt = $conn->prepare("SELECT U_id from User WHERE account = '$account'");
	$stmt->execute();
	$row = $stmt->fetch();
	$U_id = $row['U_id'];
	$stmt = $conn->prepare("SELECT S_id from Job WHERE U_id = '$U_id'");
	$stmt->execute();

	$count = $stmt->rowCount();
	#echo $count;

	if((int)$count > 0){
		$row = $stmt->fetch();
		$S_id = $row['S_id'];
		$stmt = $conn->prepare("SELECT shopname from Shop WHERE S_id = '$S_id'");
		$stmt->execute();
		$row = $stmt->fetch();
		$myshop = $row['shopname'];
		#echo $myshop;
		#echo '<p id = "demo"></p>';
		echo '<input type = "checkbox" id = "myshop" name = "myshop" value = ' . $myshop . ' onclick="filter()">';
		echo '<label for = "myshop"> &nbspOnly show the shop I work at </label>';
	}



	echo '<br><br>';
	if ($totalpage>0) {
		$sts = 0;
		$startrow = ($page-1)*$postperpage;
		$stmt = $conn->prepare("SELECT S_id, shopname, city, mask_price, mask_amount from shop limit $startrow, $postperpage");
		$stmt->execute();
		echo '<table id = "ShopList">'; 	# unordered list
		echo '<tr><th onclick="sortTableShop(); stsclick++">Shop</th><th onclick="sortTableCity(); stcclick++">City</th><th onclick="sortTableMaskPrice(); stpclick++">Mask Price</th><th onclick="sortTableMaskAmount(); staclick++">Mask Amount</th>';

		while($row=$stmt->fetch()){
			echo '<tr><td>'. $row['shopname']. '</td><td>'. $row['city']. '</td><td>'. $row['mask_price']. '</td><td>'. $row['mask_amount'] . '</td>
		<td><form action="add_order.php">
		<div class="form-row">
			<div class="form-group col-md-3">';
			if ((int)$row['mask_amount'] == 0) 
				echo '<input type="text" class="form-control mb-2 mr-sm-2" value=0 name="buy_amount" disabled>';
			else
				echo '<input type="text" class="form-control mb-2 mr-sm-2" value=0 name="buy_amount" >';
			echo '
			<input type="hidden" class="form-control mb-2 mr-sm-2" value='. $row['S_id'] .' name="S_id" >
			</div>
			<div class="form-group col-md-1">';
			if ($row['mask_amount'] == 0) 
				echo '<button class="btn btn-primary mb-2" disabled>Buy!</button> ';
			else 
				echo '<button class="btn btn-primary mb-2">Buy!</button> ';
			echo '</div>
		</div>
		</form></td>
		</tr>';
		}

		echo '</table>';
		echo '<br>';
		for($i = 1; $i <= $totalpage; $i++) {
			if ($i == $page)
				echo "$i ";
			else
				echo "<a href='home.php?page=$i'> $i</a> ";
		}
	}
	echo '</body></html>';
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
