<?php 
//Start the session
session_start();

//Check if the user is not logged in
if (!isset($_SESSION['email'])||!isset($_SESSION['user_id'])){
	header('Location: /aniqlo/account/login');
	exit();
}

$user_id = $_SESSION['user_id'];

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aniqlo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $phone_num = $bday = $gender = "";
$nameErr = $phone_numErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST['name'];
	$phone_num = $_POST['phone_num'];
	$bday = $_POST['bday'];
	$gender = isset($_POST['gender']) ? $_POST['gender'] : null;
	
	// Server-side scripting 
	// extra validation for data received 
    if (!empty($name)) {  
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {  
            $nameErr = "Invalid name format";  
        }  
    }
    if (!empty($phone_num)) {  
        if (!preg_match("/^[0-9]{3}\-[0-9]{7,8}$/", $phone_num)) {  
             $phone_numErr = "Invalid phone number format";  
        }
    }
	
	// Prepare SQL query
	if (!empty($name) && $nameErr == "" || !empty($phone_num) && $phone_numErr == "" || !empty($bday) || !empty($gender)){
		$sql = "UPDATE users SET ";
		
		if (!empty($name)){
			$sql .= "name = '$name', ";
		}
		
		if (!empty($phone_num)){
			$sql .= "phone_num = '$phone_num', ";
		}
		
		if (!empty($bday)){
			$sql .= "bday = '$bday', ";
		}
		
		if (!empty($gender)){
			$sql .= "gender = '$gender', ";
		}
		
		$sql = rtrim($sql, ", "); //trim the comma
		$sql .= "WHERE user_id = '$user_id'";
		$result = mysqli_query($conn, $sql);
	}
}
	
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Aniqlo | Edit Profile</title>
    <link rel="stylesheet" href="../../mystyle/editProfileStyle.css">
	<link rel="stylesheet" href="../../mystyle/style.css">
</head>
<body>
<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navigation.php'); ?>
<div class="wrapper">
    <div class="sidebar">
        <?php include('../profileTools.php'); ?>
    </div>
	
    <div class="profile-container">
        <h1>EDIT PROFILE</h1>
        <?php 
		// Display form submission message or error
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editProfile'])) {
			if (empty($name) && empty($phone_num) && empty($bday) && empty($gender)) {
				echo "<div class='no-update-message'><b>No fields to update.</b></div>";    
			} elseif ($nameErr !== "" || $phone_numErr !== "") {
				// Display error messages
				echo "<div class='error-message'><b>The form was not filled up correctly.</b></div>";
			} elseif ($nameErr == "" && $phone_numErr == "") {
				echo "<div class='success-message'><b>Profile updated successfully!</b></div>";
			}
		}
		?><br>	
		
        <form id = "editProfileForm" method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="nameInput, form-group">
                <label for="name"><b>NAME</b></label>
                <input type="text" id="name" name="name" placeholder="Eg. John Steve">
                <span class="error"></span>
            </div>

            <div class="phoneInput, form-group">
                <label for="phone_num"><b>PHONE NUMBER</b></label>
                <input type="tel" id="phone_num" name="phone_num" placeholder="Eg: 012-3456789">
                <span class="error"></span>
            </div>

            <div class="form-group">
                <label for="bday"><b>BIRTHDAY</b></label>
                <input type="date" id="bday" name="bday">
            </div>
            
            <div class="form-group">
                <label for="gender"><b>GENDER</b></label>
                <select id="gender" name="gender">
                    <option value="" disabled selected>Select gender</option> 
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Prefer not to say">Prefer not to say</option>
                </select>
            </div>
            
            <div class="form-group">
                <input type="submit" name="editProfile" class="button" value="SAVE CHANGES">
            </div>
        </form>
    </div>
</div>
<?php include('../../includes/footer.php');?>
<script src = "editProfileValidation"></script>
</body>
</html>