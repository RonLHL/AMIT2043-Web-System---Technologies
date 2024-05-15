<?php
include_once './secret/helper.php';
if(isset($_POST["submit-login"])){
    if(isset($_POST['login-user'])){
        $username = trim($_POST['login-user']);
    }else{
        $username = "";
    }
    $chkpass = trim($_POST["login-password"]);
    
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
    if($con -> connect_error){
        die("Connection failed: ". $con->connect_error);
    }
                
    $sql = "SELECT PASSWORD FROM USER WHERE USERNAME = '$username'";
                
    $result = $con->query($sql);
        
    if($result->num_rows > 0){
        //user found
        if($row = $result->fetch_object()){
        $password = $row->PASSWORD;    
                
        if($username == "admin"){
            if($chkpass == $password){
                setcookie('login-user', $username, time() + 7 * 24 * 60 * 60);
                $message = "Admin, Login Successful.";
                echo '<script>';
                echo 'alert("' . $message . '");';  // Show the alert
                echo 'setTimeout(function() { window.location.href = "admin-event.php"; }, 1000);';  // Delay the redirection
                echo '</script>';
            }else{
                $message = "Password Wrong, Try again.";
                echo '<script>';
                echo 'alert("' . $message . '");';  // Show the alert
                echo '</script>';
            }
        }else{
            if($chkpass == $password){
                setcookie('login-user', $username, time() + 7 * 24 * 60 * 60);
                $message = "Login Successful.";
                echo '<script>';
                echo 'alert("' . $message . '");';  // Show the alert
                echo 'setTimeout(function() { window.location.href = "memberEvent.php"; }, 1000);';  // Delay the redirection
                echo '</script>';
            }else{
                $message = "Password Wrong, Try again.";
                echo '<script>';
                echo 'alert("' . $message . '");';  // Show the alert
                echo '</script>';
            }
        }
        }else{
            //user not found
            $message = "Username NOT found.";
            echo '<script>';
            echo 'alert("' . $message . '");';  // Show the alert
            echo '</script>';
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
        <title>Login</title>
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
                <h1>Login</h1>
            </div>
        
        <form method="POST" action="">
            <table>
                <tr>
                    <td>User name: </td>
                    <td><input type="text" name="login-user" class="form-control" value="<?php echo isset($username)? $username : "";?>" />
                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="login-password" class="form-control" value="" /></td>
                </tr>
            </table>
            
            <div style="text-align: center;">
                <input type="submit" value="Sign In" name="submit-login" id="button"/>
                <input type="reset" value="Cancel" name="reset" id="button"/>
                <input type="button" value="Back" name="home" id="button" onclick="document.location='home.php'"/>
            </div>
            <b>Haven't register? Click </b><a href='register.php'>Register Now!</a>
            <br>
            <a href='#'>Forget password?</a>
            
        </form>
        </main>
    </body>
</html>
