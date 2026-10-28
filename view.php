<?php

require_once("dbconfig.inc.php");
require_once("Product.class.php");
require_once("header.inc.php");

if(!isset($_GET["id"])){
    echo "<main>";
    echo "<p>No product selected.</p>";
    echo "<p><a href='products.php'>Back To Products</a></p>";
    echo "</main>";
    require_once("footer.inc.php");
    exit;
}

$id = $_GET["id"];

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$row){
    echo "<main>";
    echo "<p>Product not found.</p>";
    echo "<p><a href='products.php'>Back To Products</a></p>";
    echo "</main>";
    require_once("footer.inc.php");
    exit;
}

$product = new Product(
    $row["product_id"],
    $row["product_name"],
    $row["category"],
    $row["description"],
    $row["price"],
    $row["quantity"],
    $row["rating"],
    $row["photo1"],
    $row["photo2"],
    $row["photo3"],
    $row["default_photo"]
);

echo $product->displayProductPage();

require_once("footer.inc.php");

?>