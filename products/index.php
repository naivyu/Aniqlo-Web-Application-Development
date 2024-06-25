<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
	<link rel="stylesheet" href="../mystyle/style.css">
    <style>
.row {
    display: flex; /* Use flexbox layout */
    justify-content: space-between; /* Distribute space evenly between items */
    align-items: stretch; /* Align items vertically */
    flex-wrap: wrap; /* Allow items to wrap onto the next line if needed */
}


.item {
    width: 30%;
	height: 40%;
    box-sizing: border-box;
    background: #fff;
    border-radius: 5px;
	padding: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: inline-block;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 25px 9px 0.3%;
    position: relative;
}

.item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.item img {
    position: middle; 
    top: 0;
    left: 0; 
    width: 100%; 
    height: 100%;
    margin: 20px;
    object-fit: cover; 
    border-radius: 5px; 
	margin: auto;
}  

.item-info {
    padding: 10px;
    text-align: center;
    margin: 5px;
    position: middle;
    border-radius: 5px; 
}

.item h2 {
    margin: 10px 0 5px;
    font-size: 18px;
    color: #333;
}

.item-price {
    font-weight: bold;
    color: #e4002b; /* Uniqlo's signature red color */
}

.item button {
    background-color: #e4002b;
    color: white;
    border: none;
    padding: 20px 30px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
	
}

.item button:hover {
    background-color: #cc002a;
}

    </style>
</head>

<body>
	<?php include('../includes/header.php'); ?>
	<?php include('../includes/navigation.php'); ?>
	
	<select id="category" name="category" onchange="filterProducts(this.value)">
	<option value="" disabled selected>Select category</option>
	<option value="All">All</option>
	<option value="Men">Men</option>
	<option value="Women">Women</option>
	<option value="Kids">Kids</option>
	</select>

	<div class="product" id="product"></div>	
    <script>
        // Function to load products using Ajax
		function filterProducts(category) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("product").innerHTML = this.responseText;
                }
            };
			
			if (category == 'All'){
				xmlhttp.open("GET", "get_products.php", true);
			}else{
				xmlhttp.open("GET", "get_products.php?category=" + category, true);
			}
            
            xmlhttp.send();
        }

        // Load products when the page is loaded
        window.onload = function() {
            // Read the category from the URL
			var urlParams = new URLSearchParams(window.location.search);
			var category = urlParams.get('category');

			// Set the selected category in the dropdown
			var categoryDropdown = document.getElementById("category");
			if (category) {
				categoryDropdown.value = category;
			}

			// Apply the filter for the selected category
			filterProducts(categoryDropdown.value);
			};
			
			document.getElementById("category").addEventListener("change",function(){
				filterProducts(this.value);
		});
    </script>
	<?php include('../includes/footer.php'); ?>
</body>
</html>
