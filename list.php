<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Login</title>
</head>
<body>

<?php
#ini_set("display_errors","On");
#error_reporting(E_ALL);

session_start();

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

try {
	if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
		header("Location: index.php");
		exit();
	}
	echo "Account " . $_SESSION["account"] . "<br>";
	echo "Phone " . $_SESSION["phone"] . "<br>";

	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	$postperpage = 2;

	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
	# set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("select count(*) from shop");
	$stmt->execute();
	$row = $stmt->fetch();
	$totalpage = ceil($row[0]/$postperpage);

	echo <<< EOT
		<!DOCTYPE html>
		<html>
			<body>
				<a href="index.php">Logout</a>
				<br>
			</body>
		</html>
EOT;
				// <button type="button"
				// 	onClick = "window.location.replace("index.php");">
				// 	Logout
				// </button>

	if ($totalpage>0) {
		for($i = 1; $i <= $totalpage; $i++) {
			if ($i == $page)
				echo "$i ";
			else
				echo "<a href='list.php?page=$i'>$i</a> ";
		}
		echo '<br>';
		$startrow = ($page-1)*$postperpage;
		$stmt = $conn->prepare("select shopname, mask_amount from shop limit $startrow, 2");
		$stmt->execute();
		echo '<ul>'; 	# unordered list
		while($row=$stmt->fetch())
			echo '<li>' . $row['shopname'] . '<br>' . $row['mask_amount'] . '<br><br> </li>';
	}
	echo '</ul></body></html>';
}
catch (PDOException $e) {
	session_unset();
	session_destroy();

	echo <<<EOT
	<!DOCTYPE html>
	<html>
		<body>
			<script>
			alert("Internal Error. $msg");
			window.location.replace("index.php");
			</script>
		</body>
	</html>
EOT;
}
?>
</body>
</html>
