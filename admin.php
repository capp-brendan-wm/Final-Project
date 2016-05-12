<?php

$username = 'kingtailor';
$password = 'keyanpleb';
if (!isset($_SERVER['PHP_AUTH_USER']) ||
    !isset($_SERVER['PHP_AUTH_PW']) ||
    ($_SERVER['PHP_AUTH_USER'] != $username) || ($_SERVER['PHP_AUTH_PW'] != $password)) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm= "Please enter the admin username and password"');
    exit('<h2> You must be an administrator to access this page. </h2>');
}



session_start();
error_reporting(0); // disables all error messages.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crowned Tailor</title>
    <link rel="icon" href="images/CT-icon.png">
    <link rel="stylesheet" type="text/css" href="primaryStyle.css">
</head>
<body>
<!--###  body start  ###-->
<!--# # # # # # # # # #-->
<!--#   HEADER DIV    #-->
<!--# # # # # # # # # #-->
<header>
    <div id="head">
        <div id="siteName">
            <a href="index.php"><img src="images/CT-LogoMain.png"></a>
        </div>
        <br>
        <div id="signIn">
            <?php
            if($_COOKIE['logUser'] != null){
                $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

                $query = "SELECT * FROM users WHERE username = :username1";
                $stmt = $dbh->prepare($query);
                $stmt->execute(array(
                    'username1' => $_SESSION['username1']
                ));
                $result= $stmt->fetchAll();

                foreach($result as $row) {
                    $username1 = $row['username'];
                    $image = $row['prof_image']; // use this as a profile photo so there's something to upload.
                }
                ?>
                <ul>
                    <li><a href="logout.php">Log-Out</a></li>
                    <li><a href="account.php"><img src="images/<?php echo $image; ?>" id="image"></a></li>
                    <li><a href="account.php"><?php echo $_COOKIE['logUser']?></a></li>
                </ul>
                <?php
            }else{
                ?>
                <ul>
                    <li><a href="account.php">Sign In</a></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
    <br><br><br>
    <div id="navbar">
        <?php
        if($_COOKIE['logUser'] != null){
            $query = "SELECT * FROM users WHERE username = :username1";
            $stmt = $dbh->prepare($query);
            $stmt->execute(array(
                'username1' => $_SESSION['username1']
            ));
            $result= $stmt->fetchAll();

            ?>
            <ul>
                <li><a href="feed.php">TheFeed</a></li>
                <li><a href="testform.php">Quiz</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
            <?php
        }else{
            ?>
            <ul>
                <li><a href="testform.php">Quiz</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
            <?php
        }
        ?>
    </div>
</header>
<!--***  header end  ***-->
<!--# # # # # # # # # # -->
<!--#   CONTENT DIV   #-->
<!--# # # # # # # # # # -->
<div id="content">

    <?php

    if ($_GET['confirm'] == "yes") {
        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'user_id' => $_SESSION['user_id']
        ));
    }
    if ($_GET['confirm'] == "no") {
    header("location: admin.php");
    }

    $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

$query = "SELECT * FROM users";
$stmt = $dbh->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll();


    echo "<table>";
foreach ($result as $row) {


        $confirm = $row['user_id'];



    echo " <tr>
    <td class='admintable'>Account id: " . $row['user_id'] . " </td>
    <td class='admintable'>Account Email " . $row['email'] . " </td>
    <td class='admintable'>Account Username: " . $row['username'] . " </td>
    <td class='admintable'>Account Password: " . $row['password'] . " </td>
    <td class='admintable'><a href='admin.php?remove=". $row['user_id'] . "'>Remove user</a></td>";

       if ($_GET['remove'] == $row['user_id']) {
           $_SESSION['user_id'] = $row['user_id'];
           echo "<td class='admintable'>Confirm? <a href='admin.php?confirm=yes'>Yes </a><a href='admin.php?confirm=no'> No</a> </td>";
       }
    echo "</tr>";
}
    echo "</table>";
    ?>
</div>
<!--***  content end  ***-->
<!--# # # # # # # # # #-->
<!--#   FOOTER DIV    #-->
<!--# # # # # # # # # #-->
<footer>
    <div id="copyright">
        <p>&copy 2016 - Crowned Tailor Clothing Co. All Rights Reserved</p>
    </div>
    <div id="lowLinks">
        <a href="index.php">Home</a>
        <a href="account.php">Sign-In</a>
        <a href="about.php">About Us</a>
        <a href="upload.php">Sponsor Page</a>
        <a href="admin.php">Admin</a>
        <img src="images/CT-Logo2.png">
    </div>
</footer>
<!--***  footer end   ***-->
<!--***    body end   ***-->
</body>
</html>