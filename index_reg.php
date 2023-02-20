<!-- for register -->
<?php echo file_get_contents("header.php"); ?>

<?php session_start(); ?>
<?php
	if(isset($_SESSION['accountErr'])) $accountErr = $_SESSION['accountErr'];
	else $accountErr = "";
	if(isset($_SESSION['pwdErr'])) $pwdErr = $_SESSION['pwdErr'];
	else $pwdErr = "";
	if(isset($_SESSION['confirmedpwdErr'])) $confirmedpwdErr = $_SESSION['confirmedpwdErr'];
	else $confirmedpwdErr = "";
	if(isset($_SESSION['phoneErr'])) $phoneErr = $_SESSION['phoneErr'];
	else $phoneErr = "";
?>

<h1>Create Account</h1>
<form action="register.php" target="_self" method="post">
	Account <input type="text" name="account" >
	<span class="error">* <?php echo $accountErr;?></span>
  	<br><br>
	Password <input type="password" name="password" >
	<span class="error">* <?php echo $pwdErr;?></span>
  	<br><br>
	Confirm Password <input type="password" name="confirmedpwd">
	<span class="error">* <?php echo $confirmedpwdErr;?></span>
  	<br><br>
	Phone Number <input type="text" name="phone">
	<span class="error">* <?php echo $phoneErr;?></span>
  	<br><br>
	<input type="submit" value="Register">
</form>

<!-- reset all session error variables -->
<?php 
	unset($_SESSION["accountErr"], $_SESSION['pwdErr'], $_SESSION['confirmedpwdErr'], $_SESSION['phoneErr']);
?>
</body>
</html>
<!-- <?php //echo file_get_contents("footer.php"); ?> -->
