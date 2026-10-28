<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

if(!isset($_GET["id"]) && $_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: products.php");
    exit;
}

if(!isset($_SESSION["logged_in"])){
    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit;
}

if($_SESSION["role"] != "Customer"){
    echo "<main>";
    echo "<p>Only customers can add products to basket.</p>";
    echo "<p><a href='products.php'>Back to Products</a></p>";
    echo "</main>";
    require_once("footer.inc.php");
    exit;
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":product_id", $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$product){
        $error = "Product not found.";
    }
    else if(!ctype_digit($quantity) || $quantity <= 0){
        $error = "Quantity must be a positive number.";
    }
    else if($quantity > $product["quantity"]){
        $error = "Requested quantity exceeds available stock.";
    }
    else{

        if(!isset($_SESSION["basket"])){
            $_SESSION["basket"] = array();
        }

        if(isset($_SESSION["basket"][$product_id])){
            $_SESSION["basket"][$product_id] += $quantity;
        }
        else{
            $_SESSION["basket"][$product_id] = $quantity;
        }

        header("Location: basket.php");
        exit;
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET"){

    $product_id = $_GET["id"];

    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":product_id", $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$product){
        echo "<main>";
        echo "<p>Product not found.</p>";
        echo "</main>";
        require_once("footer.inc.php");
        exit;
    }
}

?>

<main>

<h2>Add To Basket</h2>

<?php
if($error != ""){
    echo "<p class='error-message'>$error</p>";
}
?>

<form method="post" action="add_to_basket.php">

    <p>
        Product ID:
        <input type="text" name="product_id" value="<?php echo $product['product_id']; ?>" readonly>
    </p>

    <p>
        Product Name:
        <input type="text" value="<?php echo $product['product_name']; ?>" readonly>
    </p>

    <p>
        Unit Price:
        <input type="text" value="<?php echo $product['price']; ?>" readonly>
    </p>

    <p>
        Quantity:
        <input type="number" name="quantity" value="1" min="1" required>
    </p>

    <p>
        <input type="submit" value="Add To Basket">
    </p>

</form>

<p><a href="products.php">Back to Products</a></p>

</main>

<?php
require_once("footer.inc.php");
?>