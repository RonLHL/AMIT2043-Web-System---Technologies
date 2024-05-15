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
    if(isset($_POST["btnYes"])){
        $image = $_POST["image"];
        $path = "uploads/$image";
        echo $path;
        
        unlink($path);
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
        <title>Delete Event</title>
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
        <div class="main">
        <h1>Delete Event</h1>
        <div  class="table-gray">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
                if(isset($_GET['id'])){
                    $id = strtoupper(trim($_GET['id']));
                }else{
                    $id = "";
                }
                
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                $id = $con->real_escape_string($id);
                
                $sql = "SELECT * FROM EVENT WHERE EVENT_ID = '$id'";
                
                $result = $con->query($sql);
                
                if($row = $result->fetch_object()){
                    $id = $row->event_id ;
                    $name = $row->event_name;
                    $img = $row->event_img;
                    $desc = $row->event_desc;
                    $venue = $row->event_venue; 
                    $status = $row->status;
                    $date = $row->date; 
                    $time = $row->time; 
                    $price = $row->price;
                    
                    foreach(glob("uploads/$img.{jpg,jpeg,png,gif}", GLOB_BRACE) as $file) {
                        $basename = pathinfo($file, PATHINFO_BASENAME);              
                    }
                    
                printf("<h2>Are you sure, you want to delete the following Event?</h2>
                        <table border='1' style='background-color:white;'>
                            <tr><td>Event ID: </td><td>%s</td></tr>
                            <tr><td>Event Name: </td><td>%s</td></tr>
                            <tr><td>Image: </td><td><img class='event-img' src='%s' alt=''/></td></tr>
                            <tr><td>Event Description: </td><td>%s</td></tr>
                            <tr><td>Event Venue: </td><td>%s</td></tr>
                            <tr><td>Status: </td><td>%s</td></tr>
                            <tr><td>Date: </td><td>%s</td></tr>
                            <tr><td>Time: </td><td>%s</td></tr>
                            <tr><td>Price: </td><td>%.2f</td></tr>
                        </table>
                        <form action='' method='POST'>
                            <input type='hidden' value='%s' name='hdid' />
                            <input type='hidden' value='%s' name='hdname' />
                            <input type='hidden' name='image' value='%s'/>
                            <input type='submit' value='Yes' name='btnYes' />
                            <input type='button' value='Cancel' name='btnCancel' id='cancel'/>
                        </form>
                        ", $id, $name, $file, $desc, $venue, getEventStatus()[$status], $date, $time, $price, $id, $name, $file);
                    
                }else{
                    echo "Unable to process.[<a href='maintainEvent.php'>Try again</a>]";
                }
                $result->free();
                $con->close();
                
            }else{
                //Post method
                //delete record
                $id = strtoupper(trim($_POST["hdid"]));
                $name = trim($_POST["hdname"]);
                
                $con = new mysqli(DB_HOST,DB_USER, DB_PASS, DB_NAME);
                $sql = "DELETE FROM EVENT WHERE EVENT_ID = ?";
                
                $stmt = $con->prepare($sql);
                
                $stmt->bind_param("s", $id);
                
                $stmt->execute();
                
                if($stmt->affected_rows > 0){
                    printf("
                        <div class='info'>EVENT <b>%s</b> has been deleted!
                        [ <a href='maintainEvent.php'>Back to List</a> ]
                        </div>
                        ", $name);
                    
                    
                }else{
                    echo "<div class = 'error'>Error, cannot delete record.
                        [ <a href='maintainEvent.php'>Try again!</a> ]
                        </div>";
                }
                $con->close();
                $stmt->close();
            }
                
        ?>
        </div>
        </div>
        <?php
            include ('footer.php');
        ?>
        <script>
            document.getElementById('cancel').onclick = function() {
                window.location.href = 'maintainEvent.php';
            };
        </script>
    </body>
</html>
