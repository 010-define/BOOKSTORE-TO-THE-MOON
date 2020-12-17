<!DOCTYPE html>

<html lang="en">
<head>
    <title>Home: To The Moon</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<?php
    session_start();
    unset($_SESSION['selected']);
    include ('header.php');
?>
<section>
        <div id="welcome">
            <h2>WELCOME</h2><br>
            <h3>TO THE MOON BOOKSTORE</h3><br>
            <p>If you love travelling but have no time for it, come and travel in reading.</p>
            <p>If you plan to have a trip and need some information, we provide something for your reference.</p>
            <p>We have the most specific book selection online and we provide the following four categories:</p><br>
        </div>
        <div id="category">
            <div class="categoryRow">
                <?php
                foreach ($categories as $category){
                    echo "<div class='categoryColumn'>";
                    echo "<a href='category.php?page=" . $category['categoryId'] . "'><img src='images/categories/" . $category['image'] . ".jpg' alt='" . $category['categoryId'] . "' class='avatar'></a>";
                    echo "<p><a href='category.php?page=" . $category['categoryId'] . "'> ". $category['categoryName'] ." </a></p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
</section>
<?php
include ('footer.php');
?>
</body>
</html>