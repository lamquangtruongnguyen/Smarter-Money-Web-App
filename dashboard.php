<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('./includePHP/config.php');
include './includePHP/functions.php';
if (strlen($_SESSION["userlogin"]) == 0) {
    header("location: index.php");
} else {
    $username = $_SESSION["userlogin"];
    $sql1 = "SELECT ACCOUNT_ID FROM ACCOUNT_INFO WHERE `ACCOUNT_USERNAME` = :username;";
    $query1 = $conn->prepare($sql1);
    $query1->bindValue(':username', $username);
    $query1->execute();
    $result1 = $query1->fetch(PDO::FETCH_ASSOC);
    $account_id = $result1['ACCOUNT_ID'];
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
            <link rel="stylesheet" href="css/styleDashboard.css">
            <script>
                function change_tab(id) {
                    document.getElementById("page_content").innerHTML = document.getElementById(id + "_desc").innerHTML;
                    document.getElementById("page1").className = "notselected";
                    document.getElementById("page2").className = "notselected";
                    document.getElementById("page3").className = "notselected";
                    document.getElementById("page4").className = "notselected";
                    document.getElementById("page5").className = "notselected";
                    document.getElementById(id).className = "selected";
                }
            </script>
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
                            <li><a href="aboutus.php">About Us</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </div>
                </div>  
            </nav>

            <!-- Main section -->
            <main id ="main">
                <div class="container_btn">
                    <li class="selected" id="page1" onclick="change_tab(this.id);">Summary</li>
                    <li class="notselected" id="page2" onclick="change_tab(this.id);">Edit Category</li>
                    <li class="notselected" id="page3" onclick="change_tab(this.id);">Add Salary</li>
                    <li class="notselected" id="page4" onclick="change_tab(this.id);">Add Budget</li>
                    <li class="notselected" id="page5" onclick="change_tab(this.id);">Add Transaction</li>
                </div>
                <div class="container_info">
                    <div class='hidden_desc' id="page1_desc">
                        <h2>Summary</h2>
                        <?php
                        getSummary($conn, $username);
                        ?>
                    </div>

                    <div class='hidden_desc' id="page2_desc">
                        <h2>Category</h2>
                        <?php
                        $sql1 = "SELECT `CATEGORY_NAME`, A.ACCOUNT_ID FROM `CATEGORY` AS C"
                                . " JOIN ACCOUNT_INFO AS A"
                                . " ON C.ACCOUNT_ID = A.ACCOUNT_ID"
                                . " WHERE `ACCOUNT_USERNAME` = :username;";
                        $query1 = $conn->prepare($sql1);
                        $query1->bindValue(':username', $username);
                        $query1->execute();
                        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                        echo '<div class="cat_list"><ul>';
                        foreach ($result1 as $res) {
                            echo '<li>' . $res['CATEGORY_NAME'] . '</li>';
                        }
                        echo '</ul></div>';
                        ?>
                        <form method="POST">
                            <div class="username" style="font-size: 20px">
                                <label for="category_new">Category name:
                                    <input type="text" name="category_new" id="username">
                                </label>
                            </div>
                            <button class="btn" type="submit" name="add_category">Add Category</button>
                            <div>
                                <label for="category" style="font-size: 20px;">Category name:</label>
                                <select name="category" id="category" style="font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%">
                                    <?php
                                    $sql1 = "SELECT `CATEGORY_NAME`, A.ACCOUNT_ID FROM `CATEGORY` AS C"
                                            . " JOIN ACCOUNT_INFO AS A"
                                            . " ON C.ACCOUNT_ID = A.ACCOUNT_ID"
                                            . " WHERE `ACCOUNT_USERNAME` = :username"
                                            . " ORDER BY CATEGORY_NAME;";
                                    $query1 = $conn->prepare($sql1);
                                    $query1->bindValue(':username', $username);
                                    $query1->execute();
                                    $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result1 as $res) {
                                        echo '<option value="' . $res['CATEGORY_NAME'] . '">' . $res['CATEGORY_NAME'] . '</option>';
                                    }
                                    ?>
                                </select>
                                </label>
                            </div>
                            <button class="btn" type="submit" name="remove_category">Remove Category</button>
                        </form>
                        <?php
                        if (isset($_POST["add_category"])) {
                            $category = $_POST["category_new"];
                            $sql = "INSERT INTO `CATEGORY`(`CATEGORY_NAME`, `ACCOUNT_ID`) VALUES (:category, :account_id);";
                            $query = $conn->prepare($sql);
                            $query->bindValue(':category', $category);
                            $query->bindValue(':account_id', $account_id);
                            $query->execute();
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else if (isset($_POST["remove_category"])) {
                            $category = $_POST["category"];
                            $sql = "DELETE FROM `CATEGORY` WHERE CATEGORY_NAME = :category;";
                            $query = $conn->prepare($sql);
                            $query->bindValue(':category', $category);
                            $query->execute();
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                        ?>
                    </div>

                    <div class='hidden_desc' id="page3_desc">
                        <h2>Salary</h2>
                        <form method="POST">
                            <div class="username" style="font-size: 20px">
                                <div>
                                    <label for="salary_amount_new">Amount:
                                        <input style="margin-left: 28.58px" type="number" step="0.01" min="0" name="salary_amount_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="salary_start">Start date:
                                        <input type="date" name="salary_start_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="salary_end">End date:
                                        <input style="margin-left: 15.71px" type="date" name="salary_end_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="salary_freq">Select the frequency:</label>
                                    <select name="salary_freq" id="salary_freq" style="font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%">
                                        <option value="Bi-Weekly">Bi-Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                    </label>
                                </div>
                            </div>
                            <button class="btn" type="submit" name="add_salary" style="width: 120px;">Submit</button>
                        </form>
                        <?php
                        if (isset($_POST["add_salary"])) {
                            $salary_amount = $_POST["salary_amount_new"];
                            $salary_start = $_POST["salary_start_new"];
                            $salary_end = $_POST["salary_end_new"];
                            $salary_freq = $_POST["salary_freq"];
                            $sql = "INSERT INTO `SALARY`(`SALARY_FREQ`,`SALARY_AMOUNT`,`SALARY_STARTDATE`,`SALARY_ENDDATE`,`ACCOUNT_ID`) VALUES (:freq, :amount, :startDate, :endDate, :accountID);";
                            $query = $conn->prepare($sql);
                            $query->bindValue(':freq', $salary_freq);
                            $query->bindValue(':amount', $salary_amount);
                            $query->bindValue(':startDate', $salary_start);
                            $query->bindValue(':endDate', $salary_end);
                            $query->bindValue(':accountID', $account_id);
                            $query->execute();
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                        ?>
                    </div>

                    <div class='hidden_desc' id="page4_desc">
                        <h2>Budget</h2>
                        <form method="POST">
                            <div class="username" style="font-size: 20px">
                                <div>
                                    <label for="budget_amount_new">Amount:
                                        <input style="margin-left: 28.58px" type="number" step="0.01" min="0" name="budget_amount_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="budget_start">Start date:
                                        <input type="date" name="budget_start_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="salary_end">End date:
                                        <input style="margin-left: 15.71px" type="date" name="budget_end_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="budget_freq">Select the frequency:</label>
                                    <select name="budget_freq" id="budget_freq" style="font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%">
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                    </label>
                                </div>
                            </div>
                            <button class="btn" type="submit" name="add_budget" style="width: 120px;">Submit</button>
                        </form>
                        <?php
                        if (isset($_POST["add_budget"])) {
                            $budget_amount = $_POST["budget_amount_new"];
                            $budget_start = $_POST["budget_start_new"];
                            $budget_end = $_POST["budget_end_new"];
                            $budget_freq = $_POST["budget_freq"];
                            $sql = "INSERT INTO `BUDGET`(`BUDGET_FREQ`,`BUDGET_AMOUNT`,`BUDGET_STARTDATE`,`BUDGET_ENDDATE`,`ACCOUNT_ID`) VALUES (:freq, :amount, :startDate, :endDate, :accountID);";
                            $query = $conn->prepare($sql);
                            $query->bindValue(':freq', $budget_freq);
                            $query->bindValue(':amount', $budget_amount);
                            $query->bindValue(':startDate', $budget_start);
                            $query->bindValue(':endDate', $budget_end);
                            $query->bindValue(':accountID', $account_id);
                            $query->execute();
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                        ?>
                    </div>

                    <div class='hidden_desc' id="page5_desc">
                        <h2>Transaction</h2>
                        <form method="POST">
                            <div class="username" style="font-size: 20px">
                                <div>
                                    <label for="transaction_amount_new">Amount: $
                                        <input type="number" step="0.01" min="0" name="transaction_amount_new" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="transaction_date">Transaction date:
                                        <input type="date" name="transaction_date" id="username" required>
                                    </label>
                                </div>
                                <div>
                                    <label for="transaction_desc">Description:</label>
                                    <textarea id="transaction_desc" name="transaction_desc" rows="5" cols="30" maxlength="250" style="display: block; font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%"></textarea>
                                </div>
                                <div>
                                    <label for="tran_category">Category:</label>
                                    <select name="tran_category" id="tran_category" style="font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%">
                                        <?php
                                        $sql1 = "SELECT `CATEGORY_NAME`, A.ACCOUNT_ID FROM `CATEGORY` AS C"
                                                . " JOIN ACCOUNT_INFO AS A"
                                                . " ON C.ACCOUNT_ID = A.ACCOUNT_ID"
                                                . " WHERE `ACCOUNT_USERNAME` = :username"
                                                . " ORDER BY CATEGORY_NAME;";
                                        $query1 = $conn->prepare($sql1);
                                        $query1->bindValue(':username', $username);
                                        $query1->execute();
                                        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result1 as $res) {
                                            echo '<option value="' . $res['CATEGORY_NAME'] . '">' . $res['CATEGORY_NAME'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    </label>
                                </div>
                                <div>
                                    <label for="tran_type">Transaction type:</label>
                                    <select name="tran_type" id="tran_type" style="font-size: 20px; border: 1px solid #403f40; border-radius: 5px; opacity: 60%">
                                        <option value="Credit">Credit</option>
                                        <option value="Debit">Debit</option>
                                    </select>
                                    </label>
                                </div>
                            </div>
                            <button class="btn" type="submit" name="add_transaction" style="width: 120px;">Submit</button>
                        </form>
                        <?php
                        if (isset($_POST["add_transaction"])) {
                            $category_name = $_POST["tran_category"];
                            $sql1 = "SELECT CATEGORY_ID FROM CATEGORY WHERE `CATEGORY_NAME` = :tran_category;";
                            $query1 = $conn->prepare($sql1);
                            $query1->bindValue(':tran_category', $category_name);
                            $query1->execute();
                            $result1 = $query1->fetch(PDO::FETCH_ASSOC);
                            $category_id = $result1['CATEGORY_ID'];

                            $tran_type = $_POST["tran_type"];
                            $tran_amount = $_POST["transaction_amount_new"];
                            $tran_desc = $_POST["transaction_desc"];
                            $tran_date = $_POST["transaction_date"];
                            $tran_cat_id = $category_id;

                            $sql = "INSERT INTO `USER_TRANSACTION`(`TRANSACTION_TYPE`,`TRANSACTION_AMOUNT`,`TRANSACTION_DESC`,`TRANSACTION_DATE`,`CATEGORY_ID`) VALUES (:type, :amount, :desc, :tranDate, :catID);";
                            $query = $conn->prepare($sql);
                            $query->bindValue(':type', $tran_type);
                            $query->bindValue(':amount', $tran_amount);
                            $query->bindValue(':desc', $tran_desc);
                            $query->bindValue(':tranDate', $tran_date);
                            $query->bindValue(':catID', $tran_cat_id);
                            $query->execute();
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                        ?>
                    </div>
                    <div id="page_content">
                        <h2>Summary</h2>
                        <?php
                        getSummary($conn, $username);
                        ?>
                    </div>
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
