<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

echo "<main>";

if(!isset($_SESSION['basket']) || count($_SESSION['basket']) == 0){

    echo "<h2>Shopping Basket</h2>";
    echo "<p>Your basket is empty.</p>";
    echo "<p><a href='products.php'>Continue Shopping</a></p>";

    echo "</main>";
    require_once("footer.inc.php");
    exit;
}

echo "<h2>Shopping Basket</h2>";

echo "<table border='1' cellpadding='10'>";

echo "
<tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
</tr>
";

$grand_total = 0;

foreach($_SESSION['basket'] as $product_id => $quantity){

    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product){

        $total = $product['price'] * $quantity;
        $grand_total += $total;

        echo "
        <tr>
            <td>{$product['product_name']}</td>
            <td>$" . number_format($product['price'], 2) . "</td>
            <td>$quantity</td>
            <td>$" . number_format($total, 2) . "</td>
        </tr>
        ";
    }
}

echo "</table>";

echo "<h3>Grand Total: $" . number_format($grand_total, 2) . "</h3>";

echo "<p><a href='checkout.php'>Checkout</a></p>";
echo "<p><a href='products.php'>Continue Shopping</a></p>";

echo "</main>";

require_once("footer.inc.php");

?>
