<?php
// rent.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JB Lights and Sound Rentals</title>
    <link rel="stylesheet" href="rent.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e0d6e2a8.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- HEADER with JB logo and hamburger menu -->
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="images/jb-logo.png" alt="JB Logo">
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#">RENTALS</a></li>
                    <li><a href="#">PACKAGES</a></li>
                </ul>
            </nav>
            <div class="menu-toggle" onclick="openNav()">
                <span></span><span></span><span></span>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <h1>ELEVATE YOUR EVENT WITH PREMIUM SOUND AND LIGHTING!</h1>
        <p>Great sound and lighting for any event. Professional service, guaranteed satisfaction.</p>
    </section>

    <!-- RENTALS -->
    <section class="rentals">
        <h2>CHECK OUT OUR RENTAL RANGE! ELEVATE YOUR EVENT WITH OUR TOP-NOTCH EQUIPMENT.</h2>
        <div class="rental-equipment">
            <div class="equipment-item">
                <img src="images/roof-truss.jpg" alt="Stage and Truss">
                <h3>STAGE AND TRUSS</h3>
            </div>
            <div class="equipment-item">
                <img src="images/sound-system.jpg" alt="Sound System">
                <h3>SOUND SYSTEM</h3>
            </div>
            <div class="equipment-item">
                <img src="images/led-lights.jpg" alt="LED Lights">
                <h3>LED LIGHTS</h3>
            </div>
            <div class="equipment-item">
                <img src="images/chairs-tables.jpg" alt="Chairs and Tables">
                <h3>CHAIRS & TABLES</h3>
            </div>
            <div class="equipment-item">
                <img src="images/led-wall.jpg" alt="LED Wall">
                <h3>LED WALL</h3>
            </div>
            <div class="equipment-item">
                <img src="images/projector.jpg" alt="Projector">
                <h3>PROJECTOR</h3>
            </div>
        </div>
    </section>

    <section class="reserve">
        <a href="#" class="reserve-button">RESERVE NOW!</a>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <p><i class="fa-solid fa-phone"></i> +965-479-6633</p>
                <p><i class="fa-solid fa-envelope"></i> jbeventsound@gmail.com</p>
                <p><i class="fa-solid fa-location-dot"></i> 28th Perez St. Real, Malabon City, Philippines</p>
                <p><i class="fa-brands fa-facebook"></i> JB Lights and Sounds</p>
            </div>
            <div class="footer-right">
                <img src="images/jb-logo-footer.png" alt="JB Footer Logo">
            </div>
        </div>
    </footer>

    <!-- SIDE NAVIGATION -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><</a><br><br>
        <a href="#" class="menu-item">PROFILE</a>
        <a href="#" class="menu-item">CONTACT US</a>
        <a href="#" class="menu-item">PAYMENT METHOD</a><br><br><br>
        <button class="logout-button">LOG OUT</button>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "400px";
        }
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>

</body>
</html>
