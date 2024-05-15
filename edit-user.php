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
        <title>Edit User Details</title>
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
    </head>
    <body>
        <?php
            include ('admin-header.php');
            require_once './secret/helper.php';
        ?>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            if(isset($_GET['username'])){
                $username = trim($_GET['username']);
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
                }else{
                    echo "Unable to process.[<a href='maintainUser.php'>Try again</a>]";
                }
                $result->free();
                $con->close();
            
            }else{
                $username = strtoupper(trim($_POST["hdName"]));
                $fullname = trim($_POST["txtName"]);
                $birthdate = trim($_POST["txtBirth"]);
                $phone = trim($_POST["txtPhone"]);
                $email = trim($_POST["txtEmail"]);
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                if(isset($_POST["rbGender"])){
                $gender = trim($_POST["rbGender"]);
                }else{
                    $gender ="";
                }

                $error["username"] = checkUserName($username);
                $error["fullname"] =checkFullName($fullname);
                $error["gender"] = checkGender($gender);
                $error["birthdate"] = checkBirthdate($birthdate);
                $error["phone_number"] = checkPhone($phone);
                $error["email_address"] = checkEmail($email);
               
                $error = array_filter($error);


                if(empty($error)){
                    $con = new mysqli(DB_HOST,DB_USER, DB_PASS, DB_NAME);
                    
                    $sql = "UPDATE USER SET FULLNAME = ?, GENDER = ?, BIRTHDATE = ?, PHONE_NUMBER = ?, EMAIL_ADDRESS = ? WHERE USERNAME = ?";

                    $stmt = $con->prepare($sql);

                    $stmt->bind_param("ssssss", $fullname, $gender, $birthdate, $phone, $email, $username);
                    $stmt->execute();

                    if($stmt->affected_rows > 0){
                        printf("
                            <div class='info'>User information <b>%s</b> has been updated!
                            [ <a href='maintainUser.php'>Back to List</a> ]
                            </div>
                            ", $username);
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
        
        <div class="main">
        <h1>Edit User Details</h1>
        <div class="table-gray">
        <form method="POST" action="" enctype="multipart/form-data">
            <table>
                <tr>
                    <td class="row-label"><b>Username  :   </b></td>
                    <td><label><?php echo $username; ?><input type="hidden" name="hdName" value="<?php echo $username; ?>" /></label></td>
                </tr>
                
                <tr>
                    <td class="row-label"><b>Full Name  :   </b></td>
                    <td><input type="text" name="txtName" value="<?php echo isset($fullname)? $fullname : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td class="row-label"><b>Gender   :   </b></td>
                    <td><input type="radio" name="rbGender" value="M" 
                            <?php if(isset($gender) && $gender == "M"){ echo "checked";
                               }else{ echo "";}?>/>Male
                        <input type="radio" name="rbStatus" value="F" 
                            <?php if(isset($gender) && $gender == "F"){ echo "checked";
                                }else{ echo "";}?>/>Female
                    </td>
                </tr>
                
                 <tr>
                    <td class="row-label"><b>Birth Date   :   </b></td>
                    <td><input type="date" name="txtBirth" value="<?php echo isset($birthdate)? $birthdate : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td class="row-label"><b>Phone Number   :   </b></td>
                    <td><input type="text" name="txtPhone" value="<?php echo isset($phone)? $phone : ""; ?>" /></td>
                </tr>
                
                <tr>
                    <td class="row-label"><b>Email Address   :   </b></td>
                    <td><input type="text" name="txtEmail" value="<?php echo isset($email)? $email : ""; ?>" /></td>
                </tr>
                
            </table>
            <br />
            <input class="button" type="submit" value="Confirm" name="btnInsert" />
            <input class="button" type="button" value="Cancel" name="btnCancel" onclick="location='maintainUser.php'"/>
        </form>
        </div>
        </div>
        <?php
            include ('footer.php');
        ?>
    </body>
</html>
