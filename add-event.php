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
<?php 
    if(isset($_FILES["fupImage"])){
        $file = $_FILES["fupImage"];
        if($file['error'] > 0 ){
            switch ($file['error']){
                case UPLOAD_ERR_NO_FILE:
                    $err = "No file was seleted.";
                    break;
                case UPLOAD_ERR_FROM_SIZE:
                    $err = "File uploded is too large. Maximum 10MB is allowed";
                    break;
                default:
                    $err = "There was an error while uploading the file.";
                    break;
            }
        }else if($file['size'] > 10485760){
            //1MB = 1024kb = 1048576
            $err = "File uploded is too large. Maximum 10MB is allowed";
        }else{
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                   
        if($ext != 'jpg' && $ext != 'png' &&$ext != 'gif' &&$ext != 'jpeg'){
            $err = "Only jpg, jpeg, png and gif format re allowed";
        }else{
            //all good
            $img = uniqid();
            
            $save_as = $img . '.' . $ext;
            
            move_uploaded_file($file['tmp_name'], 'uploads/' . $save_as);
            
            }
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
        <title>Add Event</title>
        <link href="css/Style.css" rel="stylesheet" type="text/css"/>
        <style>
        .table-gray{
            background-color: #D6F0FC;
            margin: 30px;
            padding: 100px;
        }
        </style>
    </head>
    <body>
        <?php
            include ('admin-header.php');
            require_once './secret/helper-event.php';
        ?>
        
        <?php 
            if(!empty($_POST)){
                //user click/ submit the page
                //1.1 receive user input from form
                $id = strtoupper(trim($_POST["txtID"]));
                $name = trim($_POST["txtName"]);
                $desc = trim($_POST["txtDesc"]);
                $venue = trim($_POST["txtVenue"]);
                $date = trim($_POST["txtDate"]);
                $time = trim($_POST["txtTime"]);
                $price = trim($_POST["txtPrice"]);
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                if(isset($_POST["rbStatus"])){
                $status = trim($_POST["rbStatus"]);
                }else{
                    $status ="";
                }

                //1.2 check/validate/ verify member detail
                $error["event_id"] = checkEventID($id);
                $error["event_name"] =checkEventName($name);
                $error["event_desc"] = checkEventDesc($desc);
                $error["event_venue"] = checkEventVenue($venue);
                $error["status"] = checkEventStatus($status);
                $error["date"] = checkEventDate($date);
                $error["time"] = checkEventTime($time);
                $error["price"] = checkEventPrice($price);


               //NOTE: when the $error array contains null value
               //array_filter() will remove it
               $error = array_filter($error);


               if(empty($error)){
                    //GOOD, sui no error
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                    $sql = "INSERT INTO EVENT (EVENT_ID, EVENT_NAME, EVENT_IMG, EVENT_DESC, EVENT_VENUE, STATUS, DATE, TIME, PRICE) VALUES (?, ?, ? ,?, ?, ? ,?, ?, ?)";

                    $stmt = $con->prepare($sql);

                    $stmt->bind_param("ssssssssd", $id, $name, $img, $desc, $venue, $status, $date, $time, $price);

                    $stmt->execute();

                    if($stmt->affected_rows >0){
                        printf("
                          <div class=''>Event: <b>%s</b> has been inserted. [<a href='maintainEvent.php'>Back</a>] </div>
                      ",$name);
                    }else{
                        echo "Database Error, Usable to insert.Please try again!";
                    }
                    $con->close();
                    $stmt->close();
               }else{
                   //WITH ERROR, display error msg
                   echo "<ul class='error'>";
                   foreach ($error as $value){
                       echo "<li>$value</li>";
                   }
                   echo "</ul>";

               }
            }
            ?>
        <div class="main">
        <h1>Create A New Event</h1>
        <div  class="table-gray">
        <form method="POST" action="" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Event ID:</td>
                    <td><input type="text" name="txtID" value="<?php  echo isset($id)? $id : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td>Event Name:</td>
                    <td><input type="text" name="txtName" value="<?php echo isset($name)? $name : ""; ?>" /></td>
                </tr>
                
                 <tr>
                    <td>Image:</td>
                    <td><input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                        <input type="file" name="fupImage" />
                    </td>
                </tr>
                
                <tr>
                    <td>Event Description:</td>
                    <td><textarea name="txtDesc" rows="6" cols="100"><?php echo isset($desc)? $desc : ""; ?></textarea>
                </tr>
                
                <tr>
                    <td>Event Venue:</td>
                    <td><input type="text" name="txtVenue" value="<?php echo isset($venue)? $venue : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td>Status:</td>
                    <td><input type="radio" name="rbStatus" value="A" 
                            <?php if(isset($status) && $status == "A"){ echo "checked";
                               }else{ echo "";}?>/>Available
                        <input type="radio" name="rbStatus" value="U" 
                            <?php if(isset($status) && $status == "U"){ echo "checked";
                                }else{ echo "";}?>/>Unavailable
                    </td>
                </tr>
                
                <tr>
                    <td>Event Date:</td>
                    <td><input type="date" name="txtDate" value="<?php echo isset($date)? $date : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td>Event Time:</td>
                    <td><input type="time" name="txtTime" value="<?php echo isset($time)? $time : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td>Event Price:</td>
                    <td><input type="text" name="txtPrice" value="<?php echo isset($price)? $price : ""; ?>" /></td>
                </tr>
            </table>
            
            <br />
            <input type="submit" value="Confirm" 
                   name="btnInsert" />
            <input type="button" value="Cancel"
                   name="btnCancel" 
                   onclick="location='maintainEvent.php'"/>
            
        </form>
        </div>
        </div>
        <?php
            include ('footer.php');
        ?>
        <script>
            document.getElementsByName("rbStatus")[0].checked = true;
        </script>
    </body>
</html>
