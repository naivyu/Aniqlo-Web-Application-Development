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

// Fetch user details
$sql = "SELECT name, email, phone_num, bday, gender FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Aniqlo | Profile</title>
    <link rel="stylesheet" href="../myStyle/profileStyle.css">
	<link rel="stylesheet" href="../myStyle/style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<?php include('../includes/navigation.php'); ?>
<div class = "wrapper">
	<div class = "sidebar">
	<?php include('profileTools.php'); ?>
	</div> 
	
	<div class="profile-container">
		<div class="profile-info-left">
			<h1>MY PROFILE</h1>
			<h2> Profile Details </h2>
			<h3>Email</h3>
			<p><?php echo $user['email']; ?></p>
			<h3>Name</h3>
			<p><?php echo $user['name']; ?></p>
			<h3>Phone Number</h3>
			<p><?php echo $user['phone_num']; ?></p>
		</div>
    
		<div class="profile-info-right">
			<h3>Birthday</h3>
			<p><?php echo $user['bday'] ? $user['bday'] : "Not set"; ?></p>
			<h3>Gender</h3>
			<p><?php echo $user['gender'] ? $user['gender'] : "Not set"; ?></p>
		</div>
	</div>
</div>
<?php include('../includes/footer.php');?>
</body>
</html>