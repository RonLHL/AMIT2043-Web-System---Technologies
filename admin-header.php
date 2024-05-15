<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Header</title>
        <link href="css/css.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <header>
            <h1> 
                <nav class="nav">
                    <img class = "logo" src="img/logo.jpg" alt=""/>
                    <span>Gaming Society</span>
                </nav>
            </h1>
            <h2>
                <div class="d-flex">
                    <div class="p-2 flex-grow-1">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin-event.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="maintainEvent.php">Maintain Event</a></li>
                            <li class="nav-item"><a class="nav-link" href="maintainUser.php">Maintain Member</a></li>
                            <li class="nav-item"><a class="nav-link" href="feedbackAdmin.php">View Feedback</a></li>
                        </ul>
                    </div>
                    <div class="p-2">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin-profile.php">Profile</a></li>
                            <li class="nav-item">
                            <form action="" method="POST">
                                <input class="nav-link active" type="submit" name="logout" value="Logout"/>
                            </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </h2>
        </header>
    </body>
</html>
