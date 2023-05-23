<?php 

$host = 'localhost'; // or your host name
$dbname = 'peaky'; // your database name
$username = 'root'; // your username
$password = ''; // your password

$dsn = "mysql:host=$host;dbname=$dbname";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $con = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
