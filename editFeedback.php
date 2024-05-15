<?php
if(isset($_POST['logout'])){
if (isset($_COOKIE["login-user"])) {
    // Unset the cookie by setting its expiration time to the past
    setcookie("login-user", "", time() - 3600);
    $message = "LogOut Successful.";
    echo '<script>';
    echo 'alert("' . $message . '");';
    echo 'setTimeout(function() { window.location.href = "home.php"; }, 1000);';
    echo '</script>';
} else {
    $message = "Error, do not have any cookies.";
    echo '<script>';
    echo 'alert("' . $message . '");';
    echo 'setTimeout(function() { window.location.href = "home.php"; }, 1000);';
    echo '</script>';
}
}

?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Feedback</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php require_once './secret/helperMember.php'; ?>
        <h1>Edit Feedback</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            //GET METHOD
            //retrieve and display existing record
            //URL - studentID
            if (isset($_GET['id'])) {
                $feedback_id = strtoupper(trim($_GET['id']));
            } else {
                $feedback_id = "";
            }

            //Step 1: create connection
            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            //Step 2: sql statement
            $sql = "SELECT feedback_id, event_id, feedback_desc FROM feedback WHERE feedback_id = '$feedback_id'";

            //Step 3: process sql
            $result = $con->query($sql);

            if ($row = $result->fetch_object()) {
                //record found
                $feedback_id = $row->feedback_id;
                $event_id = $row->event_id;
                $feedback_desc = $row->feedback_desc;
            } else {
                //record is not found
                echo "Unable to process. [<a href = 'memberProfile.php'>Try again.</a>]";
            }
            $result->free();
            $con->close();
        } else {
            
                if (isset($_GET['id'])) {
                    $feedback_id = strtoupper(trim($_GET['id']));
                } else {
                    $feedback_id = "";
                }
                //POST METHOD - UPDATE record
                //user click/ submit the page
                //1.1 receive user input from form
                //take id from hidden field
                $feedback_id = trim($_POST["feedbackId"]);
                $feedback_desc = trim($_POST["feedbackDesc"]);
                $event_id = trim($_POST["event_id"]);

                //1.2 check/validate/verify member detail
                $error["feedback_desc"] = checkFeedback($feedback_desc);
                $error = array_filter($error);

                if (empty($error)) {
                    //GOOD, no error, allow to update
                    //Step 1: create connection
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                    //Step 2: sql statement
                    $sql = "UPDATE feedback SET feedback_desc = ? WHERE feedback_id = '$feedback_id'";

                    //Step 3: process sql
                    //NOTE: $con->query($sql) <<<<<<<< when there is NO "?" in sql
                    //NOTE: $con->prepare($sql)
                    $stmt = $con->prepare($sql);

                    //Step 3.1: prepare statement for sql with "?"
                    //NOTE: s - string, d - double, i - integer, b - blob
                    $stmt->bind_param("s", $feedback_desc);

                    //Step 3.2: execute
                    $stmt->execute();

                    //NOTE: $stmt->affected_rows (this code will only apply to CUD)
                    if ($stmt->affected_rows > 0) {
                        //insert successfully
                        printf("<div class = 'info'>
                                Your Feedback <b>%s</b> has been updated.
                                [ <a href = 'memberProfile.php'>Back to Profile</a> ]
                               </div>", $feedback_id);
                    } else {
                        //unable to insert
                        echo "Database Error, Unable to update. Please try again!";
                    }
                    $con->close();
                    $stmt->close();
                } else {
                    //WITH ERROR, display error msg
                    echo "<ul class = 'error'>";
                    foreach ($error as $value) {
                        echo "<li>$value</li>";
                    }
                    echo "</ul>";
                }
            }
        
        ?>
        <form method="POST" action="">
            <table>
                <tr>
                    <td>Feedback ID:</td>
                    <td><?php echo isset($feedback_id) ? $feedback_id : ""; ?><input type = "hidden" name = "feedbackId" value = "<?php echo $feedback_id; ?>"/></td>
                </tr>
                <tr>
                    <td>Event ID:</td>
                    <td><?php echo isset($event_id) ? $event_id : ""; ?><input type = "hidden" name = "event_id" value = "<?php echo $event_id; ?>"/></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><input type="text" name="feedbackDesc" value="<?php echo isset($feedback_desc) ? $feedback_desc : ""; ?>" /></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="Update" name="btnUpdate" />
            <input type="button" value="Cancel" name="btnCancel" onclick = "location = 'feedbackHistory.php'"/>
        </form>
        <?php include 'footer.php' ?>
    </body>
</html>