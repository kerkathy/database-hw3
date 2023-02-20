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

try{
  if (empty($_GET["O_id"])) {
		throw new Exception("you done nothing.");
	}
	else {
		$O_id = $_GET["O_id"];
		$amount = $_GET["amount"];
	}

  $account = $_SESSION['account'];
	$U_id = $_SESSION['U_id'];

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT status from orders where O_id = :O_id");
	$stmt->execute(array('O_id' => $O_id));
	$curr_status = $stmt->fetch()[0];
	if($curr_status != "incomplete") {
		echo <<< EOT
			<script>
			alert("This order is already $curr_status. ");
			window.location.replace("shop_order.php");
			</script>
		EOT;
	}
  else {
		$stmt = $conn->prepare("UPDATE orders SET status = 'finished', finisher = :U_id, end_time = CURRENT_TIMESTAMP()
								WHERE O_id = :O_id");
		$stmt->execute(array('U_id' => $U_id, 'O_id' => $O_id));

		echo <<< EOT
			<script>
			alert("Done order # $O_id successfully!");
			window.location.replace("shop_order.php");
			</script>
		EOT;
  }
}
catch(Exception $e){
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
