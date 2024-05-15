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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Profile</title>
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <link href="css/profile.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            html, body, .main {
                height: 100%;
            }
            .profile-section {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 10px;
                margin-bottom: 20px;
            }
            .profile-heading {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 20px;
                text-align: center;
            }
            .profile-info {
                font-size: 18px;
                margin-bottom: 10px;
            }
            .errorMsg{
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="profile-section">
                        <?php
                        include_once './secret/helperMember.php';
                        ?>

                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "GET") {
                            // Retrieve username from the URL
                            if (isset($_GET['username'])) {
                                $userName = strtoupper(trim($_GET['username']));
                            } else {
                                $userName = "";
                            }
                            
                            
                            // Create database connection
                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                            // Prepare SQL statement to retrieve user data based on username
                            $sql = "SELECT * FROM user WHERE username = '$userName'";
                            $stmt = $con->prepare($sql);
                            //$stmt->bind_param("s", $userName);
                            $stmt->execute();

                            // Fetch the result
                            $result = $stmt->get_result();

                            // Check if the record exists
                            if ($row = $result->fetch_object()) {
                                // Populate form fields with retrieved data
                                $fullName = $row->fullname;
                                $gender = $row->gender;
                                $birthDate = $row->birthdate;
                                $phoneNumber = $row->phone_number;
                                $email = $row->email_address;
                                $password = $row->password;
                            } else {
                                // Record not found
                                echo "Unable to find user with username: $userName";
                            }

                            // Close prepared statement and database connection
                            $result->close();
                            $stmt->close();
                            $con->close();
                        } else {
                            $fullName = trim($_POST["mbFullName"]);
                            $gender = isset($_POST["mbGender"]) ? trim($_POST["mbGender"]) : "";
                            $birthDate = trim($_POST["mbBirthdate"]);
                            $phoneNumber = trim($_POST["mbPhoneNum"]);
                            $email = trim($_POST["mbEmail"]);

                            $errors = array(
                                "mbFullName" => mbFullName(),
                                "mbGender" => mbGender(),
                                "mbBirthdate" => mbBirthdate(),
                                "mbPhoneNum" => mbPhoneNumber(),
                                "mbEmail" => mbEmail()
                            );

                            $hasError = false;
                            foreach ($errors as $error) {
                                if (!empty($error)) {
                                    $hasError = true;
                                }
                            }

                            if (!$hasError) {
                                // Create connection
                                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                                // Prepare SQL statement
                                $sql = "UPDATE USER SET FULLNAME = ?, GENDER = ?, BIRTHDATE = ?, PHONE_NUMBER = ?, EMAIL_ADDRESS = ? WHERE USERNAME = ?";
                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssss", $fullName, $gender, $birthDate, $phoneNumber, $email, $userName);
                                $stmt->execute();

                                // Check if update was successful
                                if ($stmt->affected_rows > 0) {
                                    echo "<div class='info'>Profile <b>$userName</b> has been updated.</div>";
                                } else {
                                    echo "Database Error, unable to update. Please try again!";
                                }

                                $stmt->close();
                                $con->close();
                            }
                        }
                        ?>

                        <h1 class="profile-heading">Member Profile</h1>
                        <form method="POST" action="">
                            <table>
                                <tr>
                                    <td class="profile-info"><strong>Full Name:</strong></td>
                                    <td><input type="text" name="mbFullName" value="<?php echo isset($fullName) ? $fullName : ""; ?>" /></td>
                                    <td><span class="errorMsg"><?php echo $errors["mbFullName"] ?? ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="profile-info"><strong>Gender:</strong></td>
                                    <td><input type="radio" name="mbGender" value="M" 
                                        <?php
                                        if (isset($gender) && $gender == 'M') {
                                            echo "checked";
                                        } else {
                                            echo "";
                                        }
                                        ?> />👨 Male</td>
                                    <td><input type="radio" name="mbGender" value="F" 
                                        <?php
                                        if (isset($gender) && $gender == 'F') {
                                            echo "checked";
                                        } else {
                                            echo "";
                                        }
                                        ?>/>👩 Female</td>
                                </tr>
                                <tr>
                                    <td><span class="errorMsg"><?php echo $errors["mbGender"] ?? ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="profile-info"><strong>Date of Birth:</strong></td>
                                    <td><input type="date" name="mbBirthdate" value="<?php echo isset($birthDate) ? $birthDate : ""; ?>" /></td>
                                <td><span class="errorMsg"><?php echo $errors["mbBirthdate"] ?? ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="profile-info"><strong>Phone number:</strong></td>
                                    <td><input type="text" name="mbPhoneNum" value="<?php echo isset($phoneNumber) ? $phoneNumber : ""; ?>" /></td>
                                <td><span class="errorMsg"><?php echo $errors["mbPhoneNum"] ?? ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="profile-info"><strong>Email:</strong></td>
                                    <td><input type="text" name="mbEmail" value="<?php echo isset($email) ? $email : ""; ?>" /></td>
                                <td><span class="errorMsg"><?php echo $errors["mbEmail"] ?? ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" name="mbPassword" value="" size="15" /></td>
                                </tr>
                            </table>
                            <input type="submit" value="Edit Profile" name="btnEditMbProfile" />
                            <input type="reset" value="Cancel" name="btnCancelEditMember" onclick="location.href = 'memberProfile.php'"/>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>