<!DOCTYPE html>
<html lang="en">
<style>
.error {
  color: #FF0001;
  width: 150px; /* Fixed width for error messages */
  font-size:16px;
}
</style>
<link rel="stylesheet" href="../mystyle/style.css">
<link rel="stylesheet" href="../mystyle/cartStyles.css">

<?php

session_start();

if (!isset($_SESSION['email']) && !isset($_SESSION['user_id'])){
  header('Location: /aniqlo/account');
  exit();
}

$userEmail = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

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
}

// Fetch user email and cart id
$sql = "SELECT cart_id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail); 
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$cart_id = $user['cart_id'];

if ($user !== null) {

	$cart_sql = "SELECT DISTINCT cd.*, p.prod_name, p.prod_price, 
        p.prod_category, pv.size, pv.color, pv.stock, pi.img_url
		FROM cart_details cd
		INNER JOIN products p ON cd.prod_id = p.prod_id
		INNER JOIN product_variants pv ON cd.variant_id = pv.variant_id
		INNER JOIN product_image pi ON pv.img_id = pi.img_id
		WHERE cd.cart_id = $cart_id";
		
	$cartRecords = mysqli_query($conn, $cart_sql);
} 

// makePayment form is submitted, Add new Address and order record into database
// makePayment form is submitted, Add new Address and order record into database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //retrieve cart details info
  $cart_sql = "SELECT cd.prod_id, cd.variant_id, cd.qty, p.prod_price, pv.size, pv.color, pv.stock 
               FROM cart_details cd
               INNER JOIN products p ON cd.prod_id = p.prod_id
               INNER JOIN product_variants pv ON cd.variant_id = pv.variant_id
               WHERE cd.cart_id = ?";
  
  $stmt = $conn->prepare($cart_sql);
  $stmt->bind_param("i", $cart_id); 
  $stmt->execute();
  $cart_result = $stmt->get_result();

  if ($cart_result->num_rows > 0) {
    //fetch form input (delivery address, payment info)
    $name = $_POST["name"];
    $phoneNum = $_POST["phoneNum"];
    $streetAddress = $_POST["streetAddress"];
    $unitNoAddress = $_POST["unitNoAddress"];  
    $city = $_POST["city"];  
    $postalCode = $_POST["postalCode"];  
    $state = $_POST["state"];  
    $paymentRefNo = $_POST["paymentRefNo"];  

    //insert address into address table
    $sql_address = "INSERT INTO address (street_address, unit_no_address, city, postal_code, state) 
                    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_address);
    $stmt->bind_param("sssss", $streetAddress, $unitNoAddress, $city, $postalCode, $state);

    if ($stmt->execute()) {
      //Retrieve the auto-generated primary key (address_id)
      $address_id = $conn->insert_id; // the last auto-generated ID
    
      // Compute total
      $total = 0;
      while ($row = $cart_result->fetch_assoc()) {
        $total += ($row['prod_price'] * $row['qty']); 
      }   
      $total += 8; //delivery fees is RM8
      
      // Insert data into the orders table using the retrieved address_id
      $sql_order = "INSERT INTO orders (user_id, address_id, total, payment_ref_no, status) 
                    VALUES (?, ?, ?, ?, 'PENDING')";

      $stmt = $conn->prepare($sql_order);
      $stmt->bind_param("iiis", $user_id, $address_id, $total, $paymentRefNo);

      if ($stmt->execute()) {
        // Get the ID of the last inserted order
        $last_order_id = $conn->insert_id;

        // Store cart details in an array
        $cart_details = [];
        $cart_result->data_seek(0); // Reset result set pointer
        while ($row = $cart_result->fetch_assoc()) {
          $cart_details[] = $row;
        }

        // Insert order details for each item in the cart
        foreach ($cart_details as $cart_item) {
          $sql_order_details = "INSERT INTO order_details (order_id, variant_id, qty, unit_price) 
                                VALUES (?, ?, ?, ?)";
          
          $stmt = $conn->prepare($sql_order_details);
          $stmt->bind_param("iiid", $last_order_id, $cart_item['variant_id'], 
                            $cart_item['qty'], $cart_item['prod_price']);

          if (!$stmt->execute()) {
            echo "Error inserting order details: " . $stmt->error;
          }

          // Update stock level (reduce quantity ordered from stock)
          $updated_stock = $cart_item['stock'] - $cart_item['qty'];
          $update_stock_sql = "UPDATE product_variants SET stock = ? WHERE variant_id = ?";
          $stmt = $conn->prepare($update_stock_sql);
          $stmt->bind_param("ii", $updated_stock, $cart_item['variant_id']);
          $stmt->execute();
        }

        // Delete cart details after successful order placement
        $delete_cart_details_sql = "DELETE FROM cart_details WHERE cart_id = ?";
        $stmt = $conn->prepare($delete_cart_details_sql);
        $stmt->bind_param("i", $cart_id);
        if ($stmt->execute()) {
          echo '<script>alert("Order placed successfully!")</script>';
          header("Location: /aniqlo/cart/index.php");
          exit(); // Ensure no further code execution after redirection
        } else {
          echo "Error deleting cart details: " . $stmt->error;
        }
      } else {
        echo "Error inserting order: " . $stmt->error;
      }
    } else {
      echo "Error inserting address: " . $stmt->error;
    }
  } else {
    echo "<script> alert('Your cart is empty, please add items into the cart') </script>";
  }
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="cart.css" />
  <title>CART|ANIQLO</title>
