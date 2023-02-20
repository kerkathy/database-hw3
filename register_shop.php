<!-- the webpage with tab "shop" -->
<?php session_start(); ?>
<?php echo file_get_contents("home_header.php"); ?>

<?php
#ini_set("display_errors","On");
#error_reporting(E_ALL);

if (!isset($_SESSION['Authencated']) || $_SESSION['Authencated'] != True) {
	echo <<< EOT
		<script>
		alert("Not logged in.");
		window.location.replace("index.php");
		</script>
	EOT;
	// header("Location: index.php");
	// exit();
}

if(isset($_SESSION['shopnameErr'])) $shopnameErr = $_SESSION['shopnameErr'];
else $shopnameErr = "";
if(isset($_SESSION['cityErr'])) $cityErr = $_SESSION['cityErr'];
else $cityErr = "";
if(isset($_SESSION['priceErr'])) $priceErr = $_SESSION['priceErr'];
else $priceErr = "";
if(isset($_SESSION['amountErr'])) $amountErr = $_SESSION['amountErr'];
else $amountErr = "";
?>

<h2>Register Shop</h2>
<h4>Since you don't have any shop yet, you can register here to sell masks. :)</h4>

<div class="container">
<form class="form-horizontal" action="validate_shop.php" target="_self" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Shop </label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="shopname" method="post" >
		</div>
		<div class="col-sm-5">
			<span class="error">* <?php echo $shopnameErr;?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">City </label>
		<div class="col-sm-5">
			<select name="city" class="form-control" method="post">
				 <option value="Keelung">Keelung</option>
				 <option value="Taipei">Taipei</option>
				 <option value="Taoyuan">Taoyuan</option>
				 <option value="Hsinchu">Hsinchu</option>
				 <option value="Miaoli">Miaoli</option>
				 <option value="Taichung">Taichung</option>
				 <option value="Changhua">Changhua</option>
				 <option value="Nantou">Nantou</option>
				 <option value="Yunlin">Yunlin</option>
				 <option value="Chiayi">Chiayi</option>
				 <option value="Tainan">Tainan</option>
				 <option value="Kaohsiung">Kaohsiung</option>
				 <option value="Pingtung">Pingtung</option>
				 <option value="Yilan">Yilan</option>
				 <option value="Hualien">Hualien</option>
				 <option value="Taitung">Taitung</option>
			</select>
		</div>
		<div class="col-sm-5">
			<span class="error">* <?php echo $cityErr;?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Mask Price </label>
		<div class="col-sm-5">
			<input type="number" class="form-control" name="price" method="post">
		</div>
		<div class="col-sm-5">
			<span class="error">* <?php echo $priceErr;?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Mask Amount </label>
		<div class="col-sm-5">
			<input type="number" class="form-control" name="amount" method="post">
		</div>
		<div class="col-sm-5">
			<span class="error">* <?php echo $amountErr;?></span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary">Register</button>
		</div>
	</div>
	<!-- <input type="submit" value="Register"> -->
</form>
</div>

<!-- reset all session error variables -->
<?php 
	unset($_SESSION["shopnameErr"], $_SESSION['cityErr'], $_SESSION['priceErr'], $_SESSION['amountErr']);
?>

</div>
</body>
</html>
