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
                <a href="../login/login.php" class="btn-login">Log In</a>
                <a href="../login/signUp.php" class="btn-signup">Sign Up</a>
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
                
                    <img src="../images/im1.jpg" class="hero-slide active" alt="Model 1">
                    <img src="../images/im2.jpg" class="hero-slide" alt="Model 2">
                    <img src="../images/im3.jpg" class="hero-slide" alt="Model 3">
                
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
                    <div class="slide-card"><div class="img-box"><img src="../images/item5.jpg"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item6.jpg"></div></div>
                </div>
            </div>
        </section>
    </main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <h2 class="footer-logo"><i class="fa-solid fa-bag-shopping"></i> SHOPPON</h2>
            <p>Discover unique fashion pieces and hidden gems at unbeatable prices.</p>
        </div>

        <div class="footer-col">
            <h4>Contact Us</h4>
            <p><i class="fa-solid fa-envelope"></i> hello@shoppon.com</p>
            <p><i class="fa-solid fa-phone"></i> +880 1745636346 SHOPPON</p>
            <div class="footer-socials">
                <a href="#" class="icons"><i class="fa-brands fa-facebook"></i></a> 
                <a href="#" class="icons"><i class="fa-brands fa-instagram"></i></a> 
                <a href="#" class="icons"><i class="fa-brands fa-x-snapchat"></i></a> 
            </div>
        </div>

    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2026 SHOPPON. All rights reserved.</p>
    </div>
</footer>

<script src="../js/home.js"></script>
</body>
</html>