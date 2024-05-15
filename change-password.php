<?php
include_once './secret/helper.php';

if (isset($_POST["chgPass"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    if(isset($_POST['old-password'])){
        $oldpassword = trim($_POST['old-password']);
    }else{
        $oldpassword = "";
    }
    
    if(isset($_POST['new-password'])){
        $newpassword = trim($_POST['new-password']);
    }else{
        $newpassword = "";
    }
    
    if(isset($_POST['cfpassword'])){
        $cfpassword = trim($_POST['cfpassword']);
    }else{
        $cfpassword = "";
    }
    
    if(isset($_POST["cfChange"])){
        if(strcmp($oldpassword, $password) == 0){
            $error["password"] = checkPassword($password);
            $error["cfpassword"] = checkCFPassword($password, $cfpassword);
            $error = array_filter($error);

            if(empty($error)){
                $con = new mysqli(DB_HOST,DB_USER, DB_PASS, DB_NAME);

                $sql = "UPDATE USER SET PASSWORD = $password WHERE USERNAME = $username";

                $stmt = $con->prepare($sql);

                $stmt->execute();

                if($stmt->affected_rows > 0){
                    printf("
                        <div> <b> Password </b> has been updated!
                        [ <a href='login.php'>Back to Login</a> ]
                        </div>");
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
        }else{
            echo "<div><b style='color: red'>Old password enter wrong! Please Enter Again!<b></div>";   
        }
    }
}else{
    echo "<div>Unable to process. You are Not Allow Change Password <b style='color: red'>WITHOUT<b> Login!";
    echo "[<a href='login.php'>Login</a>]</div>";
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
        <title>Change Password</title>
        <link href="css/Style.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
              rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
              crossorigin="anonymous">
        <style>
            html,
            body {
              height: 100%;
              
            }

            .form-signin {
              max-width: 420px;
              padding: 1rem;
              text-align: center;
            }

            .form-signin .form-floating:focus-within {
              z-index: 2;
            }

            .form-signin input[type="email"] {
              margin-bottom: -1px;
              border-bottom-right-radius: 0;
              border-bottom-left-radius: 0;
            }

            .form-signin input[type="password"] {
              margin-bottom: 10px;
              border-top-left-radius: 0;
              border-top-right-radius: 0;
            }
        </style>
    </head>
    <body class="d-flex align-items-center py-4 bg-body-tertiary">
        <main class="form-signin w-100 m-auto" style="background-color: #B1D6FA; border-radius: 30px">
            <div style="text-align: center;">
                <a href="home.php"><img class = "logo" src="img/logo.jpg" alt="logo" /></a>
                <h1>Change Password</h1>
            </div>
        <div class="main">
            <form method="post" action="">
                <table>
                    <tr>
                        <td>Old Password: </td>
                        <td><input type="password" name="old-password" class="form-control" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td>New Password: </td>
                        <td><input type="password" name="new-password" class="form-control" value="" /></td>
                    </tr>
                    <tr>
                        <td>Confirm Password: </td>
                        <td><input type="password" name="cf-password" class="form-control" value="" /></td>
                    </tr>
                </table>
                
                    <input type="submit" value="Confirm" name="cfChange" id="button"/>
                    <input type="button" value="Back" name="home" id="button" onclick="document.location='admin-event.php'"/>
                
                
                <a href='#'>Forget password?</a>
            
            </form>
        </div>
        </main>
        
    </body>
</html>
