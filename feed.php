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
        <ul>
            <li><a href="testform.php">Quiz</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
</header>
<!--***  header end  ***-->
<!--# # # # # # # # # # -->
<?php
define('GW_UPLOADPATH', 'images/');
// Connect to the database
$dbh = new PDO('mysql:host=localhost;dbname=gwdb', 'root', 'root');
// Retrieve the score data from MySQL
$query = "SELECT * FROM guitarwars ORDER BY score DESC";
$stmt = $dbh->prepare($query);
$stmt->execute();
$score = $stmt->fetchall();
// Loop through the array of score data, formatting it as HTML
echo '<table>';
$i = 0;
foreach($score as $row) {
    $filepath = GW_UPLOADPATH . $row['screenshot'];
    // Display the score data
    if ($i == 0) {
        echo '<tr><td colspan="2" class="topscoreheader">Top Score: ' . $row['score'] . '</td></tr>';
    }
    echo '<tr><td class="scoreinfo">';
    echo '<span class="score">' . $row['score'] . '</span><br />';
    echo '<strong>Name:</strong> ' . $row['name'] . '<br />';
    echo '<strong>Date:</strong> ' . $row['date'] . '</td></tr>';
    if(is_file($filepath) && filesize($filepath) > 0) {
        echo '<td><img src="' .$filepath. '"alt="Score image" /></td></tr>';
    }
    else {
        echo '<td><img src="unverified.gif" alt="Unverified score" /></td></tr>';
    }
    $i++;
}
echo '</table>';
?>
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