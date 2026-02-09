<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>SHOPPON | a Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>SHOPPON</span>
            </div>
            <div class="auth-group">
                <a href="#" class="btn-login">Log In</a>
                <a href="#" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container-hero">
                <div class="hero-content">
                    <h1>Welcome to <span>SHOPPON</span></h1>
                    <p class="hero-subtext">Discover stylish, affordable fashion—from new trends to thrift finds—all in one place.</p>
                    
                    <div class="about-description">
                        <h4 id ="aboutTag">ABOUT US</h4>
                        <p id="about-description">SHOPPON is your go-to marketplace for curated fashion. We connect buyers with sellers offering everything from brand-new collections to unique thrift treasures. Affordable style, always within reach.</p>
                    </div>
                </div>
              
            <div class="hero-image">
                <div class="slideshow-container">
                    <img src="../images/im1.jpg" class="hero-slide active" alt="Model 1">
                    <img src="../images/im2.jpg" class="hero-slide" alt="Model 2">
                    <img src="../images/im3.jpg" class="hero-slide" alt="Model 3">
                </div>
            </div>

            </div>
        </section>

        <section class="trending-section">
            <div class="container">
                <div class="trending-header">
                    <h2>Trending Now</h2>
                    <p>Fresh picks updated daily</p>
                </div>
            </div>
            
            <div class="infinite-slider">
                <div class="slider-track">
                    <div class="slide-card"><div class="img-box"><img src="../images/item1.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item2.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item3.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item4.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item5.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item6.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item1.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item2.jpg"></div></div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <div class="footer-logo">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="footer-socials">
                <span><i class="far fa-envelope"></i> hello@shoppon.com</span>
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>
        <p class="copyright-text">&copy; 2026 SHOPPON. All rights reserved.</p>
    </footer>

<script src="../js/home.js"></script>
</body>
</html>