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
<div id="content">
    <?php
    if($_COOKIE['logUser'] != null){
        $dbh = new PDO('mysql:host=localhost;dbname=ct.db', 'root', 'root');

        $query = "SELECT * FROM users WHERE username = :username1";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'username1' => $_SESSION['username1']
        ));
        $result= $stmt->fetchAll();
        ?>
        <h1><?php echo $_COOKIE['logUser']; ?>, Welcome to our Crowned Clothing Personality Quiz!</h1>
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->

        <?php
        require_once('appvars.php');
// Make sure the user is logged in before going any further.
        if (!isset($_SESSION['user_id'])) {
            echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
            exit();
        }

// Connect to the database
//$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $dbh = new PDO('mysql:host=localhost;dbname=mismatchdb', 'root', 'root');

// If this user has never answered the questionnaire, insert empty responses into the database
//$query = "SELECT * FROM mismatch_response WHERE user_id = '" . $_SESSION['user_id'] . "'";
//$data = mysqli_query($dbc, $query);



        $query = "SELECT * FROM mismatch_response WHERE user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'user_id' => $_SESSION['user_id']
        ));
        $data = $stmt->fetchAll();


        if (count($data) == 0) {
            // First grab the list of topic IDs from the topic table
            //$query = "SELECT topic_id FROM mismatch_topic ORDER BY category_id, topic_id";
            //$data = mysqli_query($dbc, $query);
            $topicIDs = array();

            $query = "SELECT topic_id FROM mismatch_topic ORDER BY category_id, topic_id";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll();

            foreach ($data as $row) {
                array_push($topicIDs, $row['topic_id']);
            }

            // Insert empty response rows into the response table, one per topic
            foreach ($topicIDs as $topic_id) {
                //$query = "INSERT INTO mismatch_response (user_id, topic_id) VALUES ('" . $_SESSION['user_id']. "', '$topic_id')";
                //mysqli_query($dbc, $query);

                $query = "INSERT INTO mismatch_response (user_id, topic_id) VALUES (:user_id, :topic_id)";
                $stmt = $dbh->prepare($query);
                $stmt->execute(array(
                    'user_id' => $_SESSION['user_id'],
                    'topic_id' => $topic_id
                ));
            }
        }

// If the questionnaire form has been submitted, write the form responses to the database
        if (isset($_POST['submit'])) {
            // Write the questionnaire response rows to the response table
            foreach ($_POST as $response_id => $response) {
                //$query = "UPDATE mismatch_response SET response = '$response' WHERE response_id = '$response_id'";
                //mysqli_query($dbc, $query);

                $query = "UPDATE mismatch_response SET response = :response WHERE response_id = :response_id";
                $stmt = $dbh->prepare($query);
                $stmt->execute(array(
                    'response' => $response,
                    'response_id' => $response_id
                ));



            }
            echo '<p>Your responses have been saved.</p>';
        }

// Grab the response data from the database to generate the form
//$query = "SELECT mr.response_id, mr.topic_id, mr.response, mt.name AS topic_name, mc.name AS category_name " .
//  "FROM mismatch_response AS mr " .
//  "INNER JOIN mismatch_topic AS mt USING (topic_id) " .
//  "INNER JOIN mismatch_category AS mc USING (category_id) " .
//  "WHERE mr.user_id = '" . $_SESSION['user_id'] . "'";


        $query = "SELECT mr.response_id, mr.topic_id, mr.response, mt.name AS topic_name, mc.name AS category_name " .
            "FROM mismatch_response AS mr " .
            "INNER JOIN mismatch_topic AS mt USING (topic_id) " .
            "INNER JOIN mismatch_category AS mc USING (category_id) " .
            "WHERE mr.user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
            'user_id' => $_SESSION['user_id']
        ));
        $data = $stmt->fetchAll();

//$data = mysqli_query($dbc, $query);
        $responses = array();
        foreach ($data as $row) {
            array_push($responses, $row);
        }

//mysqli_close($dbc);

// Generate the questionnaire form by looping through the response array
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
        echo '<p>How do you feel about each topic?</p>';
        $category = $responses[0]['category_name'];
        echo '<fieldset><legend>' . $responses[0]['category_name'] . '</legend>';
        foreach ($responses as $response) {
            // Only start a new fieldset if the category has changed
            if ($category != $response['category_name']) {
                $category = $response['category_name'];
                echo '</fieldset><fieldset><legend>' . $response['category_name'] . '</legend>';
            }

            // Display the topic form field
            echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' . $response['response_id'] . '">' . $response['topic_name'] . ':</label>';
            echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Love ';
            echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="2" ' . ($response['response'] == 2 ? 'checked="checked"' : '') . ' />Hate<br />';
        }
        echo '</fieldset>';
        echo '<input type="submit" value="Save Questionnaire" name="submit" />';
        echo '</form>';
        ?>


        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <!--##############################################################################################################-->
        <?php
    }else{
        ?>
        <h1>You have found our Crowned Clothing Personality Quiz</h1>
        <h2>We've noticed you are not logged-in, to access the quiz and save your results, please <a href="account.php">Sign In</a> </h2>
        <?php
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
        <img src="images/CT-Logo2.png">
    </div>
</footer>
<!--***  footer end   ***-->
<!--***    body end   ***-->
</body>
</html>