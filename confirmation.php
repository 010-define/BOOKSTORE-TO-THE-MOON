<!doctype html>
<html lang="en">
<head>
    <title>Confirmation: To The Moon</title>

    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/confirmation.css"/>
</head>
<body>

<?php
session_start();
$categories =$_SESSION['categories'];

session_destroy();

if (basename($_SERVER['PHP_SELF']) == "index.php") {
    $db = new mysqli('localhost', 'root', '0123456LYJ', 'bookstore');
    $query = "SELECT * from category";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($category = $result -> fetch_assoc()) {
        if (isset($categories)) {
            $categories[count($categories)] = $category;
        }else{
            $categories = array($category);
        }
    }

    $_SESSION['categories'] = $categories;
}
?>
<?php
include ('header.php');
?>
<main>
    <?php
    if(empty($_SESSION['cartQuantity'])){
        echo "<br><h1>There is nothing to check out</h1><br>";
    }else{
        echo "    <h1>Thank you for your order! </h1>
                  <h1>Have a great day!</h1>";
    }
    echo "<p><a href='index.php' class='commandButton'>Return to Home</a></p>";
    ?>

</main>

<?php
include('footer.php');
?>

</body>
</html>
