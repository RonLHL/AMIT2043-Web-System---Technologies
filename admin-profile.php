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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Admin-profile</title>
        <link href="css/Style.css" rel="stylesheet" type="text/css"/>
        <link href="css/profile.css" rel="stylesheet" type="text/css"/>
        <style>
            .button{
                float: right;
                color: white;
                text-align: center;
                padding:20px 26px;
                text-decoration: none;
                font-size: 26px;
                background-color: gray;
            }
        </style>
    </head>
    <body>
        <?php
        include_once './secret/helper.php';
        ?>
        <main class="main">
            <div class="topbar">
                <form action="" method="POST">
                    <input class="button" type="submit" name="logout" value="Logout"/>
                </form>
            </div>
            <div class="row">
                <div class="col-md-4 mt-1">
                    <div class="card text-center sidebar">
                        <div class="card-side-bar">
                            <img src="img/profile_default.png" class="profile-img">
                            <div class="mt-3">
                                <br>
                                <a href="admin-event.php">Home</a>
                                <a href="maintainEvent.php">Maintain Event</a>
                                <a href="maintainUser.php">Maintain Member</a>
                                <a href="feedbackAdmin.php">View Feedback</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card content">
                <div class="content">
                    <h1 class="m-3 pt-3"  style="text-align: center">Profile</h1>
                    <div class="card-body">
                        <?php
                        if (isset($_COOKIE["login-user"])) {
                            $username = $_COOKIE["login-user"];
                            
                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                            if($con -> connect_error){
                                die("Connection failed: ". $con->connect_error);
                            }

                            $sql = "SELECT * FROM USER WHERE USERNAME = '$username'";

                            //pass sql into connection to execute
                            $result = $con->query($sql);
                            if($row = $result->fetch_object()){
                            $fullname = $row->fullname ;
                            $gender = $row->gender;
                            $birthDate = $row->birthdate;
                            $phone = $row->phone_number;
                            $email = $row->email_address; 
                            $password = $row->password;
                            }else{
                                echo "Unable to process.[<a href='login.php'>Try again</a>]";
                            }
                            $result->free();
                            $con->close();
                        }
                        
                        ?>
                        <form action="change-password.php" method="POST">
                        <table>
                            <tr>
                                <td>Full Name : </td>
                                <td><label><?php echo isset($fullname)? $fullname : ""?></label></td>
                            </tr>
                            <tr>
                                <td>Gender : </td>
                                <td><label><?php echo isset($gender)? getGender()[$gender] : "";?></td>
                            </tr>
                            <tr>
                                <td>User Name : </td>
                                <td><?php echo isset($username)? $username : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth : </td>
                                <td><?php echo isset($birthDate)? $birthDate : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Phone number : </td>
                                <td><?php echo isset($phone)? $phone : "";?></td>
                            </tr>
                            <tr>
                                <td>Email : </td>
                                <td><?php echo isset($email)? $email : "";?></td>
                            </tr>
                            <tr>
                                <td>Password </td>
                                <input type="hidden" name="password" value="<?php echo isset($password)? $password : ""; ?>">
                                <input type="hidden" name="username" value="<?php echo isset($username)? $username : ""; ?>">
                                <td><input type="submit" name="chgPass" value="Change Password"/></td>
                            </tr>
                        </table>
                        </form>
                        <?php
                            
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
