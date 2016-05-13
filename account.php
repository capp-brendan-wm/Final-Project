<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crowned Tailor</title>
    <link rel="icon" href="images/CT-icon.png">
    <link rel="stylesheet" type="text/css" href="loginStyle.css">
</head>
<body>
<!--###  body start  ###-->
<!--# # # # # # # # # #-->
<!--#   HEADER DIV    #-->
<!--# # # # # # # # # #-->
<header>
    <a href="index.php"><img src="images/CT-LogoMain.png"></a>
</header>
<!--***  header end  ***-->
<!--# # # # # # # # # # -->
<!--#   CONTENT DIV   #-->
<!--# # # # # # # # # # -->
<div id="content">
    <?php
    session_start();
    error_reporting(0); // disables all error messages.

    if ($_SESSION['loggedIn'] == "") {
        $_SESSION['loggedIn'] = 0;
    }
    if ($_GET['logout'] == "true") {
        $_SESSION['loggedIn'] = 0;
    }


//If user is logged in, display the account page.
    if ($_SESSION['loggedIn'] == 1 && $_GET['signup'] != "true") {

        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
        $query = "SELECT * FROM users WHERE username = :username1";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'username1' => $_SESSION['username1']
        ));
        $result= $stmt->fetchAll();

        define('MM_UPLOADPATH', 'images/');
        define('MM_MAXFILESIZE', 10000000);      //  KB
        define('MM_MAXIMGWIDTH', 1200);        //  pixels
        define('MM_MAXIMGHEIGHT', 1200);       //  pixels

        //when the user submits the form
        if (isset($_POST['submit'])) {
            // Grab the profile data from the POST
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $email = trim($_POST['email']);
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
                if (!empty($username) && !empty($password) && !empty($email)) {

                    // Only set the picture column if there is a new picture
                    if (!empty($new_picture)) {

                        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
                        $query = "UPDATE users SET username = :username, email = :email, password = :password, " .
                            " prof_image = :new_picture WHERE username = :username1";
                        $stmt = $dbh->prepare($query);
                        $stmt->execute(array(
                            'username1' => $_COOKIE['logUser'],
                            'username' => $username,
                            'email' => $email,
                            'password' => $password,
                            'new_picture' => $new_picture
                        ));
                        $_COOKIE['logUser'] = $username;
                        $_SESSION['image'] = $new_picture;
                    }
                    // If there is no new picture to upload
                    else {
                        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
                        $query = "UPDATE users SET username = :username, email = :email, password = :password " .
                            "WHERE username = :username1";
                        $stmt = $dbh->prepare($query);
                        $stmt->execute(array(
                            'username1' => $_COOKIE['logUser'],
                            'username' => $username,
                            'email' => $email,
                            'password' => $password,
                        ));
                        $_COOKIE['logUser'] = $username;
                    }
                    // Confirm success with the user
                }
                else {
                    echo '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
                }
            }
            $_POST['submit'] = null;
        } // End of check for form submission

        //select information about logged in user from the database
        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
        $query = "SELECT prof_image FROM users WHERE username = :username1";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'username1' => $_COOKIE['logUser']
        ));
        $result= $stmt->fetchAll();

        foreach ($result as $row) {
            $_SESSION['image'] = $row['prof_image'];
        }
        ?>
        <!-- If user is logged in then display form to update profile -->
        <div id="top" >
            My Account <a href="account.php?logout=true"> Logout</a>
        </div>
        <div id="account">
            <img src="images/<?php echo $_SESSION['image']; ?>" id="image">

            <div id="nofloat" >
                <div id="account2"><h1> <?php echo $_COOKIE['logUser'];?></h1> </div>
            </div>
        </div>
        <div id="main">

            Update user info
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />

                Email <input type="email" name="email" placeholder="email">
                <br>
                Username <input type="text" name="username" placeholder="username">
                <br>
                Password <input type="password" name="password" placeholder="password">
                <br>
                Profile Photo
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                <input type="file" id="screenshot" name="screenshot"/>
                <br>
                <input type="submit" value="Update Profile!" name="submit" />
            </form>

        </div>
        <?php
    } //end of account page





//if user is not logged in and is not signing up
    if ($_SESSION['loggedIn'] == 0 && $_GET['signup'] != "true") {
        ?>
        <!-- Displpay this html if not logged in -->
        <h1> Log-In</h1>
        <form method="post">
            Username: <input type="text" placeholder="username" name="username1"><br>
            Password: <input type="password" placeholder="password" name="password1"><br>
            <br>
            <button type="submit">Log-In</button>
        </form>
        <h2> Don't have an account, <a href="account.php?signup=true" >Make one! </a> </h2>
        <?php
        if ($_POST['username1'] != null && $_POST['password1'] != null) {

            $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
            $query = "SELECT * FROM users WHERE username = :username1 AND password = :password1";
            $stmt = $dbh->prepare($query);
            $stmt->execute(array(
                'username1' => $_POST['username1'],
                'password1' => $_POST['password1'],
            ));
            $result = $stmt->fetchAll();

            foreach ($result as $row) {
                $_SESSION['loggedIn'] = 1;
                $_SESSION['username1'] = $row['username'];
                $_SESSION['user_session'] = $row['username'];
                setcookie('logUser', $_SESSION['user_session']);
                header("Location: index.php");
            }
            if ($row['id'] == "") {
                echo "<h3> You must enter a valid username and password. </h3>";
            }
        }
    }// end of login page


    // if user is not logged in and user is logged in.
    if ($_SESSION['loggedIn'] == 0 && $_GET['signup'] == "true") {
        ?>
        <!-- sign up form -->
        <h1> Sign up </h1>
        <form method="post">
            Email: <input type="text" placeholder="email" name="email3"><br>
            Username: <input type="text" placeholder="username" name="username3"><br>
            Password: <input type="password" placeholder="password" name="password3"><br>
            <br>
            <button type="submit">Submit</button>
        </form>

        <?php

        if ($_POST['username3'] != null && $_POST['password3']) {
            $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
            $query = "INSERT INTO users VALUES (0, :email3, :username3, :password3, NULL )";
            $stmt = $dbh->prepare($query);
            $result = $stmt->execute(
                array(
                    'email3'    => $_POST['email3'],
                    'username3' => $_POST['username3'],
                    'password3' => $_POST['password3']
                ));

            if ($result) {
                $_SESSION['loggedIn'] = 1;
                $_SESSION['username1'] = $_POST['username3'];
                header('Location: account.php');
            }
            else {
                echo "There was a problem entering your information. (Maybe username is already taken?) ";
            }
        }
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