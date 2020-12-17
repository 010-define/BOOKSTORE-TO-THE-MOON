<!doctype html>

<html lang="en">
<head>
    <title>Cart: To The Moon</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
</head>

<body>
<?php
// Start the session here
session_start();

$categories = $_SESSION['categories'];

if (isset($_GET['action'])) {
    unset($_SESSION['cartItems']);
    unset($_SESSION['cartQuantity']);
}

if(isset($_SESSION['selected'])){
    $selected = $_SESSION['selected'];
}

if(isset($_SESSION['cartItems'])){
    $cartItems = $_SESSION['cartItems'];
}

function getBookDetails($db, $bookId) {
    $query = "SELECT bookId, title, price
                        FROM book
                        WHERE book.bookId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $bookId);

    $stmt->execute();
    $result = $stmt->get_result();
    $bookDetails = $result->fetch_assoc();
    return $bookDetails;
}

$db = new mysqli('localhost', 'root', '0123456LYJ', 'bookstore');
$cartPrice = array('');
$totalQuantity = array('');

if(empty($cartItems)){
    if(isset($_GET['bookId'])){
        $cartItem = getBookDetails($db, $_GET['bookId']);
        $cartItem['quantity'] = 1;
        $cartItems[$_GET['bookId']] = $cartItem;
        $_SESSION['cartItems'] = $cartItems;
    }
}else{
    if(isset($_POST['quantity'])) {
        foreach($cartItems as &$cartItem){
            if($_POST['bookId'] == $cartItem['bookId']){
                if($_POST['quantity'] == 0){
                    unset($cartPrice[$cartItem['bookId']]);
                    unset($cartItems[$cartItem['bookId']]);
                }else{
                    $cartItem['quantity'] = $_POST['quantity'];
                }
            }
        }
    }else{
        if (isset($_GET['bookId'])) {
            if (isset($cartItems[$_GET['bookId']])) {
                $cartItems[$_GET['bookId']]['quantity']++;
            } else {
                $cartItem = getBookDetails($db, $_GET['bookId']);
                $cartItem['quantity'] = 1;
                $cartItems[$_GET['bookId']] = $cartItem;
            }
        }
        $_SESSION['cartItems'] = $cartItems;
    }
}

if(isset($cartItems)){
    foreach ($cartItems as &$cartItem){
        $totalPrice = $cartItem['price'] * $cartItem['quantity'];
        $cartPrice[$cartItem['bookId']] = $totalPrice;
        $totalQuantity[$cartItem['bookId']] = $cartItem['quantity'];
    }
    $_SESSION['cartQuantity'] = array_sum($totalQuantity);
    $_SESSION['cartPrice'] = array_sum($cartPrice);
    $_SESSION['cartItems'] = $cartItems;

    if(empty($_SESSION['cartItems'])){
        unset($_SESSION['cartItems']);
    }
}

?>
<main>
    <?php
    include('header.php');
    ?>
    <h2>Your Shopping Cart</h2>

    <section id="main">
        <section id="left">
            <a href="cart.php?action=clear" class="navigateBtn" action="clearCart">
                <div class="commandButton">Clear Cart</div>
            </a><br>
            <?php
            if (isset($selected)) {
                echo "<a href='category.php?page= " . $selected . "'  class='navigateBtn'><div class='commandButton'>Continue Shopping</div></a><br>";
            } else {
                echo '<a href="index.php" class="navigateBtn"><div class="commandButton">Continue Shopping</div></a><br>';
            }
            ?>
            <a href="checkout.php" class="navigateBtn">
                <div class="commandButton">Proceed to Checkout</div>
            </a><br>
        </section>

        <section id="right">
            <?php
            if (isset($_SESSION['cartItems']) || isset($_GET['bookId'])) {
                ?>
                <section id="topRight">
                    <table>
                        <tr>
                            <th class="titleColumn" id="rowTitle">Title</th>
                            <th class="quantityColumn">Quantity</th>
                            <th class="priceColumn">Price</th>
                            <th class="totalColumn">Total Price</th>
                        </tr>
                        <?php
                        foreach ($cartItems as &$cartItem) {
                            echo "
                    <tr>
                        <td class='titleColumn'> " . $cartItem['title'] . " </td>
                        <td class='quantityColumn'>
                            <form action = 'cart.php' method = 'post'>
                                <label for='quantity'>
                                <input type = 'number' name = 'quantity' min = '0' max='20' value=" . $cartItem['quantity'] . ">
                                <input type='hidden' name='bookId' value = ".$cartItem['bookId'].">
                                </label>
                                <input type = 'submit' name = 'update' value='update' id='updateButton'>
                            </form>
                        </td>
                        <td class='priceColumn'> $ " . $cartItem['price'] . "</td>
                        <td class='totalColumn'> $ " . $cartPrice[$cartItem['bookId']]  . "</td>
                    </tr>";
                        }
                        ?>
                    </table>

                </section>

                <section id="bottomRight">
                    <?php
                    echo "<h3 id='bottomLine'>You have " . array_sum($totalQuantity) . "  item in the cart</h3>";
                    echo "<h3>Cart Total: $ " . array_sum($cartPrice) . " </h3>";
                    ?>
                </section>
                <?php
            } else {
                echo "<p id='empty'>Your Shopping Cart is Empty</p>";
            }
            ?>
        </section>
    </section>
    <?php
    include('footer.php')
    ?>
</main>
</body>