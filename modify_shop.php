<!-- Warning: NEVER echo anything in this file except for final JSON. This file should only return json file. -->

<?php session_start(); ?>
<?php 
	header("Content-Type: application/json", true);
?>
<?php

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$account = $_SESSION['account'];

try {
	// modify the value as entered
	if (isset($_POST['price']) && (!empty($_POST['price'] || $_POST['price'] == 0))) {
		$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
		# set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$newPrice = $_POST['price'];
		$stmt = $conn->prepare( "UPDATE shop SET mask_price=:newPrice 
								WHERE S_id = (SELECT S_id from job 
								WHERE U_id = (SELECT U_id from user WHERE account=:account) AND position='owner')" );
		$stmt->execute(array('newPrice' => $newPrice, 'account' => $account));
		// header('Content-Type: application/json');
		// echo json_encode(array('foo' => 'bar'));
		echo json_encode(array('success' => '1'));
		exit;
	}
	else if (isset($_POST['amount']) && (!empty($_POST['amount'] || $_POST['amount'] == 0))) {
		$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
		# set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$newAmount = $_POST['amount'];
		$stmt = $conn->prepare( "UPDATE shop SET mask_amount=:newAmount 
								WHERE S_id = (SELECT S_id from job 
								WHERE U_id = (SELECT U_id from user WHERE account=:account) AND position='owner')" );
		$stmt->execute(array('newAmount' => $newAmount, 'account' => $account));
		// header('Content-Type: application/json');
		// echo json_encode(array('foo' => 'bar'));
		echo json_encode(array('success' => '1'));
		exit;
	}
	else if (isset($_POST['employee_account']) && !empty($_POST['employee_account'])) {
		$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// First, check if it's already an employee
		$newEmployee = $_POST['employee_account'];
		$stmt = $conn->prepare("SELECT count(*) from job where S_id = (SELECT S_id from job 
								WHERE U_id = (SELECT U_id from user WHERE account=:account) AND position='owner')");
		$stmt->execute(array('account' => $account));
		$row = $stmt->fetch();
		$alreadyInShop = $row[0];
		if($alreadyInShop > 0) {
			echo json_encode(array('success' => '0'));
			exit;
		}
		else {
			$stmt = $conn->prepare( "INSERT INTO job(U_id, S_id, position) VALUES 
									(SELECT U_id from user WHERE account=:newEmployee),
									(SELECT S_id from job WHERE U_id = (SELECT U_id from user WHERE account=:account) AND position='owner'),
									'employee'");
			$stmt->execute(array('account' => $account, ':newEmployee' => $newEmployee));
			// header('Content-Type: application/json');
			// echo json_encode(array('foo' => 'bar'));
			echo json_encode(array('success' => '1'));
			exit;
		}
	}
	else {
		echo json_encode(array('success' => '0'));
	}

} catch (Exception $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	echo json_encode(array('success' => '0'));
}

?>