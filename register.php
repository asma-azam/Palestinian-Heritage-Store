<?php

require_once("dbconfig.inc.php");
require_once("header.inc.php");

$errors = array();
$fieldErrors = array();
$success = "";

$first_name = "";
$last_name = "";
$email = "";
$mobile = "";
$dob = "";
$flat_no = "";
$street = "";
$city = "";
$country = "";
$postal_code = "";
$role = "Customer";

function errorClass($field, $fieldErrors){
    if(isset($fieldErrors[$field])){
        return " class='invalid'";
    }
    return "";
}

function oldValue($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $user_id = rand(1000000000, 9999999999);

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $dob = trim($_POST["dob"]);
    $flat_no = trim($_POST["flat_no"]);
    $street = trim($_POST["street"]);
    $city = trim($_POST["city"]);
    $country = trim($_POST["country"]);
    $postal_code = trim($_POST["postal_code"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = $_POST["role"];

    if($first_name == ""){
        $errors[] = "First name is required.";
        $fieldErrors["first_name"] = true;
    }

    if($last_name == ""){
        $errors[] = "Last name is required.";
        $fieldErrors["last_name"] = true;
    }

    if($email == ""){
        $errors[] = "Email is required.";
        $fieldErrors["email"] = true;
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format.";
        $fieldErrors["email"] = true;
    }

    if($mobile == ""){
        $errors[] = "Mobile is required.";
        $fieldErrors["mobile"] = true;
    }

    if($dob == ""){
        $errors[] = "Date of birth is required.";
        $fieldErrors["dob"] = true;
    }

    if($street == ""){
        $errors[] = "Street is required.";
        $fieldErrors["street"] = true;
    }

    if($city == ""){
        $errors[] = "City is required.";
        $fieldErrors["city"] = true;
    }

    if($country == ""){
        $errors[] = "Country is required.";
        $fieldErrors["country"] = true;
    }

    if($postal_code == ""){
        $errors[] = "Postal code is required.";
        $fieldErrors["postal_code"] = true;
    }
    else if(!ctype_digit($postal_code) || strlen($postal_code) != 6){
        $errors[] = "Postal code must be exactly 6 digits.";
        $fieldErrors["postal_code"] = true;
    }

    if($password == ""){
        $errors[] = "Password is required.";
        $fieldErrors["password"] = true;
    }

    if($password != $confirm_password){
        $errors[] = "Passwords do not match.";
        $fieldErrors["password"] = true;
        $fieldErrors["confirm_password"] = true;
    }

    if($dob != ""){
        $birth_year = date("Y", strtotime($dob));
        $current_year = date("Y");
        $age = $current_year - $birth_year;

        if($age < 18){
            $errors[] = "User must be at least 18 years old.";
            $fieldErrors["dob"] = true;
        }
    }

    if($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)){
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $errors[] = "Email already exists.";
            $fieldErrors["email"] = true;
        }
    }

    if(count($errors) == 0){

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
        (user_id, first_name, last_name, email, mobile, dob, flat_no, street, city, country, postal_code, password, role)
        VALUES
        (:user_id, :first_name, :last_name, :email, :mobile, :dob, :flat_no, :street, :city, :country, :postal_code, :password, :role)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id);
        $stmt->bindValue(":first_name", $first_name);
        $stmt->bindValue(":last_name", $last_name);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":mobile", $mobile);
        $stmt->bindValue(":dob", $dob);
        $stmt->bindValue(":flat_no", $flat_no);
        $stmt->bindValue(":street", $street);
        $stmt->bindValue(":city", $city);
        $stmt->bindValue(":country", $country);
        $stmt->bindValue(":postal_code", $postal_code);
        $stmt->bindValue(":password", $hashed_password);
        $stmt->bindValue(":role", $role);

        $stmt->execute();

        $success = "Registration successful. Your User ID is: " . $user_id;
    }
}

?>

<main>

<h2>Register</h2>

<?php

foreach($errors as $error){
    echo "<p class='error-message'>$error</p>";
}

if($success != ""){
    echo "<p class='success-message'>$success</p>";
    echo "<p><a href='login.php'>Go to Login</a></p>";
}

?>

<form method="post" action="register.php" novalidate>

<fieldset>
<legend>User Information</legend>

<p>First Name: 
    <input type="text" name="first_name" value="<?php echo oldValue($first_name); ?>"<?php echo errorClass("first_name", $fieldErrors); ?> required></p>
<p>Last Name: 
    <input type="text" name="last_name" value="<?php echo oldValue($last_name); ?>"<?php echo errorClass("last_name", $fieldErrors); ?> required></p>
<p>Email: 
    <input type="email" name="email" value="<?php echo oldValue($email); ?>"<?php echo errorClass("email", $fieldErrors); ?> required></p>
<p>Mobile:
     <input type="text" name="mobile" value="<?php echo oldValue($mobile); ?>"<?php echo errorClass("mobile", $fieldErrors); ?> required></p>
<p>Date of Birth: 
    <input type="date" name="dob" value="<?php echo oldValue($dob); ?>"<?php echo errorClass("dob", $fieldErrors); ?> required></p>
<p>Flat No:
     <input type="text" name="flat_no" value="<?php echo oldValue($flat_no); ?>"></p>
<p>Street: 
    <input type="text" name="street" value="<?php echo oldValue($street); ?>"<?php echo errorClass("street", $fieldErrors); ?> required></p>
<p>City: 
    <input type="text" name="city" value="<?php echo oldValue($city); ?>"<?php echo errorClass("city", $fieldErrors); ?> required></p>
<p>Country:
     <input type="text" name="country" value="<?php echo oldValue($country); ?>"<?php echo errorClass("country", $fieldErrors); ?> required></p>
<p>Postal Code: 
    <input type="text" name="postal_code" value="<?php echo oldValue($postal_code); ?>"<?php echo errorClass("postal_code", $fieldErrors); ?> required></p>
<p>Password: 
    <input type="password" name="password"<?php echo errorClass("password", $fieldErrors); ?> required></p>
<p>Confirm Password: 
    <input type="password" name="confirm_password"<?php echo errorClass("confirm_password", $fieldErrors); ?> required></p>

<p>
Role:
<input type="radio" name="role" value="Customer" <?php if($role == "Customer") echo "checked"; ?>> Customer
<input type="radio" name="role" value="Employee" <?php if($role == "Employee") echo "checked"; ?>> Employee
</p>

<p><input type="submit" value="Register"></p>

</fieldset>

</form>

</main>

<?php
require_once("footer.inc.php");
?>
