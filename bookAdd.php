<?php
session_start();
$categories =$_SESSION['categories'];

$db = new mysqli('127.0.0.1', 'root', '0123456LYJ', 'bookstore');

$title = $author = $ISBN = $price =  '';
$categoryErr = $titleErr = $authorErr = $ISBNErr = $priceErr = $imageErr = $readNowErr ='';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['category'])){
        $categoryErr = 'Category is required';
        $error= '1';
    }

    if(empty($_POST['title'])){
        $titleErr = 'The title of the book is required';
        $error= '1';
    }else{
        $title = $_POST['title'];
    }

    if(empty($_POST['author'])){
        $authorErr = 'The author of the book is required';
        $error= '1';
    }else{
        $author = $_POST['author'];
    }

    if(empty($_POST['ISBN'])){
        $ISBNErr = 'The ISBN of the book is required';
        $error= '1';
    }else{
        $ISBN = $_POST['ISBN'];
    }

    if(empty($_POST['price'])){
        $priceErr = 'The price of the book is required';
        $error= '1';
    }else{
        $price = $_POST['price'];
    }

    if(empty($_POST['readNow'])){
        $readNow = '0';
    }else{
        $readNow = $_POST['readNow'];
    }
}


if(count($_POST)!=0 && empty($error)){
    $titleLowerCase = strtolower($_POST['title']);

    $query = "INSERT INTO book(categoryId, title, author, ISBN, price, image, readNow) 
                VALUES(?,?,?,?,?,?,?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('isssssi',$_POST['category'],$_POST['title'],$_POST['author'],$_POST['ISBN'],$_POST['price'],$titleLowerCase,$readNow);
    $stmt->execute();

//    $file = strtolower($_POST['image']).".jpg.";
//    $path = "images/categories/".$file;
//    move_uploaded_file(($_FILES['image']['tmp_name']),$path);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book: To The Moon</title>
    <meta name="viewpoint" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/bookAdd.css">
</head>
<body>

<?php
include('header.php');
?>

<section>
    <?php
        if(count($_POST)!=0 && empty($error)) {
            echo "<h3>Book " . $title . " has been added!</h3>";
        }
    ?>
    <h3>Enter information for a new book</h3>
    <div>
        <table id="bookTable">
            <form action="bookAdd.php" method="post" enctype="multipart/form-data">
            <tr>
                <td><label for="category">Select Book Category</label></td>
                <td>
                    <select id="category" name="category">
                    <option value="">Select</option>
                    <?php
                    $select_value = isset($_POST['category']) ? $_POST['category'] : '';
                    foreach ($categories as $category) {
                        if ($select_value == $category['categoryId']) {
                            echo "<option value=" . $category['categoryId'] . " selected='selected'>" . $category['categoryName'] . "</option>";
                            $_SESSION['categoryId'] = $category['categoryId'];
                        } else {
                            echo "<option value=" . $category['categoryId'] . ">" . $category['categoryName'] . "</option>";
                        }
                    }
                    ?>
                    </select>
                </td>
                <td><span class="checkoutFormErrors"><?php echo $categoryErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="title">Enter The Title</label></td>
                <td><input type="text" name="title" placeholder="Born to Run" value="<?php echo ($title);?>"></td>
                <td><span class="checkoutFormErrors"><?php echo $titleErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="author">Enter The Author</label></td>
                <td><input type="text" name="author" placeholder="Christopher McDougall" value="<?php echo $author;?>"></td>
                <td><span class="checkoutFormErrors"><?php echo $authorErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="author">Enter The ISBN</label></td>
                <td><input type="text" name="ISBN" placeholder="9780307279187" value="<?php echo $ISBN;?>"></td>
                <td><span class="checkoutFormErrors"><?php echo $ISBNErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="author">Enter The Price</label></td>
                <td><input type="text" name="price" placeholder="10.41" value="<?php echo $price;?>"></td>
                <td><span class="checkoutFormErrors"><?php echo $priceErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="author">Read Now?</label></td>
                <td><input type="number" name="readNow" min="0" max="1" value="<?php echo $readNow;?>"></td>
                <td><span class="checkoutFormErrors"><?php echo $readNowErr; ?></span></td>
            </tr>
            <tr>
                <td><label for="image">Upload Book Image (jpg file)</label></td>
                <td><input type="file" id="image" name="image"></td>
                <td><span class="checkoutFormErrors"><?php echo $imageErr; ?></span></td>
            </tr>
        </table>
        <div id="buttons">
            <a href="bookChange.php" class="submit">Back to Admin</a>
            <input type="submit" class="submit" name="submit" value="submit">
        </div>
        </form>
    </div>
</section>

<?php
include('footer.php');
?>

</body>
</html>
