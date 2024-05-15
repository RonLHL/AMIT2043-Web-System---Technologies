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
    if(empty($_GET)){
        $name = "%";
    }else{
        $name = $_GET['event_name'];
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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Member Event Page</title>
        <style>
            .main{
                text-align: center;
            }
            .img{
                width: 50%;
            }
            .desc{
                text-align: justify;
                padding: 2%;
                font-size: 24px;
            }
            .line{
                font-size: 18px;
            }
            
            .joinEvent{
                border-radius: 50%;
                background-color: lightblue;
                width: 120px;
                height: 80px;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <?php
            include ('memberHeader.php');
            require_once './secret/helper-event.php';
        ?>
        <div class="main">
            <h1>Existing Event Info</h1>
            <form action="" method="GET">
                <input class="search" type="search" name="event_name" placeholder="Searching..."/>
            </form>
            <br>
            <form>
            <?php
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                if($con -> connect_error){
                    die("Connection failed: ". $con->connect_error);
                }

                $sql = "SELECT * FROM Event WHERE EVENT_NAME LIKE '$name%' AND STATUS = 'A'";

                //pass sql into connection to execute
                $result = $con->query($sql);

                //check if $result contains record??
                if($result->num_rows >0){
                   //record returned
                    while($row = $result->fetch_object()){
                        $image = $row->event_img;
                        foreach(glob("uploads/$image.{jpg,jpeg,png,gif}", GLOB_BRACE) as $file) {
                            $basename = pathinfo($file, PATHINFO_BASENAME);              
                        }
                        $time = date('g:i A', strtotime($row->time));
                        
                        printf("<div class='event-info'>
                            <h1>%s</h1>
                            <img class='img' src='%s' alt=''/>
                            <p class='desc'>%s</p>
                            <div class='line'>Venue: %s <br>
                                Status: %s <br>
                                Date: %s | Time: %s | Price: RM%.2f <br>
                                </tr>", $row->event_name, $file,$row->event_desc 
                                      ,$row->event_venue, getEventStatus()[$row->status]
                                      ,$row->date, $time, $row->price
                                      ,$row->event_id);
                        }
                    } else {
                        echo "<h1 style='color:red'>Existing Event <b>NOT</b> Found!</h1>";
                    }

                    $result->free();
                    $con->close();
                ?>
            </form>
        </div>
        <?php
            include ('footer.php');
        ?>
    </body>
</html>