</head>
<?php include('../includes/header.php') ?>; 
<?php include('../includes/navigation.php')?>;
<script> 
  const form = document.getElementById("paymentForm");
  // Fetch all form input values
  const name = document.getElementById("name").value;
  const phoneNum = document.getElementById("phoneNum").value;
  const streetAddress = document.getElementById("streetAddress").value;
  const city = document.getElementById("city").value;
  const postalCode = document.getElementById("postalCode").value;
  const state = document.getElementById("state").value;
  const paymentRefNo = document.getElementById("paymentRefNo");

  // Fetch error elements
  const nameErr = document.getElementById("nameErr");
  const phoneNumErr = document.getElementById("phoneNumErr");
  const streetAddressErr = document.getElementById("streetAddressErr");
  const cityErr = document.getElementById("cityErr");
  const postalCodeErr = document.getElementById("postalCodeErr");
  const stateErr = document.getElementById("stateErr");
  const paymentRefNoErr = document.getElementById("paymentRefNoErr");

  // Event listener to validate the payment form
  form.addEventListener("click",(makePaymentEvent) => { 
    // Validation rules
    if (name.trim() == "") {
        nameErr.innerHTML = "*<br>Receiver name is required.";
        return false;
    }
    else {
      for (const i = 0; i < name.value.length; i++) {
        const c = name.charAt(i);
        if (!((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z') || (c >= '0' && c <= '9') || c === ' ')) {
            nameErr.innerHTML = "Only alphabets, digits, and white space are allowed.";
        }
      }
    }
    if (phoneNum.trim() == "") {
        phoneNumErr.innerHTML = "*<br>Phone number is required.";
    } 
    else if (!/^\d{10,11}$/.test(phoneNum)) {
        phoneNumErr.innerHTML = "Phone number must be 10 or 11 digits.";
    }

    if (streetAddress.trim() == "") {
        streetAddressErr.innerHTML = "Street address is required.";
    }

    if (city.trim() == "") {
        cityErr.innerHTML = "City is required.";
    }

    if (postalCode.trim() == "") {
        postalCodeErr.innerHTML = "Postal code is required.";
    } 
    else if (!/^\d{5}$/.test(postalCode)) {
        postalCodeErr.innerHTML = "Postal code must be 5 digits.";
    }

    if (state.trim() == "") {
        stateErr.innerHTML = "State is required.";
    }

    if (paymentRefNo.trim() == "") {
        paymentRefNoErr.innerHTML = "Payment reference number is required.";
    }
    
    if (nameErr.innerHTML !== "" || phoneNumErr.innerHTML !== "" || streetAddressErr.innerHTML !== "" || cityErr.innerHTML !== "" || postalCodeErr.innerHTML !== "" || stateErr.innerHTML !== "" || paymentRefNoErr.innerHTML !== "") {
      return;
    }
    else {
      makePaymentEvent.preventDefault();
    }      
});
</script>


<body>
<div class="headerWrap">
    <h1 id="cartHeader">SHOPPING CART</h1>
  </div>
  <div class="mainWrap">
    <div class="cartWrap">
      <?php

      $total = 0;

      while ($row = mysqli_fetch_array($cartRecords)) { 
        // Begin productWrap
        echo "<div class='productWrap'>";
          // Product image
          echo "<div class='imageWrap'>";
            echo '<img src="/aniqlo/product_images/' . $row['img_url'] . '" width="200px" alt="Image of product could not be displayed">';
          echo "</div>";
          // Product information
          echo "<div class='productInfoWrap'>";
            echo "<p class='productDescription'>" . $row['prod_name'] . "</p>";
            echo "<div class='productIdWrap'>";
              echo "<span class='productId-def-term'>Product ID:</span>";
              echo "<span class='productId-def-desc'>" . $row['prod_id'] . "</span>";
            echo "</div>";

            // Product size
            echo "<br>";
            echo "<div class='productSizeWrap'>";
              echo "<span class='productSize-def-term'>Size:</span>";
              echo "<span class='productSize-def-desc'>" . $row['prod_category'] . " " . $row['size'] . "</span>";
            echo "</div>";

            // Product price
            echo "<br>";
            echo "<br>";

            echo "<div class='productPriceWrap'>";
              echo "<span class='productPrice'>RM" . number_format($row['prod_price'],2) . "</span>";
            echo "</div>";
          echo "</div>"; // End productInfoWrap

          // Subtotal and quantity
          echo "<div class='subtotalQtyWrap'>";
            echo "<br>";
            echo "<form method='post' action='removeFromCart.php'>";
              echo "<input type='hidden' name='cart_details_id' value='{$row['cart_details_id']}'>";
              echo "<input type='hidden' name='cart_id' value='{$row['cart_id']}'>";
              echo "<button class='removeButton' name ='removeButton' type='submit'>REMOVE</button>";
            echo "</form>";
            echo "<br>";
            echo "<div class='quantityWrap'>";
              echo "<div class='quantity'>QUANTITY</div>";
	      echo "<div class='qty' style='margin-right:10px;'>".$row['qty']."</div>";
              echo "<div>";
             	echo "<form method='post' action='updateCart.php'>";
             	echo "<select class='selectQuantity' id='productQuantity' name='productQuantity' style='padding:20px;'>";
                  echo "<option value=1>1</option>";
                  echo "<option value=2>2</option>";
                  echo "<option value=3>3</option>";
                  echo "<option value=4>4</option>";
                  echo "<option value=5>5</option>";
                echo "</select>";
                echo "<input type='hidden' name='cart_details_id' value='{$row['cart_details_id']}'>";
		echo "<br>";
       	      echo "</div>"; //close form div
              echo "<div>";
           	echo "<button class='updateCartBtn' name='updateCartBtn' type='submit'>UPDATE CART</button>";
              echo "</div>";//close button div
	      echo "</form>";
             echo "</div>";

            // Subtotal calculation
            echo "<br>";
            echo "<br>";
            echo "<div class='subTotalWrap'>";
              echo "<span class='subTotal-def-term'>SUBTOTAL:</span>";
              $subtotal = $row['qty'] * $row['prod_price'];
              $total += $subtotal; // Add subtotal to total
              echo "<span class='subTotal-def-desc'>RM" . number_format($row['qty'] * $row['prod_price'], 2) . "</span>";
            echo "</div>";
          echo "</div>"; // End subtotalQtyWrap
        echo "</div>"; // End productWrap
        echo "<hr class='horizontalLine'>";
      } 
      ?>
    </div> <!-- End cartWrap -->

    <!-- Order summary section -->
    <div class="straight-line"></div>
    <div class="orderSummaryWrap">
      <div class="orderSummaryHeader">
        <h2 id="orderSummaryHeader">ORDER SUMMARY</h2>
      </div>
      <hr class="horizontalLine">
      <div class="orderSummaryBody">
        <div class="subTotalSummaryWrap">
          <table id="orderSummaryTable">
            <tr>
              <td><span class="subTotalSummary-def-term">GRAND TOTAL</span></td>
              <td width="15px"></td>
              <td><span class="subTotalSummary-def-desc"><?php echo "RM " . number_format($total, 2); ?></span></td>
            </tr>
            <tr>
              <td><span class="subTotalSummary-def-term">DELIVERY FEES</span></td>
              <td width="15px"></td>
              <td><span class="subTotalSummary-def-desc">RM 8.00</span></td>
            </tr>
          </table>
        </div>
        <br>
        <hr class="horizontalLine">

        <div class="totalSummaryWrap">
          <span class="totalSummary-def-term" style = "font-size:26px; color = #FF2929; margin-right:30px;" c>TOTAL</span>
          <span class="totalSummary-def-desc" style = "font-size:26px; color = #FF2929;"><?php echo "RM" . number_format($total+8, 2); ?></span>
        </div>
        <hr class="horizontalLine">
        <button id="checkOutButton">CHECK OUT</button>
        <button id="continueShoppingButton" onclick="window.location.href='/aniqlo/products/index.php?category=All'">CONTINUE SHOPPING</button>
      </div>
    </div>
  </div> <!-- End mainWrap --><div class="footerWrap">
  <?php
    include('../includes/footer.php'); 
  ?>
</div>
<div id="overlay"></div>
<div class="checkOutPopUp">
<h1 id="deliveryDetailsHeader">DELIVERY DETAILS </h1>
<form id="paymentForm" name="paymentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
<table id="deliveryAddressForm">  
<tr>
  <td>Name</td>
  <td> 
    <input type="text" name="name" id="name"> 	
    <span class="error" id="nameErr">*<br></span>  
  </td>
</tr>
<tr>
  <td>Phone No</td>
  <td>
    <input type="text" name="phoneNum" id="phoneNum">
    <span class="error" id="phoneNumErr">* <br></span>  
  </td>
</tr>
<tr>
  <td>Street Address</td>
  <td>
    <input type="text" name="streetAddress" id="streetAddress">
    <span class="error" id="streetAddressErr">* <br></span>  
  </td>
</tr>
<tr> 
  <td>Unit No./Floor/Apt/Suite</td>
  <td>
    <input type="text" name="unitNoAddress" >
  </td>
</tr>
<tr>
  <td>City</td>
  <td>   
    <input type="text" name="city" id="city">
    <span class="error" id="cityErr">* <br></span>  
  </td>
</tr>
<tr>
  <td>Postal Code </td>
  <td>  
    <input type="text" name="postalCode" id="postalCode">
    <span class="error" id="postalCodeErr">* <br> </span>  
  </td>
</tr>
<tr>
  <td>State</td>
  <td>
    <select id="state" name="state" id="state">
      <option value="Johor">           Johor</option>
      <option value="Kedah">           Kedah</option>
      <option value="Kelantan">        Kelantan</option>
      <option value="Kuala Lumpur">    Kuala Lumpur</option>
      <option value="Melaka">          Melaka</option> 
      <option value="Negeri Sembilan"> Negeri Sembilan</option>
      <option value="Pahang">          Pahang</option>
      <option value="Perak">           Perak</option>
      <option value="Perlis">          Perlis</option>
      <option value="Pulau Pinang">    Pulau Pinang</option>
      <option value="Sabah">           Sabah</option>
      <option value="Sarawak">         Sarawak</option>
      <option value="Selangor">        Selangor</option>
      <option value="Terengganu">      Terengganu</option>
    </select>
    <span class="error" id="stateErr">* <br> </span>  
  </td>
</tr>
<tr>
  <td>Payment Reference No </td>
  <td>
    <input type="text" name="paymentRefNo" id="paymentRefNo">
    <span class="error" id="paymentRefNoErr">* <br></span>
  </td>  
</tr>
</table>
<div id="buttonSection">
    <br>                            
    <input id="makePaymentBtn" type="submit" name="makePaymentBtn" value="MAKE PAYMENT"> 
    <input type="hidden" name="cart_id" value="<?php echo $user['cart_id']; ?>">
    <button id="closeButton" type="button" name="close" value="close">CLOSE </button>
    <br><br>   
</div> 
</form>
</div>

<script type="text/javascript">
// Get references to the button and popup
const checkOutButton = document.getElementById('checkOutButton');
const closeBtn = document.getElementById('closeButton');
const checkOutPopUp = document.querySelector('.checkOutPopUp'); // Selecting by class name

// Function to open the popup
function openPopup() {
    checkOutPopUp.style.display = 'block';
    overlay.style.display = 'block'; // Show overlay
}

// Function to close the popup
function closePopup() {
    checkOutPopUp.style.display = 'none';
    overlay.style.display = 'none'; // Hide overlay
}

// Event listener to open the popup when the button is clicked
checkOutButton.addEventListener('click', openPopup);

// Event listener to close the popup when the close button is clicked
closeBtn.addEventListener('click', closePopup);


//form validation
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("paymentForm");

  form.addEventListener('submit', e => {

    e.preventDefault();
    validateInputs(e);
  });

  const validateInputs = (e) => { 
    // Fetch all form input values
    const name = document.getElementById("name").value.trim();
    const phoneNum = document.getElementById("phoneNum").value.trim();
    const streetAddress = document.getElementById("streetAddress").value.trim();
    const city = document.getElementById("city").value.trim();
    const postalCode = document.getElementById("postalCode").value.trim();
    const paymentRefNo = document.getElementById("paymentRefNo").value.trim();

    var nameErr = document.getElementById("nameErr");
    var phoneNumErr = document.getElementById("phoneNumErr");
    var streetAddressErr = document.getElementById("streetAddressErr");
    var cityErr = document.getElementById("cityErr");
    var postalCodeErr = document.getElementById("postalCodeErr");
    var paymentRefNoErr = document.getElementById("paymentRefNoErr");

    // Clear previous error messages
    nameErr.innerHTML = "";
    phoneNumErr.innerHTML = "";
    streetAddressErr.innerHTML = "";
    cityErr.innerHTML = "";
    postalCodeErr.innerHTML = "";
    paymentRefNoErr.innerHTML = "";

    if (name === "" || name === null) { 
      nameErr.innerHTML = "*<br>Receiver name is required.";
      e.preventDefault();
    }
    else {
      for (let i = 0; i < name.length; i++) { 
        const c = name.charAt(i);
        if (!((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z') || (c >= '0' && c <= '9') || c === ' ')) {
          nameErr.innerHTML = "*<br>Only alphabets, digits, and white space are allowed.";
        }
      }
    }
    if (phoneNum === "") { 
      e.preventDefault();
      phoneNumErr.innerHTML = "*<br>Phone number is required.";
    } 
    else if (!/^\d{10,11}$/.test(phoneNum)) {
      phoneNumErr.innerHTML = "*<br>Phone number must be 10 or 11 digits.";
    }
    if (streetAddress === "") { 
      e.preventDefault();
      streetAddressErr.innerHTML = "*<br>Street address is required.";
    }

    if (city === "") { 
      e.preventDefault();
    cityErr.innerHTML = "*<br>City is required.";
    }
    if (postalCode === "") {
      e.preventDefault();
      postalCodeErr.innerHTML = "*<br>Postal code is required.";
    } 
    else if (!/^\d{5}$/.test(postalCode)) {
      e.preventDefault();
      postalCodeErr.innerHTML = "*<br>Postal code must be 5 digits.";
    }
    if (paymentRefNo === "") { 
      e.preventDefault();
      paymentRefNoErr.innerHTML = "*<br>Payment reference number is required.";
    }
    
    if (nameErr.innerHTML !== "" || phoneNumErr.innerHTML !== "" || streetAddressErr.innerHTML !== "" || cityErr.innerHTML !== "" || postalCodeErr.innerHTML !== "" || paymentRefNoErr.innerHTML !== "") {
      return;
    }
    else {
      form.submit();
    }      
  };
});
</script>
</body>
</html>
