
<link rel="stylesheet" type="text/css" href="style.css">

<?php
session_start();
error_reporting(0); // disables all error messages.

if ($_SESSION['loggedIn'] == "") {
    $_SESSION['loggedIn'] = 0;
}
if ($_GET['logout'] == "true") {
    $_SESSION['loggedIn'] = 0;
    header("Location: account.php");
}






if ($_SESSION['loggedIn'] == 1 && $_GET['signup'] != "true") {

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

                        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
                        // Write the data to the database
                        $query = "UPDATE users SET prof_image = :screenshot WHERE username = :username1";
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
            header('location: account.php');
        }
        if ($row['username'] == "") {
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
        Player Email: <input type="text" placeholder="email" name="email3"><br>
        Player Username: <input type="text" placeholder="username" name="username3"><br>
        Player Password: <input type="password" placeholder="password" name="password3"><br>
        <br>
        <button type="submit">Save Game</button>
        </form>

    <?php

if ($_POST['username3'] != null && $_POST['password3']) {
    $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
//Write the data to the database
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