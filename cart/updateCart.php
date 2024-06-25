<?php
// Database Connection Details
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'aniqlo_db';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
  echo "not connected";
}
else {
  echo "connected";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
  echo "updateCart form handled";
  $new_qty = $_POST["productQuantity"];
  $cart_details_id = $_POST["cart_details_id"];
  $updateCart = "UPDATE cart_details SET qty = $new_qty WHERE cart_details_id = $cart_details_id";

  if (mysqli_query($conn, $updateCart)) {
    // Redirect user back to the page
    header("Location: /aniqlo/cart/index.php");
    exit(); // Ensure script execution stops after redirection
  } 
  else {
    echo "Error updating cart details: " . mysqli_error($conn);
  }
}
?>