<?php

$servername = "localhost";
$databasename = "money_management";
$dbuser = "database3301";
$dbpass = "hagl1234";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$databasename", $dbuser, $dbpass);
    // set the PDO error mode to exception  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

