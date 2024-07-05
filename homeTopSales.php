<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aniqlo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get top products based on sales with image URL
$sql = "SELECT p.prod_id, p.prod_name, p.prod_desc, p.prod_price, (
    SELECT SUM(od.qty)
    FROM order_details od
    JOIN product_variants pv ON od.variant_id = pv.variant_id
    WHERE pv.prod_id = p.prod_id
    ORDER BY od.qty DESC
    LIMIT 1
) as total_sold, 
(
    SELECT img_url
    FROM product_image pi
    WHERE pi.prod_id = p.prod_id
    ORDER BY img_url DESC
    LIMIT 1
) as img_url
FROM products p
ORDER BY total_sold DESC
LIMIT 3";

$result = $conn->query($sql);

echo '<div class="top-products-container">'; // Start of top products container

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="product-item">';
        // Linking to product details page
        echo '<a href="products/productDetails.php?prod_id=' . $row["prod_id"] . '">';
        echo '<img src="product_images/' . $row["img_url"] . '" alt="' . $row["prod_name"] . '" class="product-image">';
        echo '<h3 class="product-name">' . $row["prod_name"] . '</h3>';
        echo '</a>'; // End of anchor tag
        echo '<p class="product-desc">' . $row["prod_desc"] . '</p>';
        echo '<span class="product-price">$' . $row["prod_price"] . '</span>';
        echo '<p class="total-sold">Total Sold: ' . $row["total_sold"] . '</p>';
        echo '</div>';
    }
} else {
    echo "<p class='no-products'>No top products found.</p>";
}

echo '</div>'; // End of top products container

$conn->close();
?>
