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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>View History</title>
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
        <main class="main">
            <div class="topbar mb-3">
                <a href="#" name="logout">Logout</a>
            </div>
            <?php include_once "./secret/helperMember.php"?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link mb-3" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"><a href="memberEvent.php">Home</a></button>
                            <button class="nav-link active mb-3" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Member Profile</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="profile-section">
                                    <form action="" method="GET">
                                    <h1 class="profile-heading">Member Profile</h1>
                                    <div class="profile-info"><strong>User Name:</strong> <?php echo isset($username) ? $username : ''; ?></div>
                                    <div class="profile-info"><strong>Full Name:</strong> <?php echo isset($fullname) ? $fullname : ''; ?></div>
                                    <div class="profile-info"><strong>Gender:</strong> <?php echo isset($gender) ? $gender : ''; ?></div>
                                    <div class="profile-info"><strong>Date of Birth:</strong> <?php echo isset($birthDate) ? $birthDate : ''; ?></div>
                                    <div class="profile-info"><strong>Phone number:</strong> <?php echo isset($phone) ? $phone : ''; ?></div>
                                    <div class="profile-info"><strong>Email:</strong> <?php echo isset($email) ? $email : ''; ?></div>
                                    <?php
                                        printf("<a href='./editMemberProfile.php?username=%s'><input type='button' value='Edit Profile' /></a>",$username);
                                    ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>