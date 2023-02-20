<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!-- refer to boostrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<title>My Order</title>
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


<!-- <p id = "demo"> demo </p> -->


<script type="text/javascript">
	function logout() {
		<?php session_destroy();  ?>
	}

	// Don't know why if I put this function here it won't be call by onclick..
	// function filterOrder(){
	// 	// window.alert("filtering");
	// 	// document.getElementById('demo').innerHTML = "You pressed."
	// 	var status_input = document.getElementById("status_input");
	// 	var wanted_status = status_input.options[status_input.options.selectedIndex].value;
	// 	var table = document.getElementById("OrderList");
	// 	var tr = table.getElementsByTagName("tr");
	// 	var td1, td_status;
	// 	for (var i = 0; i < tr.length; i++) {
	// 		td1 = tr[i].getElementsByTagName("td")[1]; // status
	// 		if(td1) {
	// 			td_status = td1.textContent || td1.innerText;
	// 			if(wanted_status == "init" || td_status == wanted_status){
	// 				tr[i].style.display = "";
	// 			}
	// 			else{
	// 				tr[i].style.display = "none";
	// 			}
	// 		}
	// 	}
	// }
</script>