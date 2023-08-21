<?php

$servername = 'localhost';
$port='3306';
$username = "forostenko";
$password = "260491";
$dbname = "test_dima_forostenko";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    var_dump($exception->getMessage());
}

?>
