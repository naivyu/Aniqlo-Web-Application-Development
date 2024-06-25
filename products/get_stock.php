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
	
	$selectedSize = mysqli_real_escape_string($conn, $_GET['size']);
    $selectedColor = mysqli_real_escape_string($conn, $_GET['color']);
	
    $sql = "SELECT stock FROM product_variants 
            WHERE size = '$selectedSize' AND color = '$selectedColor'";
		
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$stock = $row['stock'];
	
	echo json_encode(["stock"=>$stock]);
	$conn->close();
?>