<!-- for login -->
<?php session_start(); ?>
<?php echo file_get_contents("header.php"); ?>
<h1>Login</h1>
<form action="login.php" target="_self" method="post">
		<div class="form-group-row">
			<label for="account" class="col-sm-2 col-form-label">Account</label> 
			<div class="col-sm-10">
				<input type="text" name="account" method="post" class="form-control" id="account"> 
			</div>
		</div>
		<div class="form-group-row">
			<label for="password" class="col-sm-2 col-form-label">Password</label> 
			<div class="col-sm-10">
				<input type="password" id="password" name="password" method="post" class="form-control">
			</div>
		</div>

	<br>
	<input type="submit" value="Login" class="btn btn-primary">
</form>
</body>
</html>