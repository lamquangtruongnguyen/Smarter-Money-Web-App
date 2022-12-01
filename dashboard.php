<?php
    session_start();    
    include('./includePHP/config.php');
    if(strlen($_SESSION["userlogin"]) == 0) {
        header("location: index.php");
    }
    else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmarterMoney - Money Management App</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation bar -->
    <nav id="navbar">
        <div class="container">
            <a href="dashboard.php" style="text-decoration: none">
                    <div class="logo">
                        <h1><span class="smarter">Smarter</span> <span class="money">Money</span></h1>
                    </div>
                </a>
            <div class="menu-icon">
                <i class='bx bx-menu' id="menu-icon"></i>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="logout.php">Log out</a></li>
                </ul>
            </div>
        </div>  
    </nav>
    
    <!-- Main section -->
    <main id =" main">
        <div class="container">
            <a href="logout.php">Log out</a>
        </div>
    </main>
    <!-- Footer -->
    <footer id="footer">
        <div class="container">
            <div class="footer-text"><p>copyright</p></div>
            <i class='bx bxs-copyright' class="copyright"></i>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    }
