
<link rel="stylesheet" type="text/css" href="style.css">

<?php
session_start();
//error_reporting(0); // disables all error messages.

if ($_SESSION['loggedIn'] == "") {
    $_SESSION['loggedIn'] = 0;
}
if ($_GET['logout'] == "true") {
    $_SESSION['loggedIn'] = 0;
    header("Location: account.php");
}






if ($_SESSION['loggedIn'] == 1 && $_GET['signup'] != "true") {

    $dbh = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');

    $query = "SELECT * FROM subscription WHERE username1 = :username1";
    $stmt = $dbh->prepare($query);
    $stmt->execute(array(
        'username1' => $_SESSION['username1']
    ));
    $result= $stmt->fetchAll();

    foreach($result as $row) {
        $username1 = $row['username1'];
        $image = $row['image']; // use this as a profile photo so there's something to upload.
    }
    $dbh = null;




    $screenshot = $_FILES['screenshot']['name'];
    $screenshot_size = $_FILES['screenshot']['size'];
    $screenshot_type = $_FILES['screenshot']['type'];
    define('MAXFILESIZE', 10000000);

    if ($screenshot != null) {

    if ((($screenshot_type == 'image/gif')
        || ($screenshot_type == 'image/jpeg')
        || ($screenshot_type == 'image/pjpeg')
        || ($screenshot_type == 'image/png')
        && ($screenshot_size > 0)
        && ($screenshot_size <= MAXFILESIZE))
    )
    {


            $target = "images/" . $screenshot;
            if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {

                    // Connect to the database

                        $dbh = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');
                        // Write the data to the database
                        $query = "UPDATE subscription SET image = :screenshot WHERE username1 = :username1";
                        $stmt = $dbh->prepare($query);
                        $result = $stmt->execute(
                            array(
                                'username1' => $_SESSION['username1'],
                                'screenshot' => $screenshot
                            )
                        );

               $image = $screenshot;


            }

        @unlink($_FILES['screenshot']['tmp_name']);
    } else {
        echo '<p class="error">The screen shot must be a GIF, JPEG, or PNG image file no ' . 'greater than ' . (MAXFILESIZE / 1024) . ' KB in size.</p>';
    }
}
    else {
        //no image has been posted

    }







    ?>
    <div id="top" >
     My Account <a href="account.php?logout=true"> Logout</a>
    </div>
    <div id="account">
         <img src="images/<?php echo $image; ?>" id="image">

        <div id="nofloat" >
        <div id="account2"> <?php echo $_SESSION['username1'];?> </div>
        <div id="account3"> Won X games, Lost X games. <br> W/L = X%</div>
        </div>
    </div>
    <div id="main">

        Upload or change photo
        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input type="file" id="screenshot" name="screenshot" />
            <input type="submit" value="Upload Image" name="submit" />
        </form>

    </div>
    <div id="back" ><a href="chess.php"> Back to game </a></div>

        <?php
    //logged in















}

if ($_SESSION['loggedIn'] == 0 && $_GET['signup'] != "true") {
    ?>
    <!-- Displpay this html if not logged in -->
    <h1> It looks like you're not logged in, would you like to sign in?</h1>
    <form method="post">
        Sign in<br>
        Player Username: <input type="text" placeholder="username" name="username1"><br>
        Player Password: <input type="password" placeholder="password" name="password1"><br>
        <br>
        <button type="submit">Load Game</button>
    </form>
    <h2> Don't have an account, <a href="account.php?signup=true" >Make one! </a> </h2>
<?php
    //not logged in
    if ($_POST['username1'] != null && $_POST['password1'] != null) {
        $dbh = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');

        $query = "SELECT * FROM subscription WHERE username1 = :username1 AND password1 = :password1";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'username1' => $_POST['username1'],
            'password1' => $_POST['password1'],
        ));
        $result = $stmt->fetchAll();

        foreach ($result as $row) {
            $_SESSION['loggedIn'] = 1;
            $_SESSION['username1'] = $row['username1'];
            header('location: account.php');
        }
        if ($row['id'] == "") {
        echo "<h3> You must enter a valid username and password. </h3>";
        }
    }



}






//$_SESSION['loggedIn'] = 0;


if ($_SESSION['loggedIn'] == 0 && $_GET['signup'] == "true") {
    ?>
    <h1> Sign up </h1>
    <form method="post">
        Save your game <br>
        Player Username: <input type="text" placeholder="username" name="username3"><br>
        Player Password: <input type="password" placeholder="password" name="password3"><br>
        <br>
        <button type="submit">Save Game</button>
        </form>

    <?php

if ($_POST['username3'] != null && $_POST['password3']) {
    $dbh = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');
//Write the data to the database
    $query = "INSERT INTO subscription VALUES (0, :username3, :password3, NULL, Null, Null, NULL)";
    $stmt = $dbh->prepare($query);
    $result = $stmt->execute(
        array(
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