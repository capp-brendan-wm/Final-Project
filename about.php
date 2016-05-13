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
                    <li><a href="upload.php"><img src="images/<?php echo $image; ?>" id="image"></a></li>
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
    <h1 style="font-size: 22px">Among the best, We out-do the rest</h1>
    <h1 style="font-size: 30px">Dress like Royalty</h1>
    <h1 style="font-size: 35px">For those who rule, Rule Well</h1>
    <h1 style="font-size: 55px; ">Long Live The Crowned</h1>
    <img style="width: 40%" src="https://s-media-cache-ak0.pinimg.com/736x/1a/7b/3e/1a7b3ec54bc6fa125d0bcb8f3b1adfd9.jpg">
    <p>Welcome to Crown Tailor
        where we can help you find your style personality. Are you always stuck wondering about what to wear to show off your personality? Well look no further here at Crown Tailors we'll help you discover ways to show off your personality through a simple and short personality clothes that'll match you to your style.</p>
    <p>Crown Tailor was founded by Andy, Zach, Brendan and Keyan in May of 2016. Originally located within the START @ West-MEC building in the Grand Canyon State of Arizona, this Glendale Office Building/School. Crowned Tailor shared its home with about 5 other businesses, most of them also being start-ups.</p>
    <img style="width: 5%;" src="images/CT-Logo2.png">
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