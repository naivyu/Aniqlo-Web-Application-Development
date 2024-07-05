<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        // Include header file and start session
        include('../includes/header.php'); 
		include('../includes/navigation.php');
        session_start();
    ?>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../mystyle/AniqloOrderHistoryStyle.css" />
    <link rel="stylesheet" href="../mystyle/AniqloOrderDetailsStyle.css" />
    <link rel="stylesheet" href="../myStyle/style.css">
    <title>ANIQLO | ORDER HISTORY</title>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include('profileTools.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="mainWrap">
            <div class="headerWrap">
                <h1 id="orderHistoryHeader">ORDER HISTORY</h1>
            </div>
            
            <!-- Order History Data -->
            <div class="orderHistoryWrap">
                <?php include('orderHistoryData.php'); ?>
            </div>
        </div>
    </div>
	<?php include('../includes/navigation.php'); ?>
	<!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add jQuery -->

    <script>
    $(document).ready(function(){
        $('.viewOrderDetailsButton').click(function(){
            var orderId = $(this).data('order-id'); // Get order ID from button data attribute
            $('#orderDetails_' + orderId).toggle(); // Toggle visibility of order details
        });
    });
    </script>
</body>
</html>

