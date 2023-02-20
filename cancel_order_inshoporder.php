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

$O_id = "";
$O_idErr = "";

try {
	if (empty($_GET["O_id"])) {
		throw new Exception("you are deleting nothing.");
	}
	else {
		$O_id = $_GET["O_id"];
		$amount = $_GET["amount"];
	}

	$account = $_SESSION['account'];
	$U_id = $_SESSION['U_id'];
	// $shopname = $_SESSION['shopname'];

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("SELECT status from orders where O_id = :O_id");
	$stmt->execute(array('O_id' => $O_id));
	$curr_status = $stmt->fetch()[0];
	if($curr_status != "incomplete") {
		// already been cancelled or finished
		echo <<< EOT
			<script>
			alert("This order is already $curr_status. ");
			window.location.replace("shop_order.php");
			</script>
		EOT;
	}

	else {
		$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled', finisher = :U_id, end_time = CURRENT_TIMESTAMP()
								WHERE O_id = :O_id");
		$stmt->execute(array('U_id' => $U_id, 'O_id' => $O_id));

		$stmt = $conn->prepare("UPDATE shop SET mask_amount = mask_amount + :amount
								WHERE S_id = (select S_id from orders where O_id = :O_id)");
		$stmt->execute(array('amount' => $amount, 'O_id' => $O_id));

		echo <<< EOT
			<script>
			alert("Delete order # $O_id successfully!");
			window.location.replace("shop_order.php");
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
		window.location.replace("shop_order.php");
		</script>
EOT;
}

?>
