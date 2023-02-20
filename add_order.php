<?php session_start(); ?>

<?php
if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
	echo <<< EOT
	<script>
	alert("Not logged in.");
	window.location.replace("index.php");
	</script>
	EOT;
}

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$buy_amount = "";

try {
	if (empty($_GET["buy_amount"]) && $_GET["buy_amount"] != 0) {
		echo <<< EOT
		<script>
		alert("You should input the amount of mask you want.");
		window.location.replace("home.php");
		</script>
		EOT;		
		exit();
	}
	else {
		$buy_amount = $_GET["buy_amount"];
		if (!is_numeric($buy_amount) || $buy_amount < 1 || $buy_amount != round($buy_amount)) {
			echo <<< EOT
			<script>
			alert("Input should be positive integer.");
			window.location.replace("home.php");
			</script>
			EOT;
			exit();
		}
	}

	$account = $_SESSION['account'];
	$shopname = $_SESSION['shopname'];
	$S_id = $_GET['S_id'];

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("SELECT mask_amount from shop where S_id=:S_id");
	$stmt->execute(array('S_id' => $S_id));
	$row = $stmt->fetch();
	$left_mask_amount = $row[0];
	if($left_mask_amount < $buy_amount) {
		echo <<< EOT
		<script>
		alert("There's not that many masks left in this shop.");
		window.location.replace("home.php");
		</script>
		EOT;			
		exit();
	}
	else {
		// update mask amount in shop
		$stmt = $conn->prepare("UPDATE shop SET mask_amount = :new_mask_amount WHERE S_id=:S_id");
		$stmt->execute(array('new_mask_amount' => $left_mask_amount - $buy_amount, 'S_id' => $S_id));
		// update order in orders
		$stmt = $conn->prepare("INSERT INTO orders (S_id, customer, price, amount) VALUES (:S_id, 
								(SELECT U_id from user where account=:account), 
							    (SELECT mask_price from shop where S_id=:S_id), :buy_amount)");
		// NOT SURE IF I CAN ONLY DECLARE S_id ONCE HERE
		$stmt->execute(array('S_id' => $S_id, 'account' => $account, 'buy_amount' => $buy_amount));

		echo <<< EOT
			<script>
			alert("Ordered $buy_amount masks successfully.");
			window.location.replace("home.php");
			</script>
		EOT;
		exit();
	}
} catch (Exception $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	echo <<< EOT
		<script>
		alert("$msg");
		window.location.replace("home.php");
		</script>
EOT;
}

?>
