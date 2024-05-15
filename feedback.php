<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "assignment");

function checkError($clnFeedback) {
    $pattern = '/[#$^*]/';
    if (preg_match($pattern, $clnFeedback)) {
        return "<p>The feedback should not contain special characters.</p>";
    }
    return "";
}

function getEvent($event_id) {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Prepare SQL statement to retrieve event ID based on event name
    $sql = "SELECT event_id FROM event WHERE event_name = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $event_id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the event exists
    if ($row = $result->fetch_assoc()) {
        // Return the event ID
        return $row['event_id'];
    } else {
        // Event not found
        return null;
    }

    // Close prepared statement and database connection
    $stmt->close();
    $con->close();
}

function getUser() {
    if (isset($_COOKIE["login-user"])) {
        $username = $_COOKIE["login-user"];
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        $sql = "SELECT user_id FROM user WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['user_id'];
        } else {
            return null;
        }

        $stmt->close();
        $con->close();
    } else {
        $message = "Error, do not have any cookies.";
        echo '<script>';
        echo 'alert("' . $message . '");';
        echo 'setTimeout(function() { window.location.href = "home.php"; }, 1000);';
        echo '</script>';
    }
}


$header = array(
    "event_id" => "Event ID",
    "event_name" => "Event Name",
    "event_img" => "Image",
    "event_desc" => "Description",
    "event_venue" => "Venue",
    "event_status" => "Status",
    "date" => "Date",
    "time" => "Time",
    "price" => "Price"
);

//search
if (empty($_GET)) {
    $name = "%";
} else {
    $name = $_GET['event_name'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Feedback</title>
        <link href="../../DFT5_P3/css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <form action="" method="POST">
            <input type="text" name="feedback" value="" size="30" maxlength="100"/>
            <input type="submit" value="Submit" name="btnSubmitFeedback" />
        </form>

        <?php
        if (isset($_POST["btnSubmitFeedback"])) {
            $feedback = trim($_POST["feedback"]);
            $event = getEvent($event_id);
            $user = getUser();
            $clnFeedback = htmlspecialchars($feedback);
            $error = checkError($clnFeedback);

            if (empty($error)) {
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $sql = "INSERT INTO user (feedback_id, feedback_desc, username, event_id) VALUES(?,?,?,?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("isss", $clnFeedback, $event, $user);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    //insert successfully
                    printf("<div class='info'>
                        Your Feedback has been inserted.
                       </div>");
                } else {
                    //unable to insert
                    echo "Database Error, Unable to insert. Please try again!";
                }
                $stmt->close();
                $con->close();
            } else {
                // Display error message
                echo $error;
            }
        }
        ?>

    </body>
</html>