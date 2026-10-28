<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Palestinian Souvenir Store</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header>

    <div class="header-brand">

        <img src="images/images.jpg"
        class="site-logo"
        alt="Store Logo">

        <h1>Palestinian Souvenir Store</h1>

    </div>

    <nav class="main-nav">

        <a href="products.php">Products</a>

        <a href="basket.php">Basket</a>

        <?php

        if(isset($_SESSION['logged_in'])){

            ?>

            <a href="logout.php">Logout</a>

            <?php

        }
        else{

            ?>

            <a href="login.php">Login</a>

            <a href="register.php">Register</a>

            <?php

        }

        ?>

    </nav>

    <?php

    if(isset($_SESSION['logged_in'])){

        echo "<p class='user-info'>";

        echo "Welcome "
        . $_SESSION['first_name']
        . " ("
        . $_SESSION['role']
        . ")";

        echo "</p>";

    }

    ?>

</header>

<div class="page-layout">