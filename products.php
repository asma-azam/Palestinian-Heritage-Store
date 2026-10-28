<?php

require_once("dbconfig.inc.php");
require_once("Product.class.php");
require_once("header.inc.php");

if(!isset($_SESSION["product_search_state"])){
    $_SESSION["product_search_state"] = json_encode(array(
        "name" => "",
        "max_price" => "",
        "category" => "",
        "sort_col" => "product_id",
        "sort_dir" => "ASC",
        "current_page" => 1,
        "per_page" => 10
    ));
}

$state = json_decode($_SESSION["product_search_state"], true);

$state["per_page"] = 10;

if(isset($_POST["filter"])){
    $state["name"] = trim($_POST["name"]);
    $state["max_price"] = trim($_POST["max_price"]);
    $state["category"] = trim($_POST["category"]);
    $state["current_page"] = 1;
    $state["per_page"] = 10;
}

if(isset($_GET["reset"])){
    $state["name"] = "";
    $state["max_price"] = "";
    $state["category"] = "";
    $state["sort_col"] = "product_id";
    $state["sort_dir"] = "ASC";
    $state["current_page"] = 1;
    $state["per_page"] = 10;
}

if(isset($_GET["sort"])){
    if($_GET["sort"] == "product_id" || $_GET["sort"] == "price" || $_GET["sort"] == "category"){
        if($state["sort_col"] == $_GET["sort"]){
            if($state["sort_dir"] == "ASC"){
                $state["sort_dir"] = "DESC";
            }
            else{
                $state["sort_dir"] = "ASC";
            }
        }
        else{
            $state["sort_col"] = $_GET["sort"];
            $state["sort_dir"] = "ASC";
        }
    }
}

if(isset($_GET["page"])){
    $state["current_page"] = (int)$_GET["page"];

    if($state["current_page"] < 1){
        $state["current_page"] = 1;
    }
}

$_SESSION["product_search_state"] = json_encode($state);

$where = " WHERE 1=1 ";
$params = array();

if($state["name"] != ""){
    $where .= " AND product_name LIKE :name ";
    $params[":name"] = "%" . $state["name"] . "%";
}

if($state["max_price"] != ""){
    $where .= " AND price <= :max_price ";
    $params[":max_price"] = $state["max_price"];
}

if($state["category"] != ""){
    $where .= " AND category = :category ";
    $params[":category"] = $state["category"];
}

$count_sql = "SELECT COUNT(*) FROM products " . $where;
$count_stmt = $pdo->prepare($count_sql);

foreach($params as $key => $value){
    $count_stmt->bindValue($key, $value);
}

$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();

$per_page = 10;

$total_pages = ceil($total_records / $per_page);

if($total_pages == 0){
    $total_pages = 1;
}

if($state["current_page"] > $total_pages){
    $state["current_page"] = $total_pages;
}

$offset = ($state["current_page"] - 1) * $per_page;

$sort_col = $state["sort_col"];
$sort_dir = $state["sort_dir"];

$sql = "SELECT * FROM products " . $where . " ORDER BY $sort_col $sort_dir LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

foreach($params as $key => $value){
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(":limit", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

$stmt->execute();

$cat_sql = "SELECT DISTINCT category FROM products ORDER BY category ASC";
$cat_stmt = $pdo->prepare($cat_sql);
$cat_stmt->execute();

?>

<aside>

    <h3>Advanced Product Search</h3>

    <form method="post" action="products.php">

        <p>
            <label>Product Name:</label>
            <input type="text"
            name="name"
            placeholder="Product Name"
            value="<?php echo $state['name']; ?>">
        </p>

        <p>
            <label>Maximum Price:</label>
            <input type="number"
            name="max_price"
            placeholder="Max Price"
            value="<?php echo $state['max_price']; ?>">
        </p>

        <p>
            <label>Category:</label>
            <select name="category">
                <option value="">Select Category</option>

                <?php

                while($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)){

                    $selected = "";

                    if($state["category"] == $cat["category"]){
                        $selected = "selected";
                    }

                    echo "<option value='" . $cat["category"] . "' $selected>" . $cat["category"] . "</option>";

                }

                ?>

            </select>
        </p>

        <p>
            <input type="submit" name="filter" value="Filter">
        </p>

        <p>
            <a href="products.php?reset=1">Reset / Show All</a>
        </p>

    </form>

</aside>

<main class="products-content">

    <h2>Products</h2>

    <?php

    if(isset($_SESSION["login_message"])){
        echo "<p class='success-message'>" . $_SESSION["login_message"] . "</p>";
        unset($_SESSION["login_message"]);
    }

    if(isset($_SESSION["logged_in"]) && $_SESSION["role"] == "Employee"){
        echo "<p><a href='add.php'>Add Product</a></p>";
    }

    ?>

    <section class="product-grid">

        <?php

        $hasProducts = false;

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $hasProducts = true;

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

            echo $product->displayCard();

        }

        if(!$hasProducts){

            echo "<p>No products found.</p>";

        }

        ?>

        <nav class="pagination">

            <?php

            if($state["current_page"] > 1){

                $prev = $state["current_page"] - 1;

                echo "<a href='products.php?page=$prev'>Previous</a>";

            }
            else{

                echo "<span class='disabled'>Previous</span>";

            }

            ?>

            <span>
                Page <?php echo $state["current_page"]; ?> of <?php echo $total_pages; ?>
            </span>

            <?php

            if($state["current_page"] < $total_pages){

                $next = $state["current_page"] + 1;

                echo "<a href='products.php?page=$next'>Next</a>";

            }
            else{

                echo "<span class='disabled'>Next</span>";

            }

            ?>

        </nav>

    </section>

</main>

<?php
require_once("footer.inc.php");
?>