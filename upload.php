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
                    'username1' => $_COOKIE['logUser']
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
<!--***  header end  ***-->
<!--# # # # # # # # # # -->
<!--#   CONTENT DIV   #-->
<!--# # # # # # # # # # -->
<div id="content">


<?php

    if ($_SESSION['loggedIn'] == 1) {

    define('MM_UPLOADPATH', 'images/');
    define('MM_MAXFILESIZE', 10000000);      //  KB
    define('MM_MAXIMGWIDTH', 1200);        //  pixels
    define('MM_MAXIMGHEIGHT', 1200);       //  pixels

    if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $old_picture = trim($_POST['old_picture']);
    $new_picture = trim($_FILES['screenshot']['name']);
    $new_picture_type = $_FILES['screenshot']['type'];
    $new_picture_size = $_FILES['screenshot']['size'];
    list($new_picture_width, $new_picture_height) = getimagesize($_FILES['screenshot']['tmp_name']);
    $error = false;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($new_picture)) {
    if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
    ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
    ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
    if ($_FILES['file']['error'] == 0) {
    // Move the file to the target upload folder
    $target = MM_UPLOADPATH . basename($new_picture);
    if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
    // The new picture file move was successful, now make sure any old picture is deleted
    if (!empty($old_picture) && ($old_picture != $new_picture)) {
    @unlink(MM_UPLOADPATH . $old_picture);
    }
    }
    else {
    // The new picture file move failed, so delete the temporary file and set the error flag
    @unlink($_FILES['screenshot']['tmp_name']);
    $error = true;
    // echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
    }
    }
    }
    else {
    // The new picture file is not valid, so delete the temporary file and set the error flag
    @unlink($_FILES['screenshot']['tmp_name']);
    $error = true;
    echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
        ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
    }
    }


    // Update the profile data in the database
    if (!$error) {

    // Only set the picture column if there is a new picture

    $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

    $query = "INSERT INTO ct_uploads VALUES (0, :screenshot, :username1, :category)";
    $stmt = $dbh->prepare($query);
    $stmt->execute(array(
    'username1' => $_COOKIE['logUser'],
    'screenshot' => $new_picture,
        'category' => $_POST['category']
    ));
    // Confirm success with the user
    // echo '<p>Your profile has been successfully updated. Would you like to <a href="viewprofile.php">view your profile</a>?</p>';
    //exit();
    }
    $_POST['submit'] = null;
    // header('location: account.php?fixit=true');
        echo "<h1> Your Clothing has been successfully uploaded! </h1>";
    } // End of check for form submission

    else {
        ?>
<br>
        <div id="main">

            Share your clothing with the community!
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />

                Photo to Submit
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                <input type="file" id="screenshot" name="screenshot"/>
                <br>
                <select name="category">
                    <option value=""></option>
                    <option value="socs">Socs </option>
                    <option value="irises"> Irises </option>
                    <option value="flyers"> Flyers </option>
                    <option value="cazzies"> Cazzies </option>
                </select>
                <br>
                <input type="submit" value="Share these clothes!" name="submit" />
            </form>

        </div>
        <br>
        <?php

    }



}
else {
    echo "<h1> You must be logged in to upload images </h1>";
}
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