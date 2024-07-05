<!DOCTYPE html>
<html>
<head>
    <title>Aniqlo | Home</title>
    <link rel="stylesheet" href="mystyle/HomeStyle.css">
	<link rel="stylesheet" href="mystyle/style.css">
</head>
<body>

<?php session_start(); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/navigation.php'); ?>

<div class="video-container">
    <video autoplay muted loop id="myVideo">
        <source src="homeImages/HomeBg1.mp4" type="video/mp4">
    </video>
</div>

<div class="content">
    <p>Coming Soon</p>
    <h1>Aniqlo 2024 Spring/Summer Collection</h1>
    <h3>- Ease into lightness -</h3>
</div>

<!-- Slideshow container -->
<div class="slideshow-container">
    <div class="aniqloSlides fade">
        <img src="homeImages/AniqloHome1.jpeg" style="width:100%">
    </div>
    <div class="aniqloSlides fade">
        <img src="homeImages/AniqloHome2.jpeg" style="width:100%">
    </div>
    <div class="aniqloSlides fade">
        <img src="homeImages/AniqloHome3.jpeg" style="width:100%">
    </div>
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

<h1 class="topProd">Top Products</h1>
<div class="top-products-container">
    <?php include('homeTopSales.php'); ?>
</div>

<div class="aniqlo-images">
    <img src="homeImages/aniqloCrop.jpg" alt="Aniqlo Image 1" class="aniqlo-bg-img">
    <div class="main-container">
        <p class="animate-on-scroll"> 
            <span class="aniqlo-text">ANIQLO</span>, where fashion meets comfort and quality. 
            At Aniqlo, we believe that clothing is not just about looking good, but also about feeling good. 
            Our collection is curated with the modern individual in mind, offering a blend of timeless classics 
            and contemporary styles. Whether you're dressing for a casual day out or a special occasion, Aniqlo 
            has something for everyone. 
        </p>
    </div>
</div>

<div class="benefits-container">
    <br>
    <h1>Shop With Us</h1>
    <div class="benefit-item">
        <img src="homeImages/delivery.png" alt="Fast Delivery Icon" class="benefit-icon">
        <h3>Fast Delivery</h3>
        <p>Get it quick, love it sooner.</p>
    </div>
    <div class="benefit-item">
        <img src="homeImages/quality.png" alt="Best Quality Icon" class="benefit-icon">
        <h3>Best Quality</h3>
        <p>Elevate your everyday wear.</p>
    </div>
    <div class="benefit-item">
        <img src="homeImages/style.png" alt="Timeless Styles Icon" class="benefit-icon">
        <h3>Timeless Styles</h3>
        <p>Pieces that remain stylish and relevant year after year.</p>
    </div>
</div>

<div class="categories-color">
    <h1 class="category"> CATEGORY </h1>
    <div class="categories-container">
        <div class="category-box">
            <a href="/aniqlo/products/index.php?category=Men">
                <img class="default-img" src="homeImages/men1.jpg" alt="Men Category">
                <img class="hover-img" src="homeImages/men2.jpg" alt="Men Category Hover" style="display:none;">
                <h2>Men</h2>
            </a>
        </div>
        <div class="category-box">
            <a href="/aniqlo/products/index.php?category=Women">
                <img class="default-img" src="homeImages/women1.jpg" alt="Women Category">
                <img class="hover-img" src="homeImages/women2.jpg" alt="Women Category Hover" style="display:none;">
                <h2>Women</h2>
            </a>
        </div>
        <div class="category-box">
            <a href="/aniqlo/products/index.php?category=Kids">
                <img class="default-img" src="homeImages/kids1.jpg" alt="Kids Category">
                <img class="hover-img" src="homeImages/kids2.jpg" alt="Kids Category Hover" style="display:none;">
                <h2>Kids</h2>
            </a>
        </div>
    </div>
</div>

<script src="home.js"></script>

<?php include('includes/footer.php'); ?> 

</body>
</html>
