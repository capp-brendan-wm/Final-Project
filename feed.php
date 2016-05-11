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
<div id="content">
<?php
define('GW_UPLOADPATH', 'images/');
// Connect to the database
$dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');
// Retrieve the score data from MySQL
$query = "SELECT * FROM ct_uploads ORDER BY id_ct_submitions DESC";
$stmt = $dbh->prepare($query);
$stmt->execute();
$score = $stmt->fetchall();
// Loop through the array of score data, formatting it as HTML
echo '<table>';
foreach($score as $row) {
    echo "<tr> <td> Username:</td> <td>" . $row['username'] . "</td> <td>Category:</td> <td>" . $row['category'] . "</td> <td><img src='images/" . $row['image'] . "'></td>";
}
echo '</table>';
?>
    </div>
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