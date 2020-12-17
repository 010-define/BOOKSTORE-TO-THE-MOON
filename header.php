<?php
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

<div id = "header1">
    <div id="logo">
        <a href="index.php">
            <img src="images/logo.png" alt="To The Moon logo" >
        </a>
        <h1>TO THE MOON</h1>
    </div>
    <div id="header1Right">
        <div id="dropdown">
            <button id="dropdownSelect">Category</button>
            <div id="dropdownContent">
                <?php
                foreach ($categories as $category){
                    echo "<p><a href='category.php?page=" . $category['categoryId'] . "'>" . $category['categoryName'] ."</a></p>";
                    echo "<br/>";
                }
                ?>
            </div>
        </div>
        <?php
            if (isset($_GET['action'])){
                if ($_GET['action']=="logout"){
                    unset($_SESSION['userName']);
                    header("Location:login.php");
                }
            }else{
                if (empty($_SESSION['userName'])){
                    echo "<a href='login.php' class='loginButton'>Log in</a>";
                }
                else{
                    echo "
                    <a href='login.php?action=logout' class='loginButton'>Log out</a>
                    ";
                }
            }
        ?>
    </div>
</div>
<div id="header2">
<!--    --><?php
/*    if(empty($_GET['action']) && isset($_SESSION['userName'])) {
        echo "<p id='loginSuccess'>Welcome!  " . $_SESSION['userName'] . "</p> ";
    }
    */?>
    <form id="searchBoxForm">
        <label for="searchBox"></label><input id="searchBox" type="text" value="Search">
        <input id="searchIcon" type="image"
               src="images/icon/search.png" alt="search icon">
    </form>
    <div id="cart">
        <div id="cartIcon"><a href="cart.php">
            <img src="images/icon/shopping_cart.png" alt="shopping cart icon"></a>
        </div>

        <div id="cartCount">
            <?php
                if (isset($_SESSION['cartQuantity'])){

                    echo "".$_SESSION['cartQuantity']." items ";
                }else{
                    echo "0 items";
                }
            ?>
        </div>
    </div>
</div>