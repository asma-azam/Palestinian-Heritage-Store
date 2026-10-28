<?php

class Product {

    private $productId;
    private $productName;
    private $category;
    private $description;
    private $price;
    private $quantity;
    private $rating;
    private $photo1;
    private $photo2;
    private $photo3;
    private $defaultPhoto;

    public function __construct(
        $productId = "",
        $productName = "",
        $category = "",
        $description = "",
        $price = "",
        $quantity = "",
        $rating = "",
        $photo1 = "",
        $photo2 = "",
        $photo3 = "",
        $defaultPhoto = ""
    ) {

        $this->productId = $productId;
        $this->productName = $productName;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->rating = $rating;
        $this->photo1 = $photo1;
        $this->photo2 = $photo2;
        $this->photo3 = $photo3;
        $this->defaultPhoto = $defaultPhoto;

    }

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function getProductName() {
        return $this->productName;
    }

    public function setProductName($productName) {
        $this->productName = $productName;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getPhoto1() {
        return $this->photo1;
    }

    public function setPhoto1($photo1) {
        $this->photo1 = $photo1;
    }

    public function getPhoto2() {
        return $this->photo2;
    }

    public function setPhoto2($photo2) {
        $this->photo2 = $photo2;
    }

    public function getPhoto3() {
        return $this->photo3;
    }

    public function setPhoto3($photo3) {
        $this->photo3 = $photo3;
    }

    public function getDefaultPhoto() {
        return $this->defaultPhoto;
    }

    public function setDefaultPhoto($defaultPhoto) {
        $this->defaultPhoto = $defaultPhoto;
    }

    private function getBadgeClass() {

        $categoryClass = strtolower($this->category);
        $categoryClass = str_replace(" ", "-", $categoryClass);
        $categoryClass = preg_replace("/[^a-z0-9\-]/", "", $categoryClass);

        return "badge-" . $categoryClass;

    }

    private function displayThumbnail($photo) {

        if($photo == ""){
            return "";
        }

        $activeClass = "";

        if($photo == $this->defaultPhoto){
            $activeClass = "active-thumb";
        }

        return "
            <a href='view.php?id=$this->productId'>
                <img src='images/$photo'
                class='$activeClass'
                alt='product thumbnail'>
            </a>
        ";

    }

    public function displayCard() {

        $viewLink = "view.php?id=$this->productId";
        $basketLink = "add_to_basket.php?id=$this->productId";
        $editLink = "edit.php?id=$this->productId";

        $actions = "
            <nav class='card-actions'>
                <a class='view-btn' href='$viewLink'>View</a>
        ";

        if(isset($_SESSION['logged_in']) && $_SESSION['role'] == 'Employee'){

            $actions .= "
                <a class='basket-btn' href='$editLink'>Edit</a>
            ";

        }
        else{

            $actions .= "
                <a class='basket-btn' href='$basketLink'>Add To Basket</a>
            ";

        }

        $actions .= "
            </nav>
        ";

        $badgeClass = $this->getBadgeClass();

        $priceFormatted = number_format((float)$this->price, 2);

        $output = "

            <article class='product-card'>

                <img src='images/$this->defaultPhoto'
                class='main-product-img'
                alt='product image'>

                <h3>
                    <a href='$viewLink'>
                        #$this->productId
                    </a>
                </h3>

                <p class='product-name'>
                    $this->productName
                </p>

                <section class='thumbnail-row'>

                    " . $this->displayThumbnail($this->photo1) . "

                    " . $this->displayThumbnail($this->photo2) . "

                    " . $this->displayThumbnail($this->photo3) . "

                </section>

                <p>
                    <span class='badge $badgeClass'>
                        $this->category
                    </span>
                </p>

                <p>
                    Quantity: $this->quantity
                </p>

                <p>
                    Rating: $this->rating / 5
                </p>

                <p class='price'>
                    $$priceFormatted
                </p>

                $actions

            </article>

        ";

        return $output;

    }

    public function displayInTable() {

        $actions = "<a href='view.php?id=$this->productId'>View</a>";

        if(isset($_SESSION['logged_in'])){

            if($_SESSION['role'] == 'Employee'){

                $actions .= " | <a href='edit.php?id=$this->productId'>Edit</a>";

            }
            else{

                $actions .= " | <a href='add_to_basket.php?id=$this->productId'>Add To Basket</a>";

            }

        }
        else{

            $actions .= " | <a href='add_to_basket.php?id=$this->productId'>Add To Basket</a>";

        }

        $output = "

        <tr>

            <td>
                <img src='images/$this->defaultPhoto'
                width='100' alt='product image'>
            </td>

            <td>
                <a href='view.php?id=$this->productId'>
                    $this->productId
                </a>
            </td>

            <td>$this->productName</td>

            <td>$this->category</td>

            <td>$$this->price</td>

            <td>$this->quantity</td>

            <td>$actions</td>

        </tr>

        ";

        return $output;

    }

    public function displayProductPage() {

        $thumb1Class = "";
        $thumb2Class = "";
        $thumb3Class = "";

        if($this->photo1 == $this->defaultPhoto){
            $thumb1Class = "active-thumb";
        }

        if($this->photo2 == $this->defaultPhoto){
            $thumb2Class = "active-thumb";
        }

        if($this->photo3 == $this->defaultPhoto){
            $thumb3Class = "active-thumb";
        }

        $priceFormatted = number_format((float)$this->price, 2);

        $output = "

        <main>

            <section class='product-detail-card'>

                <h2>$this->productName</h2>

                <img src='images/$this->defaultPhoto'
                class='detail-main-img'
                alt='product image'>

                <section class='thumbnail-row detail-thumbnails'>

                    <a href='view.php?id=$this->productId'>
                        <img src='images/$this->photo1'
                        class='$thumb1Class'
                        alt='product photo'>
                    </a>

                    <a href='view.php?id=$this->productId'>
                        <img src='images/$this->photo2'
                        class='$thumb2Class'
                        alt='product photo'>
                    </a>

                    <a href='view.php?id=$this->productId'>
                        <img src='images/$this->photo3'
                        class='$thumb3Class'
                        alt='product photo'>
                    </a>

                </section>

                <p><strong>Category:</strong> $this->category</p>

                <p><strong>Description:</strong>
                $this->description</p>

                <p><strong>Price:</strong> $$priceFormatted</p>

                <p><strong>Quantity:</strong> $this->quantity</p>

                <p><strong>Rating:</strong> $this->rating / 5</p>

                <p>
                    <a href='products.php'>
                        Back To Products
                    </a>

                    <a href='add_to_basket.php?id=$this->productId'>
                        Add To Basket
                    </a>
                </p>

            </section>

        </main>

        ";

        return $output;

    }

}

?>