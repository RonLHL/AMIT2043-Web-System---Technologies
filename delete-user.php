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
        <title>Delete User</title>
        <link href="css/Style.css" rel="stylesheet" type="text/css"/>
        <style>
            .table-gray{
                background-color: #D6F0FC;
                margin-left: 450px;
                margin-right: 450px;
                padding: 50px;
                font-size: 24px;
                border-radius: 25%;
                
            }
            table{
                height: 500px;
                font-size: 24px;
            }
            
            .row-label{
                text-align: right;
                
            }
            .button{
                border-radius: 25%;
                background-color: skyblue;
            }
        </style>
        </style>
    </head>
    <body>
        <?php
            include ('admin-header.php');
            require_once './secret/helper.php';
        ?>
        <div class="main">
        <h1>Delete User</h1>
        <div  class="table-gray">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
                if(isset($_GET['username'])){
                    $username = strtoupper(trim($_GET['username']));
                }else{
                    $username = "";
                }
                
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                $username = $con->real_escape_string($username);
                
                $sql = "SELECT * FROM USER WHERE USERNAME = '$username'";
                
                $result = $con->query($sql);
                
                if($row = $result->fetch_object()){
                    $username = $row->username ;
                    $fullname = $row->fullname;
                    $gender = $row->gender;
                    $birthdate = $row->birthdate;
                    $phone = $row->phone_number; 
                    $email = $row->email_address;
                    
                printf("<h2>Are you sure, you want to delete the following User?</h2>
                        <table>
                            <tr><td class='row-label'><b>User Name   : </b></td><td>%s</td></tr>
                            <tr><td class='row-label'><b>Full Name   : </b></td><td>%s</td></tr>
                            <tr><td class='row-label'><b>Gender   : </b></td><td>%s</td></tr>
                            <tr><td class='row-label'><b>Birth Date   : </b></td><td>%s</td></tr>
                            <tr><td class='row-label'><b>Phone Number   : </b></td><td>%s</td></tr>
                            <tr><td class='row-label'><b>Email Address   : </b></td><td>%s</td></tr>
                        </table>
                        <form action='' method='POST'>
                            <input type='hidden' value='%s' name='hdUName' />
                            <input type='hidden' value='%s' name='hdFName' />
                            <input class='button' type='submit' value='Yes' name='btnYes' />
                            <input class='button' type='button' value='Cancel' name='btnCancel' id='cancel'/>
                        </form>
                        ", $username, $fullname, getGender()[$gender], $birthdate, $phone, $email, $username, $fullname);
                    
                }else{
                    echo "Unable to process.[<a href='maintainUser.php'>Try again</a>]";
                }
                $result->free();
                $con->close();
                
            }else{
                //Post method
                //delete record
                $username = strtoupper(trim($_POST["hdUName"]));
                $fullname = trim($_POST["hdFName"]);
                
                $con = new mysqli(DB_HOST,DB_USER, DB_PASS, DB_NAME);
                $sql = "DELETE FROM USER WHERE USERNAME = ?";
                
                $stmt = $con->prepare($sql);
                
                $stmt->bind_param("s", $username);
                
                $stmt->execute();
                
                if($stmt->affected_rows > 0){
                    printf("
                        <div class='info'>User <b>%s</b> has been deleted!
                        [ <a href='maintainUser.php'>Back to List</a> ]
                        </div>
                        ", $fullname);
                    
                    
                }else{
                    echo "<div class = 'error'>Error, cannot delete record.
                        [ <a href='maintainUser.php'>Try again!</a> ]
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
                window.location.href = 'maintainUser.php';
            };
        </script>
            
    </body>
</html>
