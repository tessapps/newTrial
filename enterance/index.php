<?php
	include ("../konfig/dbKonnDetails.php");
?>
<!DOCTYPE html>
<html>

<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/main.css">

</head>

<body>
	<div class="container">
		<div class="form-container">
			<h2>Sign In</h2>
			<form>
				<div class="inside-container">
					<div class="fontuser">
						<label><b>Username</b></label>
						<input type="text" placeholder="Enter Username" name="ntUsername" required>
						<i class="fa fa-user fa-lg"></i>
					</div>

					<div class="fontpassword">
						<label><b>Password</b></label>
						<input type="password" placeholder="Enter Password" name="ntPassword" required>
						<i class="fa fa-key fa-lg"></i>
					</div>

					<div class="remember-login-container">
						<div class="remember-me-container">
						  <input type="checkbox" checked="checked">
						  <label>Remember me</label>
						</div>
						<div class="login-button-container">
						  <button type="submit" name="ntLoginButton">Login</button>
						</div>
					</div>

					<div class="register-forgot">
						<a href="register">Register</a> | <a href="forgot-pass">Forgot Password?</a>
					</div>
				</div>
				<div class="divider"></div>
				<div class="google-buttons">
					<button><i class="fa fa-google"></i>Google</button>
                </div>
                <div class="facebook-buttons">
					<button><i class="fa fa-facebook"></i>Facebook</button>
                </div>
                <div class="twitter-buttons">
					<button><i class="fa fa-twitter"></i>Twitter</button>
				</div>
			</form>
		</div>
		<div class="image-container">
			<img src="images/login-main.jpg" alt="Resim">
		</div>
	</div>
</body>

</html>
