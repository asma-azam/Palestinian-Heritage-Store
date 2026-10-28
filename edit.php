<?php
require_once("dbconfig.inc.php");
require_once("header.inc.php");
if(!isset($_SESSION["logged_in"]) || $_SESSION["role"] != "Employee"){
    header("Location: login.php");
    exit;
}
if(!isset($_GET["id"]) && !isset($_POST["product_id"])){
    header("Location: products.php");
    exit;
}
$errors = array();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $product_id = $_POST["product_id"];
    $description = trim($_POST["description"]);
    $price = trim($_POST["price"]);
    $quantity = trim($_POST["quantity"]);
    $default_photo = $_POST["default_photo"];
    if($price <= 0){
        $errors[] = "Price must be positive.";
    }
    if($quantity < 0){
        $errors[] = "Quantity must be non-negative.";
    }
    if(count($errors) == 0){
        $photo1 = $_POST["old_photo1"];
        $photo2 = $_POST["old_photo2"];
        $photo3 = $_POST["old_photo3"];
        if(isset($_FILES["photo1"]) && $_FILES["photo1"]["name"] != ""){
            $photo1 = $product_id . "_1.jpeg";
            move_uploaded_file($_FILES["photo1"]["tmp_name"], "images/" . $photo1);
        }
        if(isset($_FILES["photo2"]) && $_FILES["photo2"]["name"] != ""){
            $photo2 = $product_id . "_2.jpeg";
            move_uploaded_file($_FILES["photo2"]["tmp_name"], "images/" . $photo2);
        }
        if(isset($_FILES["photo3"]) && $_FILES["photo3"]["name"] != ""){
            $photo3 = $product_id . "_3.jpeg";
            move_uploaded_file($_FILES["photo3"]["tmp_name"], "images/" . $photo3);
        }
        $sql = "UPDATE products
                SET description = :description,
                    price = :price,
                    quantity = :quantity,
                    photo1 = :photo1,
                    photo2 = :photo2,
                    photo3 = :photo3,
                    default_photo = :default_photo
                WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":price", $price);
        $stmt->bindValue(":quantity", $quantity);
        $stmt->bindValue(":photo1", $photo1);
        $stmt->bindValue(":photo2", $photo2);
        $stmt->bindValue(":photo3", $photo3);
        $stmt->bindValue(":default_photo", $default_photo);
        $stmt->bindValue(":product_id", $product_id);
        $stmt->execute();
        header("Location: products.php");
        exit;
    }
}
$id = isset($_GET["id"]) ? $_GET["id"] : $_POST["product_id"];
$sql = "SELECT * FROM products WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":product_id", $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$product){
    echo "<p>Product not found.</p>";
    require_once("footer.inc.php");
    exit;
}
?>
<main>
<h2>Edit Product</h2>
<?php
foreach($errors as $error){
    echo "<p class='error-message'>$error</p>";
}
?>
<form method="post" action="edit.php" enctype="multipart/form-data">
<input type="hidden" name="product_id"
value="<?php echo $product['product_id']; ?>">
<input type="hidden" name="old_photo1"
value="<?php echo $product['photo1']; ?>">
<input type="hidden" name="old_photo2"
value="<?php echo $product['photo2']; ?>">
<input type="hidden" name="old_photo3"
value="<?php echo $product['photo3']; ?>">
<p>
Product ID:
<input type="text"
value="<?php echo $product['product_id']; ?>"
disabled>
</p>
<p>
Product Name:
<input type="text"
value="<?php echo $product['product_name']; ?>"
disabled>
</p>
<p>
Category:
<input type="text"
value="<?php echo $product['category']; ?>"
disabled>
</p>
<p>
Rating:
<input type="text"
value="<?php echo $product['rating']; ?>"
disabled>
</p>
<p>
Description:
<textarea name="description"><?php echo $product['description']; ?></textarea>
</p>
<p>
Price:
<input type="number"
step="0.01"
name="price"
value="<?php echo $product['price']; ?>">
</p>
<p>
Quantity:
<input type="number"
name="quantity"
value="<?php echo $product['quantity']; ?>">
</p>
<p>
Default Photo:
<br>
<input type="radio"
name="default_photo"
value="<?php echo $product['photo1']; ?>"
<?php if($product['default_photo'] == $product['photo1']) 
    echo "checked"; ?>>
Photo 1
<br>
<input type="radio"
name="default_photo"
value="<?php echo $product['photo2']; ?>"
<?php if($product['default_photo'] == $product['photo2'])
     echo "checked"; ?>>
Photo 2
<br>
<input type="radio"
name="default_photo"
value="<?php echo $product['photo3']; ?>"
<?php if($product['default_photo'] == $product['photo3'])
     echo "checked"; ?>>
Photo 3
</p>
<p>
Replace Photo 1:
<input type="file" name="photo1" accept="image/jpeg">
</p>
<p>
Replace Photo 2:
<input type="file" name="photo2" accept="image/jpeg">
</p>
<p>
Replace Photo 3:
<input type="file" name="photo3" accept="image/jpeg">
</p>
<p>
<input type="submit" value="Save Changes">
</p>
</form>
<p>
<a href="products.php">Back To Products</a>
</p>
</main>
<?php
require_once("footer.inc.php");
?>