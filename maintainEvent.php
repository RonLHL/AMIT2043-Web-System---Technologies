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
            "status" => "Status",
            "date" => "Date",
            "time" => "Time",
            "price" => "Price"
    );
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Event</title>
        <link href="css/Style.css" rel="stylesheet" type="text/css"/>
        <style>
            table{
                background-color: white;
            }
            tr:nth-child(even) {background-color: #f2f2f2;}
            .eventId, .eName, .venue, .status, .date, .time, .price{
                text-align: center;
                width: 6%;
            }
            .desc{
                width: 30%;
                text-align: justify;
            }
            .button{
                width: 10%;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <?php
            include ('admin-header.php');
            require_once './secret/helper-event.php';
        ?>
        <div class="main">
        <h1>Events</h1>
        <p>Status: 
        <?php
        //search
            if(empty($_GET)){
                //user never click anything
                $sort = "event_id"; //default
                $order = "ASC";
                $status = "%";
                $name = "%";
            }else{
                //when user click a column
                $sort = $_GET["sort"];
                $order = $_GET["order"];
                $status = $_GET["status"];
                $name = $_GET['event_name'];
            }
            printf("<a href='?sort=%s&order=%s&event_name=%s&status=%s'>All</a>", $sort, $order, '%', '%'); //($order == "ASC")? "DESC" : "ASC"
            foreach (getEventStatus() as $key => $value) {
                printf("|<a href='?sort=%s&order=%s&event_name=%s&status=%s'>%s</a>", $sort, $order, '%', $key, $key);
            }
        ?>  
        </p>
            <form action="" method="GET">
                <input type="hidden" name="sort" value="<?php echo isset($sort)? $sort : "%";?>">
            <input type="hidden" name="order" value="<?php echo isset($order)? $order : "%";?>">
            <input type="hidden" name="status" value="<?php echo isset($status)? $status : "%";?>">
                <input type="search" name="event_name" placeholder="Searching"/>
            </form>
            <br>
        <?php
            if(isset($_POST["btnDelete"])){
                if(isset($_POST["checked"])){
                    $check = $_POST["checked"];
                }else{
                    $check = null;
                }
                
                if(!empty($check)){
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    
                    $escape = array();
                    
                    foreach($check as $value) {
                        //clean id to remove special char
                        $escape[] = $con->real_escape_string($value);
                    }
                    
                    $sql = "DELETE FROM EVENT WHERE EVENT_ID IN ('" . implode("', '", $escape) . "')";
                    
                    if($con->query($sql)){
                        printf("<div class='info'> <b>%d</b> record(s) has been deleted.</div>", $con->affected_rows);
                    }
                    $con->close();
                }
            }
            
            
        ?>
        
        <form action="" method="POST">
            <table border="1" cellspacing="0" cellpadding="5">
                <tr>
                    <th></th>
                    <?php 
                        foreach ($header as $key => $value) {
                            if($key == $sort){
                                printf("<th>
                                    <a href='?sort=%s&order=%s&event_name=%s&status=%s'>%s</a>
                                    <img src='img/%s' />
                                    </th>",$key, ($order == "ASC")? "DESC" : "ASC", $name, $status, $value, ($order == "ASC") ? 'asc.png' : 'desc.png');
                            }else{
                                //sort - DB column name
                                printf("<th>
                                    <a href='?sort=%s&order=ASC&event_name=%s&status=%s'>%s</a>
                                    </th>",$key, $name, $status, $value);
                            }
                        } 
                    ?>
                    <th><a href="add-event.php">Create New Event</a></th>
                </tr>
                
                <?php
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                    if($con -> connect_error){
                        die("Connection failed: ". $con->connect_error);
                    }

                    $sql = "SELECT * FROM Event WHERE EVENT_NAME LIKE '$name%' AND Status LIKE '$status' ORDER BY $sort $order ";

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
                            printf("
                                <tr>
                                <td><input type='checkbox' name='checked[]' value='%s' /></td>
                                <td class='eventId'>%s</td>
                                <td class='eName'>%s</td>
                                <td><img class='event-img' src='%s' alt=''/></td>
                                <td class='desc'>%s</td>
                                <td class='venue'>%s</td>
                                <td class='status'>%s</td>
                                <td class='date'>%s</td>
                                <td class='time'>%s</td>
                                <td class='price'>RM%.2f</td>
                                <td class='button'>
                                    <a href='edit-event.php?id=%s'>Edit</a>
                                    <a href='delete-event.php?id=%s'>Delete</a>
                                </td>
                                </tr>",$row->event_id, $row->event_id , $row->event_name, $file
                                      ,$row->event_desc ,$row->event_venue, getEventStatus()[$row->status]
                                      ,$row->date, $row->time, $row->price
                                      ,$row->event_id, $row->event_id);
                        }
                    }


                    printf("<tr>
                        <td colspan='11'>Have %d Event(s) existing.
                         [ <a href='add-event.php'>Add New Event</a> ]
                        </td>

                           </tr>", $result->num_rows);

                    $result->free();
                    $con->close();
                ?>
                    
                <tr>
                    <td colspan="10"><input class="delete-all" type="submit" value="Delete All" name="btnDelete" onclick="return confirm('This will delete all records.\n Are You Sure?')"/></td>
                </tr>
            </table>
        </form>
        </div>
        <?php
            include ('footer.php');
        ?>
    </body>
</html>
