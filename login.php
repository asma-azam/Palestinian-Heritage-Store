<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user["password"])){

        $_SESSION["logged_in"] = true;
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];
        $_SESSION["role"] = $user["role"];

        if(isset($_SESSION["redirect_after_login"])){
            $redirect = $_SESSION["redirect_after_login"];
            unset($_SESSION["redirect_after_login"]);
            header("Location: " . $redirect);
            exit;
        }

        header("Location: products.php");
        exit;

    }else{
        $error = "Invalid email or password.";
    }
}

?>

<main>

<h2>Login</h2>

<?php
if($error != ""){
    echo "<p class='error-message'>$error</p>";
}
?>

<form method="post" action="login.php">

    <p>
        Email:
        <input type="email" name="email" required>
    </p>

    <p>
        Password:
        <input type="password" name="password" required>
    </p>

    <p>
        <input type="submit" value="Login">
    </p>

</form>

</main>

<?php
require_once("footer.inc.php");
?>