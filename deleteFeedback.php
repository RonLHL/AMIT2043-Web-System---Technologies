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
        <title>Delete FeedBack</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php require_once './secret/helperMember.php'?>
        
        <h1>Delete Feedback</h1>
        <?php 
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                //GET METHOD
                //retrieve and display existing record
                //URL - studentID
                if(isset($_GET['id'])){
                    $id = strtoupper(trim($_GET['id']));
                }else{
                    $id = "";
                }
                
                //Step 1: create connection
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                //Step 2: sql statement
                $sql = "SELECT * FROM feedback WHERE feedback_id = '$id'";
                
                //Step 3: process sql
                $result = $con->query($sql);
                
                if($row = $result->fetch_object()){
                    $feedback_id = $row->feedback_id;
                    $feedback_desc = $row->feedback_desc;
                    $userName = $row->username;
                    $event_id = $row->event_id;
                    
                    printf("<p>Are you sure, you want to delete this following feedback?</p>
                            <table cellpadding = '10'>
                                <tr>
                                    <td>Feedback ID: </td>
                                    <td>%s</td>
                                </tr>
                                <tr>
                                    <td>From username: </td>
                                    <td>%s</td>
                                </tr>
                                <tr>
                                    <td>Event: </td>
                                    <td>%s</td>
                                </tr>
                                <tr>
                                    <td>Feedback Description: </td>
                                    <td>%s</td>
                                </tr>
                            </table>
                            <form action = '' method = 'POST'>
                                <input type='hidden' name='feedbackId' value='%s'/>
                                <input type='submit' value='Yes' name='btnYes' />
                                <input type='button' value='Cancel' name='btnCancel' onclick='location.href='/memberProfile.php'' />
                            </form>",
                            $feedback_id, $userName, $event_id, $feedback_desc, $feedback_id);
                }else{
                    //record is not found
                    echo "Unable to process. [<a href = 'memberProfile.php'>Try again.</a>]";
                }
                    $result->free();
                    $con->close();
            }else{
                //POST METHOD
                //delete record
                //1.1 receive student id, name
                $id = strtoupper(trim($_POST["feedbackId"])); //hidden field
                
                //Step 1: create connection
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                //Step 2: sql statement
                $sql = "DELETE FROM feedback WHERE feedback_id = ?";
                
                //Step 3: process sql
                $stmt = $con->prepare($sql);
                
                //Step 3.1: pass in value into sql parameter
                $stmt->bind_param("s", $id);
                
                //Step 3.2: execute process
                $stmt->execute();
                
                if($stmt->affected_rows > 0){
                    //delete successful
                    printf("<div class = 'info'>Feedback <b>%s</b> has been deleted! [<a href = 'memberProfile.php'>Back to list</a>]</div>", $id);
                }else{
                    //unable to delete
                    echo "<div class = 'error'>Error, cannot delete record. [ <a href = 'memberProfile.php'>Try again</a> ]</div>";
                }
            }
        ?>
        <?php include 'footer.php'?>
    </body>
</html>