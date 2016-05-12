<?php
session_start();
error_reporting(0); // disables all error messages.
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Crowned Tailor - Index</title>
    <link rel="icon" href="images/CT-icon.png">
    <link rel="stylesheet" type="text/css" href="indexStyle.css">
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
    <!-- Start cssSlider -->
    <div class='csslider1 autoplay '>
        <input name="cs_anchor1" id='cs_slide1_0' type="radio" class='cs_anchor slide' >
        <input name="cs_anchor1" id='cs_slide1_1' type="radio" class='cs_anchor slide' >
        <input name="cs_anchor1" id='cs_slide1_2' type="radio" class='cs_anchor slide' >
        <input name="cs_anchor1" id='cs_play1' type="radio" class='cs_anchor' checked>
        <input name="cs_anchor1" id='cs_pause1' type="radio" class='cs_anchor' >
        <ul>
            <div style="width: 100%; visibility: hidden; font-size: 0px; line-height: 0;">
                <img src="https://newevolutiondesigns.com/images/freebies/city-wallpaper-5.jpg" style="width: 100%;">
            </div>
            <li class='num0 img'>
                <a href="" target=""><img src='http://home.bt.com/images/queen-elizabeth-ii-coronation-crown-137477997009502601-130726102131.jpg' alt='slide1' title='slide1' /> </a>
            </li>
            <li class='num1 img'>
                <a href="about.php" target=""><img src='http://static1.squarespace.com/static/561f9bcae4b0f197c395011c/t/561fcf31e4b0ad1f9959cf43/1444925234702/diytailoring.jpg?format=1500w' alt='slide2' title='slide2' /> </a>
            </li>
            <li class='num2 img'>
                <a href="testform.php" target=""><img src='http://edc.h-cdn.co/assets/cm/15/04/54c19810eab0d_-_zoey-deschanel-tommy-fashion-line-95399786.jpg' alt='slide3' title='slide3' /> </a>
            </li>

        </ul>
        <div class='cs_description'>
            <label class='num0'>
                <span class="cs_title"><span class="cs_wrapper">We are CROWNED</span></span>
            </label>
            <label class='num1'>
                <span class="cs_title"><span class="cs_wrapper">Get Your Clothing TAILORED</span></span>
            </label>
            <label class='num2'>
                <span class="cs_title"><span class="cs_wrapper">Take our Clothing Quiz!</span></span>
            </label>
        </div>

        <div class='cs_bullets'>
            <label class='num0' for='cs_slide1_0'>
                <span class='cs_point'></span>
                <span class='cs_thumb'><img src='http://home.bt.com/images/queen-elizabeth-ii-coronation-crown-137477997009502601-130726102131.jpg' alt='slide1' title='slide1' /></span>
            </label>
            <label class='num1' for='cs_slide1_1'>
                <span class='cs_point'></span>
                <span class='cs_thumb'><img src='http://static1.squarespace.com/static/561f9bcae4b0f197c395011c/t/561fcf31e4b0ad1f9959cf43/1444925234702/diytailoring.jpg?format=1500w' alt='slide2' title='slide2' /></span>
            </label>
            <label class='num2' for='cs_slide1_2'>
                <span class='cs_point'></span>
                <span class='cs_thumb'><img src='http://edc.h-cdn.co/assets/cm/15/04/54c19810eab0d_-_zoey-deschanel-tommy-fashion-line-95399786.jpg' alt='slide3' title='slide3' /></span>
            </label>
        </div>
    </div>
    <!-- End cssSlider -->
    <h1 style="font-size: 40px;">Welcome to the Domain of the Crowned Tailor</h1>

    <div>
        <img style="width: 50%; float: right" src="images/crown1.jpg">
        <div style="width: 50%;"><br><h1>HEY YOU!</h1><h2>Do you want clothes tailored to you and your personality perfectly?</h2><h3>Take our <a href="testform.php">Crowned Clothing Personality Quiz</a>!</h3><img style="width: 25%;" src="http://www.picgifs.com/graphics/c/clothing/graphics-clothing-929591.gif"></div>
    </div>
</div>
<div style="text-align: center" class="productLine">
    <h2 style="margin-left: 10%; margin-top: 15%; margin-right: 10%; font-size: 30px;">CHECK OUT OUR PERSONALITY PRODUCT LINES!</h2>
    <ul>
        <li>
            <div class="open">
                <img style="width: 50%; height: 100px" src="http://cdn.shopify.com/s/files/1/0035/1192/products/black_varsity_jacket_unis_open_1024x1024.jpg?v=1445460266">
                <h2>Socs</h2>
            </div></li>
        <li>
            <div class="open">
                <img style="width: 70%; height: 100px" src="http://www.freevector.com/uploads/vector/preview/243/FreeVector-Beautiful-Eye.jpg">
                <h2>Irises</h2>
            </div></li>
        <li><div class="open">
                <img style="width: 60%; height: 100px" src="https://s-media-cache-ak0.pinimg.com/736x/c7/48/c9/c748c9cffbd463256afd60c98b48fa53.jpg">
                <h2>Flyers</h2>
            </div></li>
        <li>
            <div class="open">
                <img style="width: 50%; height: 100px" src="https://www.colourbox.com/preview/3217520-isolated-fluffy-heart-on-white-background.jpg">
                <h2>Cazzies</h2>
            </div>
        </li>
    </ul>
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
        <a href="admin.php">Mng Website</a>
        <a href="account.php">Sign-In</a>
        <a href="about.php">About Us</a>
        <img src="images/CT-Logo2.png">
    </div>
</footer>
<!--***  footer end   ***-->
<!--***    body end   ***-->
</body>
</html>