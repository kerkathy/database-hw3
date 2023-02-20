<?php session_start(); ?>
<?php echo file_get_contents("shop_header.php"); ?>
<script src='myshop.js'></script>

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
		exit();
		</script>		
EOT;
	}

	$account = $_SESSION['account'];
	if(!empty($_SESSION['newEmployeeErr'])) $newEmployeeErr = $_SESSION['newEmployeeErr'];
	else $newEmployeeErr = "";

	// Get shop information
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT shopname, mask_amount, mask_price, city from shop
							where S_id in (select S_id from job where position = 'owner' 
							and U_id = (select U_id from user where account = :account))");
	$stmt->execute(array('account' => $account));
	$row = $stmt->fetch();

	$shopname = $row['shopname'];
	$city = $row['city'];
	$price = $row['mask_price'];
	$amount = $row['mask_amount'];
	$_SESSION['shopname'] = $shopname;
} catch (PDOException $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();

	echo <<<EOT
		<script>
		alert("Internal Error. $msg");
		window.location.replace("clf_owner.php");
		</script>
	EOT;
}
?>

<!-- Show shop information -->

<h3><br>My shop</h3>
<table class="table">
<tr>
	<th>Shop</th>
	<td><?php echo $shopname; ?></td>

</tr>
<tr>
	<th>City</th>
	<td><?php echo $city; ?></td>
</tr>
<tr>
	<th>Mask Price</th>
	<td><input type="number" min=0 class="form-control mb-2 mr-sm-2" value="<?php echo $price; ?>" id="price" disabled></td>
	<td><button class="btn btn-primary mb-2" id="priceBtn" name="price">Edit</button></td>
</tr>
<tr>
	<th>Mask Amount</th>
	<td><input type="number" min=0 class="form-control mb-2 mr-sm-2" value="<?php echo $amount; ?>" id="amount" disabled></td>
	<td><button type="submit" class="btn btn-primary mb-2" id="amountBtn" name="amount">Edit</button></td>
</tr>

</table>

<h3><br>Employee</h3>
<form action="add_employee.php">
	<div class="form-row">
		<div class="form-group col-md-3">
			<input type="text" class="form-control mb-2 mr-sm-2" placeholder="Type Account" name="newEmployee">
		</div>
		<div class="form-group col-md-1">
			<button class="btn btn-primary mb-2">Add</button> 
		</div>
		<span class="error">* <?php echo $newEmployeeErr;?></span>
	</div>
</form>
<br>

<?php

try {
	// Get employee list with their info
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT account, phone from user where U_id in 
							(select U_id from job 
							where S_id = (select S_id from shop where shopname = :shopname) and position = 'employee')");
	$stmt->execute(array('shopname' => $shopname));

	echo '<table id = "EmployeeList" class="table">';
	echo '<tr><th>Account</th><th>Phone</th>';
	while($row = $stmt->fetch()){
		echo '<tr><td>'. $row['account']. '</td><td>'. $row['phone']. 
		'</td><td><form action="del_employee.php">
		<input type="hidden" name="delEmployee" value="' . $row["account"] . '"</input>
		<input type="submit" value="Delete" class="btn btn-outline-danger"></form></td></tr>';
	}
	echo '</table>';
	echo '<br>';
}

catch (PDOException $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();

	echo <<<EOT
		<script>
		alert("Internal Error. $msg");
		window.location.replace("clf_owner.php");
		</script>
	EOT;
}
?>

<!-- reset all session error variables -->
<?php 
	unset($_SESSION["newEmployeeErr"]);
?>


</div>

</body>
</html>
