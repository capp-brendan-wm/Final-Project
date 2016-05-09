<?php
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
                <li><a href="upload.php"><img src="images/<?php echo $image; ?>" id="image"></a></li>
                <li><a href="admin.php"><?php echo $_COOKIE['logUser']?></a></li>
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
        <ul>
            <li><a href="testform.php">Quiz</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
</header>
<!--***  header end  ***-->
<!--# # # # # # # # # # -->
<!--#   CONTENT DIV   #-->
<!--# # # # # # # # # # -->
<div id="content">
    <img style="width: 40%" src="https://s-media-cache-ak0.pinimg.com/736x/a1/99/c6/a199c69ed7f6b52943df06c06d092e29.jpg">
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
        <img src="images/CT-Logo2.png">
    </div>
</footer>
<!--***  footer end   ***-->
<!--***    body end   ***-->
</body>
</html>