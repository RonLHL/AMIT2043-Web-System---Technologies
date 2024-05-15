<?php

session_start();

require_once './secret/helperCart.php';

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

if(isset($_COOKIE['login-user'])) {
    //retrieve the value from the cookie
    $username = $_COOKIE['login-user'];
} else {
    echo "Cookie 'login-user' is not set.";
}


if(isset($_POST["btnAdd"])){
    if(isset($_SESSION["cart"])){
        $eventArrayID = array_column($_SESSION["cart"],"eventID");
        if(!in_array($_GET["event_id"],$eventArrayID)){
            $count = count($_SESSION["cart"]);
            $eventArray = array(
                'eventID' => $_GET["event_id"],
                'eventName' => $_POST["hdName"],
                'price' => $_POST["hdPrice"],
                'quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][$count] = $eventArray;
            echo '<script>window.location="cart.php"</script>';
        }else{
            echo '<script>alert("Event has been added to cart.")</script>';
            echo '<script>window.location="cart.php"</script>';
        }
    }else{
        $eventArray = array(
            'eventID' => $_GET["event_id"],
            'eventName' => $_POST["hdName"],
            'price' => $_POST["hdPrice"],
            'quantity' => $_POST["quantity"],
        );
        $_SESSION["cart"][0] = $eventArray;
    }
}

if(isset($_GET["action"])){
    if($_GET["action"] == "delete"){
        foreach($_SESSION["cart"] as $keys => $value){
            if($value["eventID"] == $_GET["event_id"]){
                unset($_SESSION["cart"][$keys]);
                echo '<script>alert("Event has been removed from cart.")</script>';
                echo '<script>window.location="cart.php"</script>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="css/css.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include ('memberHeader.php'); require_once './secret/helperCart.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Events</h1>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php
                    $sql = "SELECT * FROM EVENT ORDER BY EVENT_ID ASC";

                    $result = mysqli_query($con,$sql);

                    if(mysqli_num_rows($result)>0){

                        while($row = mysqli_fetch_array($result)){
                ?>
                <div class="col">
                    <div class="card h-100">
                        <form action="cart.php?action=add&event_id=<?php echo $row["event_id"];?>" method="POST">
                            <?php
                                $imagePath = "";
                                foreach (glob("uploads/{$row['event_img']}.{jpg,jpeg,png,gif}", GLOB_BRACE) as $file) {
                                    $imagePath = $file;
                                }
                            ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="Event Image" >
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["event_name"];?></h5>
                                <h6 class="card-text">RM <?php echo $row["price"];?></h6>
                                <input type="text" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="hdName" value="<?php echo $row["event_name"];?>">
                                <input type="hidden" name="hdPrice" value="<?php echo $row["price"];?>">
                                <input type="submit" name="btnAdd" class="btn btn-success" style="margin-top: 10px;" value="Add to cart">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>  
        <br>
        <br>
        <h1 class="text-center mb-4">Cart</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Remove Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!empty($_SESSION["cart"])){
                        
                        $eventIDs = array();
                        $total = 0;
                        if(isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
                            //loop through each item in the cart
                            foreach($_SESSION["cart"] as $item) {
                                //check if the "eventID" key exists in the item
                                if(isset($item["eventID"])) {
                                    //add the event ID to the array
                                    $eventIDs[] = $item["eventID"];
                                }
                            }
                        }
                        $jsonEventIDs = json_encode($eventIDs);
                            foreach($_SESSION["cart"] as $key => $value){
                    ?>
                        <tr>
                            <td><?php echo isset($value["eventName"]) ? $value["eventName"] : ""; ?></td>
                            <td><?php echo isset($value["quantity"]) ? $value["quantity"] : ""; ?></td>
                            <td><?php echo isset($value["price"]) ? $value["price"] : ""; ?></td>
                            <td><?php echo array_key_exists("price", $value) ? number_format($value["quantity"] * $value["price"], 2) : ""; ?></td>
                            <td><a href="cart.php?action=delete&event_id=<?php echo isset ($value["eventID"]) ? $value["eventID"] : ""; ?>" class="btn btn-danger">Remove Item</a></td>
                        </tr>
                    <?php
                        $total = $total + (array_key_exists("price", $value) ? ($value["quantity"] * $value["price"]) : 0);
                            }
                    ?>
                    <tr>
                        <td colspan="3" align="right">Total (RM):</td>
                        <td align="right"><?php echo number_format($total,2);?></td>
                        <td></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <div class="text-end">
                <input class="btn btn-info btn-md" type="submit" value="Proceed to Checkout" name="btnSubmit" onclick="proceedToCheckout('<?php echo $username; ?>', ['E0007','E0008','E0009','E0010','E0005'], '<?php echo $total; ?>');" />
            </div>
        </div>
    </div>
    <?php include ('footer.php'); ?>
</body>
<script>
    function proceedToCheckout(username, eventIDs, total) {
    console.log("Username:", username);
    console.log("Event IDs:", eventIDs);
    console.log("Total:", total);

    var url = "payment.php?username=" + username + "&total=" + total;

    // If there are eventIDs, add them to the URL as query parameters
    if (eventIDs.length > 0) {
        url += "&eventIDs=" + eventIDs.join(",");
    }

    // Log the URL to the console
    console.log("URL:", url);

    // Redirect to payment.php
    window.location.href = url;
}

</script>
</html>
