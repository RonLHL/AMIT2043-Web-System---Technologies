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
            "username" => "Username",
            "fullname" => "Full Name",
            "gender" => "Gender",
            "birthdate" => "Birthdate",
            "phone_number" => "Phone Number",
            "email_address" => "Email",
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
        <title>Users</title>
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
                border-radius: 25%;
                background-color: skyblue;
            }
            .username, .fullname{
                text-align: left;
            }
            .gender, .birthdate, .phone, .email{
                text-align: center;
            }
            .rbCheck{
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <?php
            include ('admin-header.php');
            require_once './secret/helper.php';
        ?>
        <div class="main">
        <h1>Users</h1>
        <p>Gender: 
        <?php
        //search
            if(empty($_GET)){
                //user never click anything
                $sort = "username"; //default
                $order = "ASC";
                $gender = "%";
                $name = "%";
            }else{
                //when user click a column
                $sort = $_GET["sort"];
                $order = $_GET["order"];
                $gender = $_GET["gender"];
                $name = $_GET['username'];
            }
            printf("<a href='?sort=%s&order=%s&username=%s&gender=%s'>All</a>", $sort, $order, '%', '%'); //($order == "ASC")? "DESC" : "ASC"
            foreach (getGender() as $key => $value) {
                printf("|<a href='?sort=%s&order=%s&username=%s&gender=%s'>%s</a>", $sort, $order, '%', $key, $key);
            }
        ?>
        </p>
            <form action="" method="GET">
                <input type="hidden" name="sort" value="<?php echo isset($sort)? $sort : "%";?>">
                <input type="hidden" name="order" value="<?php echo isset($order)? $order : "%";?>">
                <input type="hidden" name="gender" value="<?php echo isset($gender)? $gender : "%";?>">
                <input type="search" name="username" placeholder="Searching"/>
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
                    
                    $sql = "DELETE FROM USER WHERE USERNAME IN ('" . implode("', '", $escape) . "')";
                    
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
                                    <a href='?sort=%s&order=%s&username=%s&gender=%s'>%s</a>
                                    <img src='img/%s' />
                                    </th>",$key, ($order == "ASC")? "DESC" : "ASC", $name, $gender, $value, ($order == "ASC") ? 'asc.png' : 'desc.png');
                            }else{
                                //sort - DB column name
                                printf("<th>
                                    <a href='?sort=%s&order=ASC&username=%s&gender=%s'>%s</a>
                                    </th>",$key, $name, $gender, $value);
                            }
                        } 
                    ?>
                </tr>
                
                <?php
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                    if($con -> connect_error){
                        die("Connection failed: ". $con->connect_error);
                    }

                    $sql = "SELECT * FROM USER WHERE USERNAME LIKE '$name%' AND GENDER LIKE '$gender' ORDER BY $sort $order ";

                    //pass sql into connection to execute
                    $result = $con->query($sql);

                    //check if $result contains record??
                    if($result->num_rows >0){
                        //record returned
                        
                        while($row = $result->fetch_object()){
                            printf("
                                <tr>
                                <td><input class='rbCheck' type='checkbox' name='checked[]' value='%s' /></td>
                                <td class='username'>%s</td>
                                <td class='fullname'>%s</td>
                                <td class='gender'>%s</td>
                                <td class='birthdate'>%s</td>
                                <td class='phone'>%s</td>
                                <td class='email'>%s</td>
                                <td class='button'>
                                    <a href='edit-user.php?username=%s'>Edit</a>
                                    <a href='delete-user.php?username=%s'>Delete</a>
                                </td>
                                </tr>",$row->username, $row->username, $row->fullname
                                      , getGender()[$row->gender], $row->birthdate, $row->phone_number
                                      ,$row->email_address, $row->username, $row->username);
                        }
                    }


                    printf("<tr><td colspan='8'>Have %d User(s) existing.</td></tr>", $result->num_rows);

                    $result->free();
                    $con->close();
                ?>
                    
                <tr>
                    <td colspan="10"><input class="delete-all" type="submit" value="Delete All" name="btnDelete" onclick="return confirm('This will delete all records.\n Are You Sure?')"/></td>
                </tr>
            </table>
        </form>
            
            <?php
            include ('footer.php');
            ?>
    </body>
</html>
