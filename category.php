<!doctype html>
<html lang="en">
<head>
    <title>Category: To The Moon</title>
    <meta charset="utf-8">
    <meta name="categoryAdventure" content="The Adventure In To The Moon">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/category.css">
</head>

<body>
<?php
// Start the session here
session_start();

$categories = $_SESSION['categories'];

if (isset($_GET['page'])) {
    $categoryIdNow = $_GET['page'];
} else {
    $categoryIdNow = 1;
};

function getBooks($db, $selectedCategory) {
    $query = "SELECT bookId, title, author, ISBN, price, image, readNow
                        FROM book
                        WHERE book.categoryId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $selectedCategory);

    $stmt->execute();
    $result = $stmt->get_result();
    $bookArray = $result->fetch_all( MYSQLI_ASSOC);
    return $bookArray;
}

$db = new mysqli('localhost', 'root', '0123456LYJ', 'bookstore');
$books = getBooks($db,$categoryIdNow);

$_SESSION['books'] = $books;



?>
<main>
    <?php
    include 'header.php';
    ?>
    <section>
        <div class="main">
            <article class="mainLeft">
                <div id="btn-group">
                    <?php
                    foreach ($categories as $category) {
                        if ($category['categoryId'] == $categoryIdNow) {
                            $_SESSION['selected'] = $category['categoryId'];
                            echo "<a  href='category.php?page=" . $category['categoryId'] . "'><button class= 'button" . $category['categoryId'] . "'  id='btnSelected'>" . $category['categoryName'] . "</button></a>";
                        } else {
                            echo "<a  href='category.php?page=" . $category['categoryId'] . "'><button class= 'button" . $category['categoryId'] . "'>" . $category['categoryName'] . "</button></a>";
                        }
                    }
                    ?>
                </div>
            </article>
            <article class="mainRight">
                <?php
                foreach ($books as $book) {
                    echo "<div class='categoryRow'>";
                        echo "
                        <div class = 'categoryLeft'>
                             <img src='images/categories/" . $book['image'] . ".jpg' alt='" . $book['title'] . "'>
                        </div>
                        <div class = 'categoryMiddle'>
                            <h3>" . $book['title'] . "</h3>
                            <p class='author'>" . $book['author'] . "</p>
                            <p class='isbn'>" . $book['ISBN'] . "</p>
                            <p class='price'>$" . $book['price'] . "</p>
                        </div>";
                        if ($book['readNow'] == 1) {
                            echo "
                            <div class ='categoryRight'>
                                <a href='cart.php?bookId=" . $book['bookId'] . "' class='addToCart'>Add to cart</a><br>
                                <a href='#' class='addToCart'>Read now</a>
                            </div>";
                        } else {
                            echo "
                            <div class ='categoryRight'>
                                <a href='cart.php?bookId=" . $book['bookId'] . "' class='addToCart'>Add to cart</a>
                            </div>";
                        }
                    echo "</div>";
                }
                ?>
            </article>
        </div>
    </section>
    <?php
    include 'footer.php';
    ?>
</main>
</body>
</html>