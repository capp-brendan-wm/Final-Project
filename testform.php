<?php
session_start();
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
                    <li><a href="account.php"><img src="images/<?php echo $image; ?>" id="image"></a></li>
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
<div id="content" style="text-align: left">
<?php

if ($_POST['submit'] == null) {

    $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

    $query = "SELECT * FROM ct_questions";
    $stmt = $dbh->prepare($query);
    $stmt->execute(array(
    //'username1' => $_COOKIE['logUser']
    ));
    $result= $stmt->fetchAll();


    echo "<form method='post'><table>";
            foreach ($result as $row) {
            echo "<tr> <td>" .
                    $row['question'] . "<br>" .
                    "  </td>";


                if ($row['ct_type_type_id'] > 0) {


                $dbh2 = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

                $query2 = "SELECT * FROM ct_topic WHERE ct_questions_question_id = :ct_questions_question_id";
                $stmt2 = $dbh2->prepare($query2);
                $stmt2->execute(array(
                'ct_questions_question_id' => $row['ct_type_type_id']
                ));
                $result2= $stmt2->fetchAll();

                echo "<td>";
                    foreach ($result2 as $row2) {

                    echo "<input type='checkbox' value='1' name='" . $row2['ct_type_type_id'] . "'>";
                    echo $row2['topic'] . "<br>";

                    }
                    echo "</td>";


                }


                ;
                echo "</tr>";
            }
            echo "<tr><td><input type='submit' value='Submit the form!' name='submit'></td></tr></table></form>";


}
else {
    echo "you took the test";





}

    ?>

    <style>
        td {
            border: 1px;
            border-style: double;
        }
    </style>
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