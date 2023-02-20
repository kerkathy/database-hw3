<?php session_start(); ?>
<?php 

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
# set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$account = $_SESSION['account'];
$stmt = $conn->prepare( "select count(*) as cnt from job where U_id in (select U_id from user where account = '$account') and position = 'owner'" );
$stmt->execute();
$row = $stmt->fetch();
$isOwner = $row[0];

if($isOwner > 0) {
	header("Location: myshop.php");
}
else header("Location: register_shop.php");
?>