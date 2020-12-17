<!doctype html>
<html lang="en">
<head>
    <title>Checkout: To The Moon</title>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/checkout.css"/>
</head>
<body>
<?php
session_start();
$categories = $_SESSION['categories'];
$bookPrice = $_SESSION['cartPrice'];
$shippingFee = '5.00';
$totalPrice = $bookPrice + $shippingFee;

if (empty($_SESSION['cartQuantity'])) {
    header("Location: confirmation.php");
    exit;
}

function getCustomer($db, $userName)
{
    $query = "SELECT * FROM bookstore.customer WHERE email= ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    return $customer;
}

$name = $address = $phone = $email = $card = "";
$nameErr = $addressErr = $phoneErr = $emailErr = $cardErr = $expDateErr = "";
$error = "0";

if(isset($_SESSION['userName'])){
    $db = new mysqli('localhost', 'root', '0123456LYJ', 'bookstore');
    $customerInfo = getCustomer($db, $_SESSION['userName']);
}elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $error = "1";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
        $error = "1";
    } else {
        $address = $_POST["address"];
    }

    if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST["phone"])) {
        $phone = $_POST["phone"];
    } else {
        if (empty($_POST["phone"])) {
            $phone = $_POST["phone"];
            $phoneErr = "Phone is required";
            $error = "1";
        } else {
            $phone = $_POST["phone"];
            $phoneErr = "Invalid phone format";
            $error = "1";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $error = "1";
    } else {
        $email = $_POST["email"];
    }

}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($_POST["card"])) {
        $cardErr = "Card Number is required";
        $error = "1";
    } else {
        if (validateCard($_POST["card"]) == false) {
            $cardErr = "Invalid card number";
            $error = "1";
        } else {
            $card = $_POST["card"];
        }
    }

    if ($_POST["expYear"] == "0") {
        $expDateErr = "Exp. Date is required";
        $error = "1";
    } else {
        if ($_POST["expMonth"] == "0") {
            $expDateErr = "Exp. Date is required";
            $error = "1";
        }
    }

    if ($error == "0") {
        header("Location: confirmation.php");
        exit;
    }
}

function validateCard($cardNumber)
{
    $cardNumber = preg_replace(" /\D|\s/ ", "", $cardNumber);
    $cardLength = strlen($cardNumber);
    if ($cardLength != 0) {
        $parity = $cardLength % 2;
        $sum = 0;
        for ($i = 0; $i < $cardLength; $i++) {
            $digit = $cardNumber [$i];
            if ($i % 2 == $parity) $digit = $digit * 2;
            if ($digit > 9) $digit = $digit - 9;
            $sum = $sum + $digit;
        }
        $valid = ($sum % 10 == 0);
        return $valid;
    }
    return false;
}

?>

<main>
<?php
    include ('header.php');
?>
<section id="main">
<section id="left">
    <h2>Checkout</h2>
    <p id="formTitleText">In order to purchase the items in your shopping cart, please provide the following information:</p>
    <table id="checkoutTable">
    <form method="post" action="checkout.php" name="form">
    <tr>
        <td>Customer Name:</td>
        <?php
            if(isset($customerInfo)){
                echo "<td>".$customerInfo['customerName']."</td>";
            }else{
                echo"<td><label><input type='text' name='name' value= $name ></label></td>
                     <td><span class='checkoutFormErrors'> $nameErr </span></td>";
            }
        ?>
    </tr>
    <tr>
       <td>Address:</td>
        <?php
        if(isset($customerInfo)){
            echo "<td>".$customerInfo['address']."</td>";
        }else{
            echo"<td><label><input type='text' name='address' value= $address ></label></td>
                 <td><span class='checkoutFormErrors'> $addressErr </span></td>";
        }
        ?>
    </tr>
    <tr>
        <td>Phone:</td>
        <?php
        if(isset($customerInfo)){
            echo "<td>".$customerInfo['phone']."</td>";
        }else{
            echo"<td><label><input type='text' name='phone' placeholder='000-000-0000' value= $phone ></label></td>
                 <td><span class='checkoutFormErrors'> $phoneErr </span></td>";
        }
        ?>
    </tr>
    <tr>
        <td>Email:</td>
        <?php
        if(isset($customerInfo)){
            echo "<td>".$customerInfo['email']."</td>";
        }else{
            echo"<td><label><input type='email' name='email' value= $email></label></td>
                 <td><span class='checkoutFormErrors'> $emailErr </span></td>";
        }
        ?>
    </tr>
    <tr>
       <td>Credit Card Number:</td>
       <td><label><input type="text" name="card" value="<?php echo ($card);?>"></label></td>
       <td><span class="checkoutFormErrors"><?php echo $cardErr; ?></span></td>
    </tr>
    <tr>
       <td>Exp. Date:</td>
       <td><label id="expDate">
               <select name="expMonth">
                   <option value="0">Select</option>
                   <?php
                   $select_value = isset($_POST['expMonth']) ? $_POST['expMonth'] : '';
                   $month = array("January"=>"1", "February"=>"2" , "March"=>"3" , "April"=>"4", "May"=>"5", "June"=>"6", "July"=>"7", "August"=>"8","September"=>"9", "October"=>"10", "November"=> "11", "December"=>"12");
                   foreach ($month as $fullName =>$num){
                       if ($select_value == $num){
                           echo"<option value=".$num." selected='selected'>".$fullName."</option>";
                       }else {
                           echo "<option value=" . $num . " >" . $fullName . "</option>";
                       }
                   }
                   ?>
               </select>
               <select name="expYear">
                   <option value="0">Select</option>
                   <?php
                   $select_value = isset($_POST['expYear']) ? $_POST['expYear'] : '';
                   for ($x = 0; $x <= 10; $x++) {
                       if($select_value == date("Y",strtotime("+ $x year"))){
                           echo"<option value=".date("Y",strtotime("+ $x year"))." selected='selected'>".date("Y",strtotime("+ $x year"))."</option>";
                       }else{
                           echo"<option value=".date("Y",strtotime("+ $x year")).">".date("Y",strtotime("+ $x year"))."</option>";
                       }
                   }
                   ?>
               </select></label></td>
       <td><span class="checkoutFormErrors"><?php echo $expDateErr; ?></span></td>

    </tr>
    <tr>
       <td></td>
       <td><label><input type="submit" name="submit" value="submit" id="submitButton"></label></td>
    </tr>
    </form>
    </table>
    </section>
<section id="right">
    <section id="checkoutSummary">
        <ul>
            <li>Next day delivery is guaranteed.</li>
            <li>A $ <?php echo"$shippingFee" ?> shipping fee is applied to all orders</li>
        </ul>
        <div id="checkoutTotals">
            <table>
                <tr>
                    <td>Cart Subtotal</td>
                    <td>
                        <?php
                        echo "$ ".$bookPrice."";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Shipping Fee</td>
                    <td>
                        <?php
                        echo "$ ".$shippingFee."";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="total">Total</td>
                    <td class="total">
                        <?php
                        echo "$ ".$totalPrice." ";
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </section>
    </section>
</section>
</main>
<?php
include('footer.php')
?>
</body>
</html>
