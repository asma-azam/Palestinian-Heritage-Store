<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

if(!isset($_SESSION["logged_in"])){
    $_SESSION["redirect_after_login"] = "checkout.php";
    header("Location: login.php");
    exit;
}

if(!isset($_SESSION["basket"]) || count($_SESSION["basket"]) == 0){
    echo "<main>";
    echo "<h2>Checkout</h2>";
    echo "<p>Your basket is empty.</p>";
    echo "<p><a href='products.php'>Back to Products</a></p>";
    echo "</main>";
    require_once("footer.inc.php");
    exit;
}

$errors = array();
$total = 0;

foreach($_SESSION["basket"] as $product_id => $quantity){

    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":product_id", $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product){
        $total += $product["price"] * $quantity;
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $cardholder = trim($_POST["cardholder"]);
    $card_number = trim($_POST["card_number"]);
    $expiry_month = trim($_POST["expiry_month"]);
    $expiry_year = trim($_POST["expiry_year"]);
    $cvv = trim($_POST["cvv"]);

    if($cardholder == ""){
        $errors[] = "Cardholder name is required.";
    }

    if(!ctype_digit($card_number) || strlen($card_number) != 16){
        $errors[] = "Card number must be 16 digits.";
    }

    if(!ctype_digit($cvv) || strlen($cvv) != 3){
        $errors[] = "CVV must be 3 digits.";
    }

    if(count($errors) == 0){

        $order_id = rand(1000000000, 9999999999);

        $sql = "INSERT INTO orders
                (order_id, user_id, total)
                VALUES
                (:order_id, :user_id, :total)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":order_id", $order_id);
        $stmt->bindValue(":user_id", $_SESSION["user_id"]);
        $stmt->bindValue(":total", $total);
        $stmt->execute();

        foreach($_SESSION["basket"] as $product_id => $quantity){

            $sql = "SELECT * FROM products WHERE product_id = :product_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":product_id", $product_id);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if($product){

                $sql = "INSERT INTO order_items
                        (order_id, product_id, quantity, unit_price)
                        VALUES
                        (:order_id, :product_id, :quantity, :unit_price)";

                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":order_id", $order_id);
                $stmt->bindValue(":product_id", $product_id);
                $stmt->bindValue(":quantity", $quantity);
                $stmt->bindValue(":unit_price", $product["price"]);
                $stmt->execute();
            }
        }

        unset($_SESSION["basket"]);

        echo "<main>";
        echo "<h2>Order Confirmed</h2>";
        echo "<p>Your Order ID is: $order_id</p>";
        echo "<p>Order Total: $" . number_format($total, 2) . "</p>";
        echo "<p><a href='products.php'>Back to Products</a></p>";
        echo "</main>";

        require_once("footer.inc.php");
        exit;
    }
}

?>

<main>

<h2>Checkout</h2>

<?php
foreach($errors as $error){
    echo "<p class='error-message'>$error</p>";
}
?>

<h3>Basket Summary</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Product</th>
    <th>Unit Price</th>
    <th>Quantity</th>
    <th>Total</th>
</tr>

<?php

foreach($_SESSION["basket"] as $product_id => $quantity){

    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":product_id", $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product){

        $line_total = $product["price"] * $quantity;

        echo "<tr>";
        echo "<td>" . $product["product_name"] . "</td>";
        echo "<td>$" . number_format($product["price"], 2) . "</td>";
        echo "<td>$quantity</td>";
        echo "<td>$" . number_format($line_total, 2) . "</td>";
        echo "</tr>";
    }
}

?>

</table>

<h3>Total: $<?php echo number_format($total, 2); ?></h3>

<form method="post" action="checkout.php">

<fieldset>
<legend>Credit Card Information</legend>

<p>Cardholder Name:
<input type="text" name="cardholder" required>
</p>

<p>Card Number:
<input type="text" name="card_number" required>
</p>

<p>Expiry Month:
<select name="expiry_month" required>
<?php
for($i = 1; $i <= 12; $i++){
    $month = str_pad($i, 2, "0", STR_PAD_LEFT);
    echo "<option value='$month'>$month</option>";
}
?>
</select>
</p>

<p>Expiry Year:
<select name="expiry_year" required>
<?php
$current_year = date("Y");
for($i = $current_year; $i <= $current_year + 10; $i++){
    echo "<option value='$i'>$i</option>";
}
?>
</select>
</p>

<p>CVV:
<input type="text" name="cvv" required>
</p>

<p>
<input type="submit" value="Place Order">
</p>

</fieldset>

</form>

</main>

<?php
require_once("footer.inc.php");
?>