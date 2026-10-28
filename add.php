<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

if(!isset($_SESSION["logged_in"]) || $_SESSION["role"] != "Employee"){
    header("Location: login.php");
    exit;
}

$errors = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $product_name = trim($_POST["product_name"]);
    $category = trim($_POST["category"]);
    $description = trim($_POST["description"]);
    $price = trim($_POST["price"]);
    $quantity = trim($_POST["quantity"]);
    $rating = trim($_POST["rating"]);
    $default_photo_choice = $_POST["default_photo"];

    if($product_name == "") $errors[] = "Product name is required.";
    if($category == "") $errors[] = "Category is required.";
    if($description == "") $errors[] = "Description is required.";
    if($price <= 0) $errors[] = "Price must be positive.";
    if($quantity < 0) $errors[] = "Quantity must be non-negative.";
    if($rating < 1 || $rating > 5) $errors[] = "Rating must be between 1 and 5.";

    if(count($errors) == 0){

        $sql = "INSERT INTO products
        (product_name, category, description, price, quantity, rating, photo1, photo2, photo3, default_photo)
        VALUES
        (:product_name, :category, :description, :price, :quantity, :rating, '', '', '', '')";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":product_name", $product_name);
        $stmt->bindValue(":category", $category);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":price", $price);
        $stmt->bindValue(":quantity", $quantity);
        $stmt->bindValue(":rating", $rating);

        $stmt->execute();

        $product_id = $pdo->lastInsertId();

        $photo1 = $product_id . "_1.jpeg";
        $photo2 = $product_id . "_2.jpeg";
        $photo3 = $product_id . "_3.jpeg";

        move_uploaded_file($_FILES["photo1"]["tmp_name"], "images/" . $photo1);
        move_uploaded_file($_FILES["photo2"]["tmp_name"], "images/" . $photo2);
        move_uploaded_file($_FILES["photo3"]["tmp_name"], "images/" . $photo3);

        if($default_photo_choice == "photo1"){
            $default_photo = $photo1;
        }
        else if($default_photo_choice == "photo2"){
            $default_photo = $photo2;
        }
        else{
            $default_photo = $photo3;
        }

        $sql = "UPDATE products
                SET photo1 = :photo1,
                    photo2 = :photo2,
                    photo3 = :photo3,
                    default_photo = :default_photo
                WHERE product_id = :product_id";

        $stmt = $pdo->prepare($sql);

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

?>

<main>

<h2>Add Product</h2>

<?php
foreach($errors as $error){
    echo "<p class='error-message'>$error</p>";
}
?>

<form method="post" action="add.php" enctype="multipart/form-data">

<fieldset>
<legend>Product Information</legend>

<p>Product Name:
<input type="text" name="product_name" required>
</p>

<p>Category:
<select name="category" required>
<option value="">Select Category</option>
<option value="Embroidery">Embroidery</option>
<option value="Olive Wood">Olive Wood</option>
<option value="Ceramics">Ceramics</option>
<option value="Kitchen">Kitchen</option>
</select>
</p>

<p>Description:
<textarea name="description" required></textarea>
</p>

<p>Price:
<input type="number" name="price" step="0.01" required>
</p>

<p>Quantity:
<input type="number" name="quantity" required>
</p>

<p>Rating:
<input type="number" name="rating" min="1" max="5" required>
</p>

<p>Photo 1:
<input type="file" name="photo1" accept="image/jpeg" required>
</p>

<p>Photo 2:
<input type="file" name="photo2" accept="image/jpeg" required>
</p>

<p>Photo 3:
<input type="file" name="photo3" accept="image/jpeg" required>
</p>

<p>Default Photo:
<input type="radio" name="default_photo" value="photo1" checked> Photo 1
<input type="radio" name="default_photo" value="photo2"> Photo 2
<input type="radio" name="default_photo" value="photo3"> Photo 3
</p>

<p>
<input type="submit" value="Insert">
</p>

</fieldset>

</form>

</main>

<?php
require_once("footer.inc.php");
?>