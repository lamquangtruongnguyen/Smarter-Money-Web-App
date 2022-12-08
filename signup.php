<?php
include './includePHP/config.php';
if (isset($_POST['signup'])) {
    //Getting post values
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql1 = "SELECT `ACCOUNT_USERNAME` FROM `ACCOUNT_INFO` WHERE `ACCOUNT_USERNAME` = :username;";
    $query1 = $conn->prepare($sql1);
    $query1->bindValue(':username', $username);
    $query1->execute();
    if ($query1->rowCount() > 0) {
        echo "<script>alert('Username already exists')</script>";
        echo "<script>window.location='signup.php'</script>";
    } else {
        $sql1 = "SELECT `USER_ID` FROM `USER_INFO` WHERE `USER_EMAIL` = :email;";
        $query1 = $conn->prepare($sql1);
        $query1->bindValue(':email', $email);
        $query1->execute();
        $result1 = $query1->fetch(PDO::FETCH_ASSOC);
        if ($query1->rowCount() > 0) {
            $account_user_id = $result1['USER_ID'];
            $sql = "INSERT INTO `ACCOUNT_INFO`(`ACCOUNT_USERNAME`, `ACCOUNT_PASSWORD`, `USER_ID`) VALUES (:username,:password,:account_user_id)";
            $query = $conn->prepare($sql);
            $query->bindValue(':username', $username);
            $query->bindValue(':password', $password);
            $query->bindValue(':account_user_id', $account_user_id);
        } else {
            $sql1 = "SELECT MAX(USER_ID) AS `USER_ID` FROM `USER_INFO`";
            $query1 = $conn->prepare($sql1);
            $query1->execute();
            $result1 = $query1->fetch(PDO::FETCH_ASSOC);
            $account_user_id = $result1['USER_ID'] + 1;
            $sql = "INSERT INTO `USER_INFO`(`USER_LNAME`, `USER_FNAME`, `USER_EMAIL`, `USER_PHONE`) VALUES (:firstname,:lastname,:email,:phone);"
                    . "INSERT INTO `ACCOUNT_INFO`(`ACCOUNT_USERNAME`, `ACCOUNT_PASSWORD`, `USER_ID`) VALUES (:username,:password,:account_user_id);";
            $query = $conn->prepare($sql);
            $query->bindValue(':firstname', $firstname);
            $query->bindValue(':lastname', $lastname);
            $query->bindValue(':email', $email);
            $query->bindValue(':phone', $phone);
            $query->bindValue(':username', $username);
            $query->bindValue(':password', $password);
            $query->bindValue(':account_user_id', $account_user_id);
        }
        $query->execute();
    }
    $lastInsertId = $conn->lastInsertId();
        if ($lastInsertId) {
            echo "<script>alert('You have successfully signed up')</script>";
            echo "<script>window.location='index.php'</script>";
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
        <style>
            #main .container {
                top: 25vh;
                left: 20vw;
            }
            @media (min-width: 1200px) {
                label[for="lastname"] {
                    padding-right: 39.81px !important;
                }
                label[for="email"] {
                    padding-right: 81.07px !important;
                }
                label[for="phone"] {
                    padding-right: 74.55px !important;
                }
                label[for="username"] {
                    padding-right: 3.39px !important;
                }
                label[for="password"] {
                    padding-right: 8.96px !important;
                }
            }
        </style>
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
                        <li><a href="aboutus.php">About Us</a></li>
                        <li><a href="index.php">Sign In</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>

            </div>  
        </nav>

        <!-- Main section -->
        <main id="main">
            <div class="container">
                <form method="post" action="" class="login">
                    <div><h1>Sign Up</h1></div>
                    <div class="username">
                        <label for="firstname" style="padding-right: 39.57px;">First Name</label>
                        <input type="text" name="firstname" id="username" required>
                    </div>
                    <div class="username">
                        <label for="lastname" style="padding-right: 39.83px;">Last Name</label>
                        <input type="text" name="lastname" id="username" required>
                    </div>
                    <div class="username">
                        <label for="email" style="padding-right: 84.96px;">Email</label>
                        <input type="email" name="email" id="username" required>
                    </div>
                    <div class="username">
                        <label for="phone" style="padding-right: 77.82px;">Phone</label>
                        <input type="text" name="phone" id="username" required>
                    </div>
                    <div class="username">
                        <label for="username">New Username</label>
                        <input type="text" name="username" id="username" placeholder="No special characters" required>
                    </div>
                    <div class="password">
                        <label for="password" style="padding-right: 6.09px;">New Password</label>
                        <input type="password" name="password" id="password" placeholder="Minimum of 8 characters" required>
                    </div>
                    <button class="btn" type="submit" name="signup">Sign Up</button>
                    <div class="login-footer"><p>Already have an account? <a href="index.php">Sign In</a></p></div>
                    <?php
                    ?></form>
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
