<?php 
	session_start();
	if (!isset($_SESSION['email'])||!isset($_SESSION['user_id'])){
		header('Location: /aniqlo/account/login');
		exit();
	}
	$user_id = $_SESSION['user_id'];

	// Establish database connection
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'aniqlo_db';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn -> connect_error){
		die('Connection failed: ' . mysqli_connect_error());
	}
	
	$product_query = "SELECT * FROM products WHERE prod_id = " . $_GET['prod_id'];
	$product_result = $conn->query($product_query);
	$product = $product_result->fetch_assoc();

	$img_query = "SELECT * FROM product_image WHERE prod_id = " . $_GET['prod_id'];
	$img_result = $conn->query($img_query);
	$image = $img_result->fetch_assoc();
	
	$sql = "SELECT color FROM product_variants WHERE prod_id = " . $_GET['prod_id'];
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	if ($_SERVER["REQUEST_METHOD"] === 'POST'){
		
		$query = "SELECT variant_id FROM product_variants WHERE color='" . $_POST['color'] . "'AND size='" . $_POST['size'] . "'";
		$variantId_result = $conn->query($query);
		$variant_row = $variantId_result->fetch_assoc();
		$variant_id = $variant_row['variant_id'];
		$quantity = $_POST['quantity'];
		$cart_query = "SELECT cart_id FROM users WHERE user_id=" . $user_id;
		$cart_result = $conn->query($cart_query);
		$cart_row = $cart_result->fetch_assoc();
		$cart_id = $cart_row['cart_id'];
		$prod_id = $_GET['prod_id'];
		
		$sql = "INSERT into cart_details (cart_id, prod_id, variant_id, qty) VALUES ('$cart_id','$prod_id','$variant_id','$quantity')";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			echo '<script>alert("Added to your cart");</script>';
		}else{
			echo 'Error in adding to cart: ' . mysqli_error($conn);
		}
	}
	
	$conn->close();
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product Details</title>
	<link rel="stylesheet" href="../mystyle/style.css">
	<style>
	body {
		font-family: Arial, sans-serif;
		line-height: 1.6;
		margin: 0;
		padding: 20px;
		background-color: #f4f4f4;
	}

	.product-container {
		max-width: 1200px;
		margin: auto;
		background: #ffffff;
		padding: 20px;
		box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
		display: flex;
		justify-content: space-between;
	}
	
	.image, .desc{
		flex: 1;
	}
	
	.image{
		margin-right: 20px;
	}

	.image img {
		width: 100%;
		max-width: 500px;
		display: block;
		margin-bottom: 20px;
	}

	.small-img-group {
		display: flex;
		flex-wrap: wrap;
	}

	.small-img-col img {
		cursor: pointer;
		margin: 10px;
		width: 80px;
		height: 80px;
	}

	.purchase-group {
		display: block;
		align-items: center;
		margin-bottom: 20px;
	}

	 select, input[type="number"], .buy-btn {
        padding: 10px;
        margin-right: 10px;
    }

	.buy-btn {
		background-color: red;
		color: white;
		border: none;
		padding: 20px 30px;
		cursor: pointer;
		width: 30%;
		font-size: 20px;
		margin-top: 30px;
	}

	.desc h2, .desc h3, .desc h4 {
		margin: 10px 0;
	}
	
	.desc h5{
		color: #808080;
	}
	</style>
</head>
<body>
	<?php include('../includes/header.php'); ?>
	<?php include('../includes/navigation.php'); ?>
	<div class = "product-container">
	<section class="image">
	<div class="row">
	<?php
		echo '<img id="main-image" src="../product_images/' . $image['img_url'] . '" alt="' . $product['prod_name'] . '">';
		
		echo '<div class="small-img-group">';
		do{
			echo '<div class="small-img-col">';
			echo '<img src="../product_images/' . $image['img_url'] . '" alt="' . $product['prod_name'] . '">';
			echo '</div>';
		}while($image=$img_result->fetch_assoc());
		echo '</div>';
	?>
	</div>
	</section>
	
	<section class="desc">
	<h4>All Product / <?php echo $product['prod_category']?></h4>
	<h1><?php echo $product['prod_name']?></h1>
	
	<h3>Product Details</h3>
	<p><?php echo $product['prod_desc']?></p>
	
	<h2>RM <?php echo $product['prod_price']?></h2>
	
	
	<form id="purchaseForm" method="POST" action = "">
	<div class="purchase-group">
	
	<select id="size" name="size">
		<option disabled>Select Size</option>
		<option value="S">S</option>
		<option value="M">M</option>
		<option value="L">L</option>
	</select>
	
	<select id="color" name="color">
		<option disabled>Color</option>
	<?php
	$prev_color = null;
	do{
		if ($prev_color !== $row['color']){
			echo '<option value="' . $row['color'] . '">' . $row['color'] . '</option>' ;
		}
		$prev_color = $row['color'];
	}while($row=$result->fetch_assoc());
	?>
	
	</select>
	<input id="quantity" name="quantity" type="number" value="1" style="width: '30px' height:'20px';">
	<input id="buyButton" type="submit" class="buy-btn" value="Add To Cart">
	</form>
	</section>
	</div>
	
	<script>
    document.querySelectorAll('.small-img-col img').forEach(image => {
        image.addEventListener('click', function () {
            document.getElementById('main-image').src = this.src;
        });
    });
	
	document.addEventListener('DOMContentLoaded', function(){
		var sizeSelect = document.getElementById('size');
		var colorSelect = document.getElementById('color');
		var button = document.getElementById('buyButton');
		
		sizeSelect.addEventListener('change', validateStock);
		colorSelect.addEventListener('change', validateStock);
		
		function validateStock() {
			var selectedSize = sizeSelect.value;
			var selectedColor = colorSelect.value;
			
			var xhr= new XMLHttpRequest();
			xhr.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    var stockResponse = JSON.parse(this.responseText);
					var stockValue = parseInt(stockResponse.stock);
					
					if (stockValue == 0){
						button.disabled=true;
						button.style.backgroundColor = "grey";
						button.value="Out of stock";
					}else{
						button.disabled=false;
						button.value="Add to cart";
						button.style.backgroundColor = "red";
					}
                }
            };
			
            xhr.open("GET", "get_stock.php?size=" + selectedSize + "&color=" + selectedColor, true);
            xhr.send();
		}
	});
	</script>
	<?php include('../includes/footer.php'); ?>
</body>
</html>