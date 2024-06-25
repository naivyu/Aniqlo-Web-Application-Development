<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aniqlo_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categoryFilter = "";


if (isset($_GET['category'])){
	$category = $_GET['category'];
	$categoryFilter = " WHERE p.prod_category = '" . $category . "'";
}


// Query the database to fetch all the products
$sql = "SELECT * FROM products p 
		LEFT JOIN product_image pi
		ON pi.prod_id = p.prod_id" . $categoryFilter;

$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Fetch data and store in $items array
    while ($row = $result->fetch_assoc()) {
		echo '<div class="item">';
        echo '<img src = "../product_images/' . $row["img_url"] . '" alt = "' . $row["prod_name"] . '">';
		echo '<div class = "item-info">';
		echo '<h2>' . $row["prod_name"] . '</h2>';
		echo '<p class = "item-price">RM' . $row["prod_price"] . '</p>';
		echo '<a href="productDetails.php?prod_id=' . $row["prod_id"] . '"><button>View Option</button></a>';
		echo '</div></div>';
    }
} else {
    echo '<p class="error-message">No products found</p>';
}

// Close the database connection
$conn->close();
?>