<?php 
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	// Establish database connection
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'aniqlo_db';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn -> connect_error){
		die('Connection failed: ' . mysqli_connect_error());
	}
	
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$phone = mysqli_real_escape_string($conn, $_POST['phone']);
	
	$nameErr = $emailErr = $phoneNumErr = $passwordErr= ""; 
	
	// Server-side scripting - additional input validation before append the new data into database
	// Name validation
	if (empty(trim($name))) {  
		$nameErr = "Name is required"; 
	}else if(!preg_match("/^[a-zA-Z ]*$/",$name)){ //only alphabets and white space are allowed
		$nameErr = "Invalid name format"; 
	}
	
	// Phone validation
	if (empty(trim($phone))) {  
		$phoneNumErr = "Phone number is required";  
	} else if(!preg_match ("/^[0-9]{3}\-[0-9]{7,8}$/", $phone)){
		$phoneNumErr = "Invalid phone format";
	}
	
	// Password validation
	if (empty(trim($password))) {
		$passwordErr = "Password is required";
	}else if(!preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9\-_@.]{8,}$/", $password)){
		$passwordErr = "Invalid password format";
	}
	
	// Email validation
	if (empty(trim($email))) { 
		$emailErr = "Email is required";  
	}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$emailErr = "Invalid email format";  
	}else{
		// Check if the email already exists in the database
		$sql_check_email = "SELECT * FROM users WHERE email = '$email'";
		$result_check_email = $conn->query($sql_check_email);
		if ($result_check_email->num_rows > 0) {
			$emailErr = "This email address is already registered";
		}
	}

	if($nameErr == "" && $emailErr == "" && $phoneNumErr == "" && $passwordErr == ""){
		$sql = "INSERT INTO users (email, password, name, phone_num) VALUES ('$email', '$password', '$name', '$phone')";
		if ($conn->query($sql) === TRUE) {
			$last_id = $conn->insert_id;
			$sql2 = "UPDATE users SET cart_id = user_id WHERE user_id = '$last_id'";
			mysqli_query($conn, $sql2);
			// Display a JavaScript alert for successful registration
			echo '<script>alert("Registration successful! Please proceed to login page");</script>';
		}else{
			echo 'Error creating account: ' . mysqli_error($conn);
		} 
	}
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Aniqlo | Sign Up</title>
	<link rel = "stylesheet" href = "../../mystyle/registerStyle.css">
	<link rel="stylesheet" href="../../mystyle/style.css">

</head>

<body>
	<?php include('../../includes/header.php'); ?>
	<?php include('../../includes/navigation.php'); ?>
	<div class = "wrapper">
	<div class = "register-container">
	<h1>SIGN UP</h1>
	<!-- Error message from server -->
	<?php 
	if(!empty($emailErr)): 
		echo "<div class='error-message'><p>" . $emailErr . "<p></div>";
	endif;
	if(!empty($nameErr)):
		echo "<div class='error-message'><p>" . $nameErr . "<p></div>";
	endif;
	if(!empty($phoneNumErr)):
		echo "<div class='error-message'><p>" . $phoneNumErr . "<p></div>";
	endif;
	if(!empty($passwordErr)):
		echo "<div class='error-message'><p>" . $passwordErr . "<p></div>";
	endif;
	?>
	
	<form id = "registerForm" method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<!-- Email field -->
		<div id ="emailInput">
		<label for = "email">EMAIL ADDRESS</label>
		<input id = "email" type = "email" name = "email" placeholder = "Enter email" required>
		<span class="error"></span>
		</div>
		<br>
		<!-- Name field -->
		<div id="nameInput">
		<label for = "name">NAME</label>
		<input id = "name" type = "text" name = "name" placeholder = "Enter name" required>
		<span class="error"></span>
		</div>
		<br>
		<!-- Phone field -->
		<div id="phoneInput">
		<label for = "phone">PHONE</label>
		<input id = "phone" type = "text" name = "phone" placeholder = "Eg: 012-3456789" required>
		<span class="error"></span>
		</div>
		<br>
		<!-- Password field -->
		<div id="passwordInput">
		<label for = "password">PASSWORD</label>
		<input id = "password" type = "password" name = "password" placeholder = "Enter password" required>
		<span class="error"></span>
		</div>
		<br>
		<div class = "clear"></div>
		
		<!-- Sign Up button -->
		<input type = "submit" value = "SIGN UP">
	</form>
	
	<div class = "clear"></div>
	<br>
	Already have an account? <a href = "/aniqlo/account/login">Login</a>
	</div>
	</div>
<?php include('../../includes/footer.php');?>
<script src="registerValidation.js"></script>
</body>
</html>