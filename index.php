<?php
session_start();
include './includePHP/config.php';

if (ISSET($_POST['login'])) {
    if ($_POST['username'] != "" || $_POST['password'] != "") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM `ACCOUNT_INFO` WHERE `account_username`=? AND `account_password`=? ";
        $query = $conn->prepare($sql);
        $query->execute(array($username, $password));
        $row = $query->rowCount();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if ($row > 0) {
            $_SESSION['userlogin'] = $_POST['username'];
            header("location: dashboard.php");
        } else {
            echo "
				<script>alert('Invalid username or password')</script>
				<script>window.location = 'index.php'</script>
				";
        }
    }
}
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
                <a href="index.php" style="text-decoration: none">
                    <div class="logo">
                        <h1><span class="smarter">Smarter</span> <span class="money">Money</span></h1>
                    </div>
                </a>
                <div class="menu-icon">
                    <i class='bx bx-menu' id="menu-icon"></i>
                </div>
                <div class="nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>

            </div>  
        </nav>

        <!-- Main section -->
        <main id="main">
            <div class="container">
                <form method="post" action="" class="login">
                    <div><h1>Sign In</h1></div>
                    <div class="username">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="No special characters" required>
                    </div>
                    <div class="password">
                        <label for="password" style="padding-right: 6.97px;">Password</label>
                        <input type="password" name="password" id="password" placeholder="Minimum of 8 characters" required>
                    </div>
                    <button class="btn" name="login" type="submit">Sign In</button>
                    <div class="login-footer"><p>Don't have an account? <a href="signup.php">Sign Up</a></p></div>
                </form>
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
