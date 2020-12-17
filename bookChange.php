<?php
session_start();
$categories =$_SESSION['categories'];

$bookInfos=array();
$db = new mysqli('127.0.0.1', 'root', '0123456LYJ', 'bookstore');

function getbookInfo($db){
$query = "SELECT book.bookId, book.title, book.author, book.ISBN, book.price, category.categoryName FROM book join category on book.categoryId = category.categoryId order by book.bookId";
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$bookInfos = $result->fetch_all(MYSQLI_ASSOC);
return $bookInfos;
}

$bookInfos = getbookInfo($db);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['bookChange'] == 'delete'){
        foreach ($bookInfos as $bookInfo) {
            if ($bookInfo['bookId'] == $_POST['bookId']) {
                mysqli_query($db, "DELETE FROM book 
    WHERE book.bookId=" . $bookInfo['bookId'] . "");
            }
        }
    }elseif($_POST['bookChange'] == 'update') {
        foreach ($bookInfos as $bookInfo) {
            if ($bookInfo['bookId'] == $_POST['bookId']) {
                $bookInfos[$bookInfo['bookId'] - 1]['price'] = $_POST['bookPrice'];
                mysqli_query($db, "UPDATE book SET book.price=" . $_POST['bookPrice'] . "
    WHERE book.bookId=" . $bookInfo['bookId'] . "");
            }
        }
    }
    $bookInfos = getbookInfo($db);
}

if (!empty($bookInfos)){
    $_SESSION['bookInfos']=$bookInfos;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: To The Moon</title>
    <meta name="viewpoint" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php
include('header.php');
?>

<section>
    <table id="bookAdmin">
        <tr>
            <th class="titleStyle">Book Id</th>
            <th class="titleStyle">Title</th>
            <th class="titleStyle">Author</th>
            <th class="titleStyle">Category</th>
            <th class="titleStyle">ISBN</th>
            <th class="titleStyle">Price</th>
            <th class="titleStyle">Delete</th>
        </tr>
        <?php
        $bookInfos = $_SESSION['bookInfos'];
        foreach ($bookInfos as $bookInfo) {
            echo
                "<tr class='rowStyle'>
                <td id='bookId'>" . $bookInfo['bookId'] . "</td>    
                <td>" . $bookInfo["title"] . "</td>
                <td>" . $bookInfo["author"] . "</td>
                <td id='category'>" . $bookInfo["categoryName"] . "</td>
                <td id='ISBN'>" . $bookInfo["ISBN"] . "</td>
                <td id='price'>
                    <form action='bookChange.php' method='post'>
                        <input type='hidden' name='bookId' value='" . $bookInfo["bookId"] . "'>
                        <label>
                        <input class='inputStyle' type='number' name='bookPrice' min='0.00' step='0.01' value='" . $bookInfo["price"] . "'>
                        </label>
                        <input class='submitStyle' type='submit' name='bookChange' value='update'>
                    
                </td>
                <td><input class='submitStyle' type='submit' name='bookChange' value='delete'></td>
                    </form>
                </tr>";
        }
        ?>
    </table>
    <div id="add">
        <a href="bookAdd.php" id="bookAdd">Add a Book</a>
    </div>
</section>

<?php
include('footer.php');
?>

</body>
</html>
