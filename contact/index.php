<?php
// Establish database connection
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'aniqlo_db';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn -> connect_error){
	die('Connection failed: ' . mysqli_connect_error());
}

$nameErr = $emailErr = $phone_numErr = $enquiryErr = $subjectErr = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])){
	
	$name = $_POST["name"];
    $email = $_POST["email"];
    $phone_num = $_POST["phone_num"];
    $subject = $_POST["subject"];
    $enquiry = isset($_POST["enquiry"]) ? input_data($_POST["enquiry"]) : "";
	
	// Server-side scripting - additional input validation before append the new data into database
	//Name validation
	if (empty(trim($_POST["name"]))){
		$nameErr = "Please fill in your name.";
	}else{
		$name = input_data($_POST["name"]);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-z ]*$/", $name)){
			$nameErr = "Only alphabets and white space are allowed";
		}
	}
	
	// Email Validation   
    if (empty(trim($_POST["email"]))) {  
        $emailErr = "Email is required";  
    } else {  
        $email = input_data($_POST["email"]);  
        // check that the e-mail address is well-formed  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
            $emailErr = "Invalid email format";  
        }  
    }  
	
	//Phone validation
	if (empty(trim($_POST["phone_num"]))){
		$phone_numErr = "Phone no is required";
	} else{
		$phone_num = input_data($_POST["phone_num"]);
		// check if phone no is well-formed
		if (!preg_match("/^[0-9]*$/", $phone_num)){
			$phone_numErr = "Only numeric value is allowed.";
		}
		//check mobile no length should not be less and greater than 10
		if (strlen($phone_num) != 10){
			$phone_numErr = "Phone number must contain 10 digits.";
		}
	}
	
	// Types of enquiries validation
	if (!isset($_POST["enquiry"])|| empty($_POST["type"])){
		$enquiryErr = "Please choose the type of your enquiry.";
	} else{
		$enquiry = input_data($_POST['enquiry']);
	}
	
	// Subject validation
	if (trim($_POST['subject'] == "")){
		$subjectErr = 'Please fill in your subject.';
	}else{
		$subject = input_data($_POST['subject']);
	}
}

if ($nameErr = "" && $emailErr = "" && $phone_numErr = "" && $enquiryErr = "" && $subjectErr = ""){
	$datetime = date('Y-m-d H:i:s');
	// Prepared statement for inserting into database
	$sql = "INSERT INTO enquiries (name, email, phone_num, type, subject, datetime) VALUES ('$name', '$email', '$phone_num', '$enquiry','$subject','$datetime')";
	$conn->query($sql);
}

function input_data($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}	

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Aniqlo | Contact Us</title>
	<link rel="stylesheet" href="../myStyle/contactStyle.css">
	<link rel="stylesheet" href="../myStyle/style.css">
	<style>
.wrapper {
  display:flex;
  flex-direction:column;
  width:100%;
  height:100%;
}
</style>
</head>

<body>
	<?php include('../includes/header.php'); ?>
	<?php include('../includes/navigation.php'); ?>	
	<div class="wrapper">
	<h1>Contact Us</h1>
	
	<ul>
        <li id="clockDetail" style="list-style-image: url('../mystyle/clock.png');"><b>&ensp;Operation Hours:</b> 8AM - 12AM (Open daily)</li><br>
        <li id="phoneDetail" style="list-style-image: url('../mystyle/phone.png');"><b>&ensp;Phone number:</b> 012-9876543 </li><br>
        <li id="emailDetail" style="list-style-image: url('../mystyle/email.png');"><b>&ensp;Email:</b> <a href="mailto:aniqlocustomerservice@uniqlo.com">aniqlocustomerservice@uniqlo.com</a></li><br>
        <li id="facebookDetail" style="list-style-image: url('../mystyle/facebook.png');"><b>&ensp;Facebook:</b> <a href="https://www.facebook.com/AniqloMalaysia">Aniqlo Malaysia</a></li><br>
        <li id="instagramDetail" style="list-style-image: url('../mystyle/instagram.png');"><b>&ensp;Instagram:</b> <a href="https://www.instagram.com/aniqlo_my/">aniqlo_my</a></li><br>
    </ul>
	
	<p style="text-align:center;">For general enquiries or feedback, please leave a message below and we will get back to you soon.</p>
	<span class="error" id="requiredField">* required field </span>  
	<br><br>
	
	<?php include ('_form.php');?>
	
	<?php  
    // Display form submission message or error
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        if ($nameErr == "" && $emailErr == "" && $phone_numErr == "" && $subjectErr == "" && $enquiryErr == "") {
            // Display a message for successful submission
            echo "<div class='success-message'><b>We have recorded your enquiries.</b></div>";
        } else {
            // Display error messages
            echo "<div class='error-message'><b>The form was not filled up correctly.</b></div>";
        }
    }
	?>
	<?php include('../includes/footer.php'); ?>
	</div> 
	
	<script src="contactValidation.js"></script>
</body>
</html>