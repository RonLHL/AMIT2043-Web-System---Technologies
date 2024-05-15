<?php 
    function validateDate($date, $format = 'Y/m/d') { 
        $d = DateTime::createFromFormat($format, $date); 
        return $d && $d->format($format) === $date; 
    } 

    function detectedError(){
        global $fullName, $gender, $birthDate, $phoneNum, $email, $userName, $password, $cfpassword;
        
        $error = array();
        
        if($fullName == null || $fullName == ""){
            $error["fullName"] = "Please enter your <b>FULL NAME</b>!";
        }else if(strlen($fullName) > 50){
            $error["fullName"] = "Your <b>FULL NAME</b> must not longer than 50 letters!";
        }else if (!preg_match('/^[A-Za-z \/]+$/', $fullName)) {
            $error["fullName"] = "Your <b>FULL NAME</b> contains invalid characters!"; 
        }
        
        if($gender == null){
            $error["gender"] = "Please choose your <b>GENDER</b>!"; 
        }else if(!preg_match("/^[MF]$/", $gender)){
            $error["gender"] = "Invalid <b>GENDER</b>!"; 
        }
        
        if($birthDate == null){
            $error["birthDate"] = "Please selete your <b>BIRTH DATE</b>!";
        }else if(validateDate($birthDate)){
            $error["birthDate"] = "Invalid <b>BIRTH DATE</b>!"; 
        }
        
        if($phoneNum == null){
            $error["phoneNum"] = "Please enter your <b>PHONE NUMBER</b>!";
        }else if(!preg_match('/^01[0-9]-\d{7,8}$/', $phoneNum)){
            $error["phoneNum"] = "Invalid <b>Phone</b>!"; 
        }
        
        if($email == null){
            $error["email"] = "Please enter your <b>EMAIL ADDRESS</b>!";
        }
        
        if($userName == null){
            $error["userName"] = "Please enter your <b>USER NAME</b>!";
        }else if(strlen($userName) > 30){
            $error["userName"] = "Your <b>USER NAME</b> must not longer than 30 letters!";
        }else if (!preg_match('/^[A-Za-z @,\'\.\-\/]+$/', $userName)) {
            $error["userName"] = "Your <b>USER NAME</b> contains invalid characters!"; 
        }
        
        if($password == null){
            $error["password"] = "Please enter your <b>PASSWORD</b>!";
        }else if(strlen($password) < 8){
            $error["password"] = "Your <b>PASSWORD</b> must have atleast 8 letters!";
        }
        
        if($cfpassword == null){
            $error["cfpassword"] = "Please enter the <b>CONFIRM PASSWORD</b>!";
        }else if($cfpassword != $password){
            $error["cfpassword"] = "Your <b>CONFIRM PASSWORD</b> do not match!";
        }
        
        return $error;
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
        <title>Register result</title>
    </head>
    <body>
        Register-result check
        <?php
        if(isset($_POST["submit-register"])){
            $fullName = trim($_POST["register-full-name"]);
            $gender = $_POST["rbgender"];
            $birthDate = $_POST["birth-date"];
            $phoneNum = trim($_POST["register-phone"]);
            $email = trim($_POST["register-email"]);
            $userName = trim($_POST["register-user-name"]);
            $password = trim($_POST["register-password"]);
            $cfpassword = trim($_POST["register-cfpassword"]);
            
            $error = detectedError();
            
            if(empty($error)){
                printf('
                    <h1>Successful register to OUR SOCIETY</h1>
                    <p><b>%s. %s</b></p>
                    <li>User Name: %s</li>
                    <li>Birth date: %s</li>
                    <li>Phone number: %s</li>
                    <li>Email: %s</li>
                    ',($gender == "M")? "Mr" : "Ms" , $fullName, $userName, $birthDate, $phoneNum, $email);
                
            }else{
                echo"<h1>OPPS... Something wrong DETECTED!</h1>";
                
                echo"<ul style='color: red'>";
                
                printf("<li>%s</li>", implode("</li><li>", $error));
                
                echo"</ul>";
            }
                
            echo "[". '<a href= "register.php">Back</a>'."]";
            
        }else{
            echo '<script>location="register.php"</script>';
        }
        ?>
    </body>
</html>
