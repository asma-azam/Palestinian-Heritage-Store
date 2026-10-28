<?php

$host = "localhost";
$dbname = "web1222779_souvenirstore";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e){

    die("Connection Failed : " . $e->getMessage());

}

?>