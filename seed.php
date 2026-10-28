<?php

require_once("dbconfig.inc.php");

$sql = "INSERT INTO products
(product_name, category, description,
price, quantity, rating,
photo1, photo2, photo3, default_photo)

VALUES

(
'Palestinian Embroidered Cushion',
'Embroidery',
'Traditional handmade Palestinian cushion.',
35.00,
10,
5,
'cushion1.jpeg',
'cushion2.jpeg',
'cushion3.jpeg',
'cushion1.jpeg'
),

('Olive Wood Spoon',
'Kitchen',
'Handmade Palestinian olive wood spoon.',
25.00,
10,
5,
'IMG_5079.JPG',
'IMG_5079.JPG',
'IMG_5079.JPG',
'IMG_5079.JPG'),

('Ceramic Plate',
'Ceramics',
'Decorated Palestinian ceramic plate.',
40.00,
8,
5,
'IMG-4672.WEBP',
'IMG-4672.WEBP',
'IMG-4672.WEBP',
'IMG-4672.WEBP');
";
$stmt = $pdo->prepare($sql);

$stmt->execute();

echo "Products Added Successfully";

?>