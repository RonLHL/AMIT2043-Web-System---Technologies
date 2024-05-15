<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Member header</title>
        <link href="css/css.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <header>
            <h1> 
                <nav class="nav">
                    <img class = "logo" src="img/logo.jpg" alt=""/>
                    <span>ROY Gaming Society</span>
                </nav>
            </h1>
            <h2>
                <div class="d-flex">
                    <div class="p-2 flex-grow-1">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="memberEvent.php">Event</a></li>
                            <li class="nav-item"><a class="nav-link" href="paymentHistory.php">Payment History</a></li>
                            <li class="nav-item"><a class="nav-link" href="feedbackHistory.php">Feedback History</a></li>
                        </ul>
                    </div>
                    <div class="p-2">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link" href="memberProfile.php"><img src="img/profile_default.png" class="memberProfile"></a></li>
                            <li class="nav-item"><a class="nav-link" href="cart.php"><img src="img/cart.jpg" class="memberProfile"></a></li>
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
