<!-- check for valid shops -->
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

$shopname = $city = $price = $amount = "";
$shopnameErr = $cityErr = $priceErr = $amountErr = "";

try {
	if (!isset($_POST["shopname"]) || !isset($_POST["city"])) {
		throw new Exception("error with code !!");
		// header("Location: index.php");
		// exit();
	}
	if (empty($_POST["shopname"])) {
		$shopnameErr = "Required !";
	}
	else {
		$shopname = $_POST["shopname"];
	}

	$city = $_POST["city"];

	// if (!isset($_POST["price"])) {
	if (empty($_POST["price"]) && $_POST["price"] != 0) {
		$priceErr = "Required !";
	}
	else {
		$price = $_POST["price"];
		if ($price < 0) {
			$priceErr = "Should be nonnegative number.";
		}
	}
	if (empty($_POST["amount"]) && $_POST["amount"] != 0) {
		$amountErr = "Required !";
	}
	else {
		$amount = $_POST["amount"];
		if ($amount < 0) {
			$amountErr = "Should be nonnegative number.";
		}
	}
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("select shopname from shop where shopname=:shopname");
	$stmt->execute(array('shopname' => $shopname));

	if ($stmt->rowCount() > 0) {
		$shopnameErr = "shop has been registered.";
	}

	if (empty($shopnameErr) && empty($cityErr) && empty($priceErr) && empty($amountErr) && $stmt->rowCount() == 0) {
		// update table shop 
		$stmt = $conn->prepare("INSERT INTO shop (shopname, mask_amount, mask_price, city) values (:shopname, :amount, :price, :city)");
		$stmt->execute(array('shopname' => $shopname, 'city' => $city, 'amount' => (int)$amount, 'price'=> (int)$price));

		// if ($amount == 0) {
		// 	echo <<< EOT
		// 		<script>
		// 		alert("Got amount 0.");
		// 		</script>
		// 	EOT;
		// 	$stmt = $conn->prepare("INSERT INTO shop (shopname, mask_amount, mask_price, city) values (:shopname, 0, :price, :city)");
		// 	$stmt->execute(array('shopname' => $shopname, 'city' => $city, 'price'=> (int)$price));
		// }
		// else {
		// 	$stmt = $conn->prepare("INSERT INTO shop (shopname, mask_amount, mask_price, city) values (:shopname, :amount, :price, :city)");
		// 	$stmt->execute(array('shopname' => $shopname, 'city' => $city, 'amount' => (int)$amount, 'price'=> (int)$price));
		// }
		echo <<< EOT
			<script>
			alert("Create an shop successfully.");
			</script>
		EOT;

		// update table job
		$stmt = $conn->prepare("insert into job (U_id, S_id, position) values 
								((select U_id from user where account = :account),
								 (select S_id from shop where shopname = :shopname), 
								 'owner' )");
		$stmt->execute(array('account' => $_SESSION['account'], 'shopname' => $shopname));
		// echo <<< EOT
		// 	<script>
		// 	alert("Update owner of shop successfully.");
		// 	window.location.replace("clf_owner.php");
		// 	</script>
		// EOT;
		exit();
	}
	else {
	// sent error with session variables
		$_SESSION['shopnameErr'] = $shopnameErr;
		$_SESSION['cityErr'] = $cityErr;
		$_SESSION['priceErr'] = $priceErr;
		$_SESSION['amountErr'] = $amountErr;
		// go back to index_reg
		header("location: register_shop.php");
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
				window.location.replace("clf_owner.php");
				</script>
			</body>
		</html>
EOT;
}

?>
