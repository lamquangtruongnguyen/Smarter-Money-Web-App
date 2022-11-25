<?php
    function check_login($con) {
        if(isset($_SESSION['account_id'])) {
            $id = $_SESSION['account_id'];
            $query = "SELECT * FROM ACCOUNT_INFO WHERE ACCOUNT_ID = '$id' limit 1";
            
            $result = $pdo->prepare($query);
            $result->execute();
        }
    }
?>