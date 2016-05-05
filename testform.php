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
        foreach($result as $row){
            $thyID = $row['user_id'];
            $query = "SELECT * FROM ct_response WHERE users_user_id = :users_user_id";
            $stmt = $dbh->prepare($query);
            $stmt->execute(array(
                'users_user_id' => $thyID
            ));
            $data= $stmt->fetchAll();

            require_once('appvars.php');

            if (count($data) == 0) {
                $topicIDs = array();

                $query2 = "SELECT topic_id FROM ct_topic ORDER BY category, ct_type_type_id";
                $stmt2 = $dbh->prepare($query2);
                $stmt2->execute();
                $data2 = $stmt->fetchAll();

                foreach ($data2 as $row2) {
                    array_push($topicIDs, $row2['topic_id']);
                }

                // Insert empty response rows into the response table, one per topic
                foreach ($topicIDs as $topic_id) {
                    //$query = "INSERT INTO mismatch_response (user_id, topic_id) VALUES ('" . $_SESSION['user_id']. "', '$topic_id')";
                    //mysqli_query($dbc, $query);

                    $query = "INSERT INTO ct_response (users_user_id, topic_id) VALUES (:users_user_id, :topic_id)";
                    $stmt = $dbh->prepare($query);
                    $stmt->execute(array(
                        'users_user_id' => $thyID,
                        'topic_id' => $topic_id
                    ));
                }
            }

        }

// If the questionnaire form has been submitted, write the form responses to the database
        if (isset($_POST['submit'])) {
            // Write the questionnaire response rows to the response table
            foreach ($_POST as $response_id => $response) {
                //$query = "UPDATE mismatch_response SET response = '$response' WHERE response_id = '$response_id'";
                //mysqli_query($dbc, $query);

                $query = "UPDATE ct_response SET response = :response WHERE response_id = :response_id";
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
        echo '<p>Please Answer the Following Questions</p>';
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