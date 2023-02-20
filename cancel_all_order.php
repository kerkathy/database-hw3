<?php session_start(); ?>
<?php
	header("Content-Type: application/json", true);
?>
<?php

$dbservername = 'localhost';
$dbname = 'hw2';
$dbusername = 'root';
$dbpassword = '';

$O_id = "";

try{
  $O_id_j = json_decode($_POST['O_id'], true);

  for($i = 0; $i < sizeof($O_id_j); $i++){

    $O_id = $O_id_j[$i];
    $account = $_SESSION['account'];
  	$U_id = $_SESSION['U_id'];

  	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  	$stmt = $conn->prepare("SELECT status from orders where O_id = :O_id");
  	$stmt->execute(array('O_id' => $O_id));
  	$curr_status = $stmt->fetch()[0];
  	if($curr_status != "incomplete") {
      echo json_encode(array('success' => '1'));
  	}

  	else {
  		$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled', finisher = :U_id, end_time = CURRENT_TIMESTAMP()
  								WHERE O_id = :O_id");
  		$stmt->execute(array('U_id' => $U_id, 'O_id' => $O_id));

      $stmt = $conn->prepare("SELECT amount FROM orders WHERE O_id = ?");
  		$stmt->execute(array($O_id));
      $amount = $stmt->fetch()[0];

      $stmt = $conn->prepare("UPDATE shop SET mask_amount = mask_amount + :amount
  								WHERE S_id = (select S_id from orders where O_id = :O_id)");
  		$stmt->execute(array('amount' => $amount, 'O_id' => $O_id));

  	}
  }



  echo json_encode(array('success' => '1'));
}
catch (Exception $e) {
	$msg = $e->getMessage();
	session_unset();
	session_destroy();
	echo json_encode(array('success' => '0'));
}

?>
