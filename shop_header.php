<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!-- refer to boostrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<title>Shop</title>
<style>
.error {color: #FF0000;}
</style>
</head>


<body>

<div class="container">
<a href="home.php">Home</a>
<span>&nbsp&nbsp</span>
<a href="clf_owner.php">Shop</a>
<span>&nbsp&nbsp</span>
<a href="my_order.php">My Order</a>
<span>&nbsp&nbsp</span>
<a href="shop_order.php">Shop Order</a>
<span>&nbsp&nbsp</span>
<a href="index.php" onclick="logout()">Logout</a>

<script type="text/javascript">
	function logout(){
		<?php session_destroy();  ?>
	}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
