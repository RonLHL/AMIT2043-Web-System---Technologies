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

$username = $_GET['username'];
$eventIDs = $_GET['event_id'];
$total = $_GET['total'];
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Payment</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <?php include_once './secret/helperCart.php';?>
        <?php include ('memberHeader.php'); ?>
        <?php
        if (!empty($_POST)) {
            //user click/ submit the page
            //1.1 receive user input from form
            $username = strtoupper(trim($_POST["username"]));
            $eventID = trim($_POST["eventID"]);
            $cardName = trim($_POST["nameOnCard"]);
            $cardNumber = trim($_POST["cardNumber"]);
            $expDate = trim($_POST["expiryDate"]);
            $CVV = trim($_POST["cvv"]);
            $amount = trim($_POST["totalAmount"]);
            $paymentMethod = trim($_POST["paymentMethod"]);

            //1.2 check/validate/ verify member detail

            $error["nameOnCard"] = checkUserName($cardName);
            $error["cardNumber"] = checkCardNumber($cardNumber);
            $error["expiryDate"] = checkExpiryDate($expDate);
            $error["ccv"] = checkCVV($CVV);
            $error["totalaAmount"] = checkTotalAmount($amount);
            $error["paymentMethod"] = checkPaymentMethod($paymentMethod);

            $message = ". You Have Been Register Successful.";

            //NOTE: when the $error array contains null value
            //array_filter() will remove it
            $error = array_filter($error);

            if (empty($error)) {
                //GOOD, sui no error
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

                $sql = "INSERT INTO PAYMENT (PAYMENT_ID, USERNAME, EVENT_ID, CARDNAME, CARDNUMBER, EXPDATE, CVV, AMOUNT, PAYMENT_METHOD) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $con->prepare($sql);

                $stmt->bind_param("ssssssids", $username, $eventID, $cardName, $cardNumber, $expDate, $CVV, $amount, $paymentMethod);

                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo '<script>';
                    echo 'alert(" ' . $fullname . $message . '");';  // Show the alert
                    echo 'setTimeout(function() { window.location.href = "login.php"; }, 1000);';  // Delay the redirection
                    echo '</script>';
                } else {
                    echo "Database Error, Usable to insert.Please try again!";
                }
                $con->close();
                $stmt->close();
            } else {
                //WITH ERROR, display error msg
                echo "<ul class='error'>";
                foreach ($error as $value) {
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
        ?>
        <div class="container mt-5">
            <div class="row">
                <form action="" method="POST">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" name="username" placeholder="username" value="<?php echo isset($username) ? $username : ""; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="Event" class="form-label">Event:</label>
                            <input type="text" class="form-control" name="eventID" placeholder="E0001" value="<?php echo isset($eventIDs) ? $eventIDs : ""; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="paymentType" class="form-label">Payment Type:</label>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select a Payment Method</button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" type="button" onclick="updateValue('Visa')">Visa</button></li>
                                <li><button class="dropdown-item" type="button" onclick="updateValue('Master')">Master</button></li>
                                <li><button class="dropdown-item" type="button" onclick="updateValue('Touch N Go')">Touch N Go</button></li>
                            </ul>
                        </div>
                        <div>
                            <span>Selected Payment Method: </span><span id="selectedPaymentMethod">None</span>
                        </div>
                        <input type="hidden" id="paymentMethod" name="paymentMethod" value="">
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nameOnCard" class="form-label">Name On Card:</label>
                            <input type="text" class="form-control" name="nameOnCard" placeholder="Name On Card">
                        </div>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card Number:</label>
                            <input type="text" class="form-control" name="cardNumber" placeholder="xxxx-xxxx-xxxx-xxxx">
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date:</label>
                            <input type="date" class="form-control" name="expiryDate" value="<?php echo htmlspecialchars($expDate ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV:</label>
                            <input type="text" class="form-control" name="cvv" placeholder="xxx">
                        </div>
                        <div class="mb-3">
                            <label for="totalAmount" class="form-label">Total Amount:</label>
                            <input type="text" class="form-control" name="totalAmount" placeholder="RMxxx" value="<?php echo isset($total) ? $total : ""; ?>" readonly>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-info btn-md" href="#" role="button"><input type="submit" name="btnSubmit" value="CheckOut"></a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function updateValue(value) {
                // Update the text of the dropdown button with the selected value
                document.getElementById('paymentMethod').value = value;
                document.getElementById('selectedPaymentMethod').innerText = value;
            }
        </script>
        <?php include ('footer.php'); ?>
    </body>
</html>