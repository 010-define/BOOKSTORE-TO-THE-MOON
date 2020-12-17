<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login: To The Moon</title>
    <meta name="viewpoint" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<?php
session_start();
$categories =$_SESSION['categories'];

$db = new mysqli('localhost', 'root', '0123456LYJ', 'bookstore');

$userNameErr = $userPasswordErr = "";
$userName = $userPassword = "";

function getPassword($db, $userName)
{
    $query = "SELECT password FROM bookstore.user WHERE userName= ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    $password = $result->fetch_assoc();
    return $password;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["userName"])) {
        $userNameErr = "User Name is required.";
    } else {
        $userName = $_POST["userName"];
    }

    $userPassword = $_POST["userPassword"];
    $loginPassword = getPassword($db, $userName);

    if (empty($loginPassword)) {
        $userNameErr = "User Name is not existed.";
    } else {
        if ($userPassword == $loginPassword['password']) {
            $_SESSION['userName'] = $userName;
            if ($userName == 'admin') {
                header("Location:bookChange.php");
            } else {
                header("Location:index.php");
            }
        } elseif (empty($_POST["userPassword"])) {
            $userNameErr = "User Password is required.";
        } else {
            $userPasswordErr = "Your password is wrong.";
        }
    }
}
?>

<?php
    include ('header.php');
?>

<section>
    <form id="loginInfo" name="loginInfo" action="login.php" method="post">
        <table>
            <tr class="trStyle">
                <td class="labelStyle">User Name:</td>
                <td>
                    <label>
                        <input type="text" name="userName" value="<?php echo $userName ?>">
                    </label>
                </td>
                <td class="loginErr">
                    <?php echo $userNameErr; ?>
                </td>
            </tr>
            <tr class="trStyle">
                <td class="labelStyle">Password:</td>
                <td>
                    <label>
                        <input type="password" name="userPassword" value="<?php echo $userPassword ?>">
                    </label>
                </td>
                <td class="loginErr">
                    <?php echo $userPasswordErr; ?>
                </td>
            </tr>
            <tr class="trStyle">
                <td></td>
                <td>
                    <input type="submit" name="submit" id="loginButton">
                </td>
            </tr>
        </table>
    </form>
</section>

<?php
include('footer.php');
?>

</body>
</html>
