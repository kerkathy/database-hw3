<!-- Add new employees -->
<?php session_start(); ?>

<?php
if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
	header("Location: index.php");
	exit();
}

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$delEmployee = "";
$delEmployeeErr = "";

try {
	if (empty($_GET["delEmployee"])) {
		throw new Exception("you are deleting nothing.");
	}
	else {
		$delEmployee = $_GET["delEmployee"];
	}

	$account = $_SESSION['account'];
	$shopname = $_SESSION['shopname'];

	// check if account not exist / it's already an employee
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("DELETE FROM job WHERE 
							U_id = (select U_id from user where account=:delEmployee) and
							S_id in (select S_id from shop where shopname=:shopname)");
	$stmt->execute(array('delEmployee' => $delEmployee, 'shopname' => $shopname));

	if (!empty($delEmployeeErr)) {
	// sent error with session variables
		$_SESSION['delEmployeeErr'] = $delEmployeeErr;
		echo <<< EOT
			<script>
			window.location.replace("myshop.php");
			</script>
		EOT;
		// header("location: myshop.php");
	}
	else {
		echo <<< EOT
			<script>
			alert("Delete $delEmployee successfully!");
			window.location.replace("myshop.php");
			</script>
		EOT;
	}

} catch (Exception $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	echo <<< EOT
		<script>
		alert("$msg");
		window.location.replace("myshop.php");
		</script>
EOT;
}

?>
