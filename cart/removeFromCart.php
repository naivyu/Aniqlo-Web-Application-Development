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
  echo "remove from cart handled";
  $cart_details_id = $_POST["cart_details_id"];
  $cart_id = $_POST["cart_id"];
  $removeFromCartDetails = "DELETE FROM cart_details WHERE cart_details_id = $cart_details_id";

  if (mysqli_query($conn, $removeFromCartDetails)) {
    // Redirect user back to the page
    header("Location: /aniqlo/cart/index.php");
    exit(); // Ensure script execution stops after redirection
  } 
  else {
    echo "Error updating cart details: " . mysqli_error($conn);
  }
}
?>