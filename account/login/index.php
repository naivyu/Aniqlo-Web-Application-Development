<?php 
//Start the session
session_start();

// Check if the user has successfully logged in
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])){
	header('Location: /aniqlo/account');
	exit();
}

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$error = '';
	$email = $_POST['email'];
	$user_password = $_POST['password'];
	
	// Establish database connection
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'aniqlo_db';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn -> connect_error){
		die('Connection failed: ' . mysqli_connect_error());
	}
	
	$sql = "SELECT user_id, email, name FROM users WHERE email = '$email' AND password = '$user_password'";
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		$rowcount = mysqli_num_rows($result);
		// if result matches exactly 1 record (1 user)
		if ($rowcount === 1) {  
		  $row = mysqli_fetch_assoc($result);
		  // Login successful, store the email and user id in the session
		  $_SESSION['email'] = $email;
		  $_SESSION['user_id'] = $row['user_id'];
		  
		  mysqli_free_result($result);
			
		  //redirect to account page
		  header('Location: /aniqlo/account');
		  exit(); // Ensure script execution stops after redirection
		} 
		else {
			$error = 'Invalid email or password'; 
		}
	}
	//Close the database Connection
	mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Aniqlo | Login</title>
	<link rel="stylesheet" href="../../mystyle/loginStyle.css">
	<link rel="stylesheet" href="../../mystyle/style.css">
</head>

<body>
	<?php include('../../includes/header.php'); ?>
	<?php include('../../includes/navigation.php'); ?>
	<div class="wrapper">
	<div class="login-container">
	<h1>LOGIN</h1>
	
	<?php if (isset($error)): ?>
		<p><?php echo "<div class='error-message'><b> . $error . </b></div>"; ?></p>
	<?php endif; ?>
	
	<p>Login with your email address and password.</p>
	<form id = "loginForm" method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<!-- Email field -->
		<div class="emailInput, form-group">
		<label for = "email">EMAIL ADDRESS</label><br>
		<input id = "email" type = "email" name = "email" placeholder = "Enter your email" required>
		<span class="error"></span>
		</div>
		
		<!-- Password field -->
		<div class="passwordInput, form-group">
		<label for = "password">PASSWORD</label><br>
		<input id = "password" type = "password" name = "password" placeholder = "Enter your password" required>
		<span class="error"></span>
		</div>
		
		<!-- Login button -->
		<div class="form-group">
		<input type = "submit" value = "LOGIN">
		</div>
	</form>
	Don't have an account? <a href = "/aniqlo/account/register">Sign Up</a>
	</div>
	</div>
	<?php include('../../includes/footer.php');?>

	<script src = "loginValidation.js"></script>
</body>
</html>