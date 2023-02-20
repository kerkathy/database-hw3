<?php
session_start();
$_SESSION['Authencated'] = False;

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

// define variables and set to empty values
$accountErr = $pwdErr = $confirmedpwdErr = $phoneErr = "";
$account = $password = $confirmedpwd = $phone = "";

try {
	if (!isset($_POST["account"]) || !isset($_POST["password"])) {
		throw new Exception("error with code !!");
		header("Location: index.php");
		exit();
	}
	if (empty($_POST["account"])) {
		$accountErr = "Required !";
	}
	else {
		$account = $_POST["account"];
		// check account
		if (!preg_match("/^[a-zA-Z0-9]*$/", $account)) {
			$accountErr = "Only letters and numbers are allowed";
		}
	}
	if (empty($_POST["password"])) {
		$pwdErr = "Required !";
	}
	else {
		$password = $_POST["password"];
		if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
			$pwdErr = "Only letters and numbers are allowed.";
		}
	}
	if (empty($_POST["confirmedpwd"])) {
		$confirmedpwdErr = "Required !";
	}
	else {
		$confirmedpwd = $_POST["confirmedpwd"];
		if ($confirmedpwd != $password) {
			$confirmedpwdErr = "Password mismatch.";
		}
	}
	if (empty($_POST["phone"])) {
		$phoneErr = "Required !";
	}
	else {
		$phone = $_POST["phone"];
		if (!preg_match("/^[0-9]{10}$/", $phone)) {
			$phoneErr = "Should be 10-digit number. Ex:0912345678";
		}
	}
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("select account from user where account=:account");
	$stmt->execute(array('account' => $account));

	if ($stmt->rowCount() > 0) {
		$accountErr = "Account has been registered.";
	}

	if (empty($accountErr) && empty($pwdErr) && empty($confirmedpwdErr) && empty($phoneErr) && $stmt->rowCount() == 0) {
		$salt = strval(rand(1000, 9999));
		$hashvalue = hash('sha256', $salt.$password);

		// Construct prepared statement
		$stmt = $conn->prepare("INSERT INTO user (account, password, phone, salt) values (:account, :password, :phone, :salt)");
		$stmt->execute(array('account' => $account, 'password' => $hashvalue, 'phone' => $phone, 'salt'=> $salt));
		$_SESSION['Authencated'] = True;

		echo <<< EOT
			<!DOCTYPE html>
			<html>
				<body>
					<script>
					alert("Create an account successfully.");
					window.location.replace("index.php");
					</script>
				</body>
			</html>
		EOT;
		exit();
	}
	else {
		// throw new Exception("Register failed.");

	// sent error with session variables
		$_SESSION['accountErr'] = $accountErr;
		$_SESSION['pwdErr'] = $pwdErr;
		$_SESSION['confirmedpwdErr'] = $confirmedpwdErr;
		$_SESSION['phoneErr'] = $phoneErr;
		header("location: index_reg.php");
	}

} catch (Exception $e) {
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
