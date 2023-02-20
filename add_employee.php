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

$newEmployee = "";
$newEmployeeErr = "";

try {
	// if (!isset($_POST["shopname"]) || !isset($_POST["city"])) {
	// 	throw new Exception("error with code !!");
	// 	// header("Location: index.php");
	// 	// exit();
	// }
	if (empty($_GET["newEmployee"])) {
		$newEmployeeErr = "Required !";
	}
	else {
		$newEmployee = $_GET["newEmployee"];
	}

	$account=$_SESSION['account'];
	$shopname=$_SESSION['shopname'];

	if($newEmployee != ""){

		// check if account not exist
		$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
		# set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare("SELECT count(*) from user where account=:account");
		$stmt->execute(array('account' => $newEmployee));
		$row = $stmt->fetch();
		$accountExist = $row[0];
		if($accountExist < 1) {
			$newEmployeeErr = "User doesn't exist.";
		}
		else {
			// check if it's already an employee
			$stmt = $conn->prepare("SELECT count(*) from job where 
									U_id = (select U_id from user where account=:newEmployee) and
									S_id in (select S_id from shop where shopname=:shopname)");
			$stmt->execute(array('newEmployee' => $newEmployee, 'shopname' => $shopname));
			$row = $stmt->fetch();
			$alreadyEmployee = $row[0];
			if($alreadyEmployee > 0) {
				$newEmployeeErr = "This user is already in your shop.";
			}
			else {
				//update table job
				$stmt = $conn->prepare("INSERT INTO job (U_id, S_id, position) values 
										((select U_id from user where account = :newEmployee),
										 (select S_id from shop where shopname=:shopname),
										 'employee')
										 ");
				$stmt->execute(array('newEmployee' => $newEmployee, 'shopname' => $shopname));
				echo <<< EOT
					<script>
					alert("Added employee $newEmployee successfully.");
					window.location.replace("myshop.php");
					</script>
				EOT;
				exit();
			}
		}
	}
	if (!empty($newEmployeeErr)) {
	// sent error with session variables
		$_SESSION['newEmployeeErr'] = $newEmployeeErr;
		// go back to index_reg
		echo <<< EOT
			<script>
			window.location.replace("myshop.php");
			</script>
		EOT;
		// header("location: myshop.php");
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
