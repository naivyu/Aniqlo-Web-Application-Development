<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: /aniqlo/account/login");
    exit;
}

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

// Query to fetch order details
$sql = "SELECT order_id, order_date, total, status 
        FROM orders 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['user_id']);

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result_orders = $stmt->get_result();

// Query to fetch order count
$sql_count = "SELECT COUNT(order_id) as order_count
              FROM orders
              WHERE user_id = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("s", $_SESSION['user_id']);

if (!$stmt_count->execute()) {
    die("Error executing statement: " . $stmt_count->error);
}
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$order_count = $row_count['order_count'];

echo '<table class="orderHistoryListTable">
        <tr>
            <th>ORDER ID</th>
            <th>ORDER DATE</th>
            <th>ORDER AMOUNT</th>
            <th>ORDER STATUS</th>
            <th></th>
        </tr>';

if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='orderId'>" . $row["order_id"] . "</td>";
        echo "<td class='orderDate'>" . $row["order_date"] . "</td>";
        echo "<td class='orderAmount'>RM " . $row["total"] . "</td>";
        echo "<td class='orderStatus'>" . $row["status"] . "</td>";
        echo '<td><button class="viewOrderDetailsButton" data-order-id="' . $row["order_id"] . '">View Order Details</button></td>';
        echo "</tr>";

        // Hidden row for order details dropdown
        echo '<tr class="orderDetailsRow" id="orderDetails_' . $row["order_id"] . '" style="display:none;">';
        echo '<td colspan="5">';
        echo '<div class="orderDetailsContent">';
        echo '<strong>Order Details:</strong><br>';
        echo '<table class="orderDetailsTable">';
        echo '<tr>';
        echo '<th>Size</th>';
        echo '<th>Color</th>';
        echo '<th>Qty</th>';
        echo '<th>Unit Price</th>';
        echo '</tr>';
    
        // Fetch order details for this order
        $sql_details = "SELECT size, color, qty, unit_price
                        FROM order_details
                        INNER JOIN product_variants ON order_details.variant_id = product_variants.variant_id
                        WHERE order_details.order_id = ?";
        $stmt_details = $conn->prepare($sql_details);
        $stmt_details->bind_param("i", $row["order_id"]);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();
    
        while ($detail = $result_details->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $detail["size"] . '</td>';
            echo '<td>' . $detail["color"] . '</td>';
            echo '<td>' . $detail["qty"] . '</td>';
            echo '<td>RM ' . $detail["unit_price"] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        echo '<tr><td colspan="5"><hr class="horizontalLine"></td></tr>';
    }
} else {
    echo "<tr><td colspan='5'>0 results</td></tr>";
}

$stmt->close();
$stmt_count->close();
$conn->close();

?>