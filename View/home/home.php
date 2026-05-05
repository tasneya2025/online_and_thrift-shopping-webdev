<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>SHOPPON | a Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>

    <header class="main-header" id="mainHeader">
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
                    <div class="hero-badge">
                        <i class="fa-solid fa-bolt"></i>
                        New arrivals every day
                    </div>
                    <h1>Your Style,<br>Your <span>SHOPPON</span></h1>
                    <p class="hero-subtext">Discover stylish, affordable fashion — from new trends to thrift finds — all in one curated marketplace.</p>

                    <div class="hero-cta">
                        <a href="../login/signUp.php" class="cta-primary">
                            <i class="fa-solid fa-store"></i> Start Shopping
                        </a>
                        <a href="../login/signUp.php" class="cta-secondary">
                            <i class="fa-solid fa-tag"></i> Sell with us
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">2K+</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Sellers</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">98%</span>
                            <span class="stat-label">Happy Buyers</span>
                        </div>
                    </div>
                </div>

                <div class="hero-image">
                    <img src="../images/im1.jpg" class="hero-slide active" alt="Model 1">
                    <img src="../images/im2.jpg" class="hero-slide" alt="Model 2">
                    <img src="../images/im3.jpg" class="hero-slide" alt="Model 3">
                    <div class="slide-dots">
                        <div class="dot active" data-index="0"></div>
                        <div class="dot" data-index="1"></div>
                        <div class="dot" data-index="2"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features-strip">
            <div class="container">
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon blue"><i class="fa-solid fa-shield-halved"></i></div>
                        <h3>Verified Sellers</h3>
                        <p>Every seller on SHOPPON is verified so you shop with total confidence.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon pink"><i class="fa-solid fa-recycle"></i></div>
                        <h3>Thrift & New</h3>
                        <p>Find brand-new collections and unique second-hand treasures side by side.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon green"><i class="fa-solid fa-bolt"></i></div>
                        <h3>Fast & Easy</h3>
                        <p>Post an item in minutes or browse the latest trends without any hassle.</p>
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
                    <div class="slide-card"><div class="img-box"><img src="../images/item1.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item2.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item3.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item4.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item5.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item6.jpg" alt="item"></div></div>
                    <!-- Duplicates for seamless loop -->
                    <div class="slide-card"><div class="img-box"><img src="../images/item1.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item2.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item3.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item4.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item5.jpg" alt="item"></div></div>
                    <div class="slide-card"><div class="img-box"><img src="../images/item6.jpg" alt="item"></div></div>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div style="max-width:1280px; margin:0 auto; padding: 0 32px;">
                <div class="about-inner">
                    <div class="about-text">
                        <h2>About <span>SHOPPON</span></h2>
                        <p>SHOPPON is your go-to marketplace for curated fashion. We connect buyers with sellers offering everything from brand-new collections to unique thrift treasures. Affordable style, always within reach — whether you are shopping or selling.</p>
                        <div class="about-pills">
                            <span class="pill"><i class="fa-solid fa-check"></i> Free to join</span>
                            <span class="pill"><i class="fa-solid fa-check"></i> Easy selling</span>
                            <span class="pill"><i class="fa-solid fa-check"></i> Secure checkout</span>
                        </div>
                    </div>
                    <div class="about-visual">
                        <div class="about-stat-box">
                            <span class="num">2K+</span>
                            <span class="lbl">Products Listed</span>
                        </div>
                        <div class="about-stat-box">
                            <span class="num">500+</span>
                            <span class="lbl">Active Sellers</span>
                        </div>
                    </div>
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
                <p><i class="fa-solid fa-phone"></i> +880 1745636346</p>
                <div class="footer-socials">
                    <a href="#" class="icons"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="icons"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="icons"><i class="fa-brands fa-x-twitter"></i></a>
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