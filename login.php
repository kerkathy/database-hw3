<?php
session_start();
$_SESSION['Authencated'] = False;

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

try {
	$account = $_POST["account"];
	$password = $_POST["password"];
	if (!isset($account) || !isset($password)) {
		header("Location: index_.php");
		exit();
	}
	if (empty($account) || empty($password)) {
		throw new Exception("Please input account name and password.");
	}
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("select U_id, account, password, salt, phone from user where account=:account");
	$stmt->execute(array('account' => $account));

	if ($stmt->rowCount() == 1) {
		$row = $stmt->fetch();
		if($row['password'] == hash('sha256', $row['salt'] . $password)) {
			$_SESSION['Authencated'] = True;
			$_SESSION['account'] = $account;
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['U_id'] = $row['U_id'];
			header("Location: home.php");
			exit();
		}
		else {
			throw new Exception("Login failed.");
		}
	}
	else {
		throw new Exception("Login failed.");
	}
}
catch (Exception $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	echo <<< EOT
		<!DOCTYPE html>
		<html>
			<body>
				<script>
				alert("$msg");
				window.location.replace("index.php");
				</script>
			</body>
		</html>
EOT;
}
?>
