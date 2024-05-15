<?php
require_once './secret/helper.php';
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
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
              max-width: 330px;
              padding: 1rem;
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
                <h1>Register</h1>
            </div>
        
        <?php 
            if(!empty($_POST)){
                //user click/ submit the page
                //1.1 receive user input from form
                $fullname = strtoupper(trim($_POST["fullname"]));
                $birthdate = trim($_POST["birthdate"]);
                $phone = trim($_POST["phone"]);
                $email = trim($_POST["email"]);
                $username = trim($_POST["username"]);
                $password = trim($_POST["password"]);
                $cfpassword = trim($_POST["cfpassword"]);
                
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                if(isset($_POST["rbgender"])){
                $gender = trim($_POST["rbgender"]);
                }else{
                    $gender ="";
                }

                //1.2 check/validate/ verify member detail
                $error["fullname"] = checkFullName($fullname);
                $error["rbgender"] = checkGender($gender);
                $error["birthdate"] = checkBirthdate($birthdate);
                $error["phone"] = checkPhone($phone);
                $error["email"] = checkEmail($email);
                $error["username"] = checkUserName($username);
                $error["password"] = checkPassword($password);
                $error["cfpassword"] = checkCFPassword($password, $cfpassword);

                $message = ". You Have Been Register Successful.";
                
               //NOTE: when the $error array contains null value
               //array_filter() will remove it
               $error = array_filter($error);


               if(empty($error)){
                    //GOOD, sui no error
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                        $sql = "INSERT INTO USER (USERNAME, FULLNAME, GENDER, BIRTHDATE, PHONE_NUMBER, EMAIL_ADDRESS, PASSWORD) VALUES ( ? ,?, ?, ? ,?, ?, ?)";

                    $stmt = $con->prepare($sql);

                    $stmt->bind_param("sssssss", $username, $fullname, $gender, $birthdate, $phone, $email, $password);

                    $stmt->execute();

                    if($stmt->affected_rows >0){
                        echo '<script>';
                        echo 'alert(" ' . $fullname . $message . '");';  // Show the alert
                        echo 'setTimeout(function() { window.location.href = "login.php"; }, 1000);';  // Delay the redirection
                        echo '</script>';
                        
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
        
        <form method="post" action="">
            <table>
                <tr>
                    <td>Full name: </td>
                    <td><input type="text" name="fullname" class="form-control" value="<?php echo isset($fullname)? $fullname : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>Gender: </td>
                    <td><input type="radio" name="rbgender" value="M" <?php if(isset($gender) && $gender == "M"){ echo "checked";
                               }else{ echo "";}?>/>Male
                        <input type="radio" name="rbgender" value="F" <?php if(isset($gender) && $gender == "F"){ echo "checked";
                               }else{ echo "";}?>/>Female
                    </td>
                </tr>
                <tr>
                    <td>Birth date: </td>
                    <td><input type="date" name="birthdate" class="form-control" value="<?php echo isset($birthdate)? $birthdate : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>Phone number: </td>
                    <td><input type="text" name="phone" class="form-control" value="<?php echo isset($phone)? $phone : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>Email address: </td>
                    <td><input type="email" name="email" class="form-control" value="<?php echo isset($email)? $email : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>User name: </td>
                    <td><input type="text" name="username" class="form-control" value="<?php echo isset($username)? $username : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" class="form-control" value="" /></td>
                </tr>
                <tr>
                    <td>Confirm password: </td>
                    <td><input type="password" name="cfpassword" class="form-control" value="" /></td>
                </tr>
            </table>
            <div style="text-align: center;">
                <input type="submit" value="Sign up" name="submit-register" id="button"/>
                <input type="reset" value="Cancel" name="reset" id="button"/>
            </div>
            <b>Already have an account?</b><a href='login.php'>Log-In Now!</a>
            <br>
        </form>
        
        </main>
        
        <script>
            document.getElementsByName("rbgender")[0].checked = true;
        </script>
        
    </body>
</html>
