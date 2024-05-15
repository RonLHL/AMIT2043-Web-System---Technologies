<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "assignment");

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//function check gender
function checkGender($gender){
    if($gender == null){
        return "Please select your <b>Gender</b>.";
        
    }else if(!array_key_exists($gender, getGender())){
        //whatever user select is NOT in our list
        return "Hello, wrong <b>gender</b>!";
    }
}

function checkPaymentMethod($paymentMethod){
    if($paymentMethod == ""){
        return "Payment Method Cannot Be Null";
    }
}

function checkTotalAmount($amount){
    $totalAmount = trim($amount);
    
    if (!is_numeric($totalAmount) || $totalAmount <= 0) {
        return "Invalid total amount. Please enter a positive number.";
    }

    return "";
}

function checkCVV($cvv){
    if($cvv > 3){
        return "CCV cannot more than 3 digits";
    }
    return "";
}

function checkExpiryDate($expDate) {
    // Define the expected format (MM/YY)
    $pattern = '/^(0[1-9]|1[0-2])\/(\d{2})$/';

    // Check if the date matches the pattern
    if (!preg_match($pattern, $expDate, $matches)) {
        return "Invalid expiry date format. Use MM/YY.";
    }

    // Extract month and year from the matched pattern
    $month = intval($matches[1]);
    $year = intval('20' . $matches[2]); // Convert YY to YYYY

    // Get the current month and year
    $currentMonth = intval(date('m'));
    $currentYear = intval(date('Y'));

    // Check if the expiry date is in the future
    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
        return "Card expiry date must be in the future.";
    }

    return ""; // No error, return empty string
}

function checkCardNumber($cardNumber){
    $value = preg_replace('/\D/', '', $cardNumber);

    // Check if the card number is all digits and has a valid length
    if (!ctype_digit($value) || strlen($value) < 13 || strlen($value) > 19) {
        return "Invalid card number length.";
    }
}
//create function to check full name 
function checkFullName($name){
    if($name == null){
        return "Please enter your <b>name</b>.";
        
    }else if(strlen($name) >30){
        return "Your <b>name</b> is too long.";
        
    }else if(!preg_match('/^[A-Za-z \/]+$/', $name)){
        return 'Invalid <b>name</b>.';
    }
    
}

function checkBirthdate($birth){
    if($birth == null){
        return "Please selete your <b>BIRTH DATE</b>!";
        
    }else if(validateDate($birth)){
        return "Invalid <b>BIRTH DATE</b>!";
    }
}

function checkPhone($phone){
    if($phone == null){
        return "Please enter your <b>PHONE NUMBER</b>!";
        
    }else if(strlen($phone) >13){
        return "Your <b>PHONE NUMBER</b> is too long, maximum 12.";
        
    }else if(!preg_match('/^01[0-9]-\d{7,8}$/', $phone)){
        return "Invalid <b>Phone</b>! Format(01X-XXXXXXXX)";
    }
    
}

function checkEmail($email){
    if($email == null){
        return "Please enter your <b>EMAIL ADDRESS</b>!";
    }
}

function checkPassword($password){
    if($password == null){
        return "Please enter your <b>PASSWORD</b>!";
    }else if(strlen($password) < 8){
        return "Your <b>PASSWORD</b> must have atleast 8 letters!";
    }
}

function checkCFPassword($password ,$cfpassword){
    if($cfpassword == null){
        return "Please enter your <b>PASSWORD</b>!";
    }else if(strcmp($cfpassword ,$password) != 0){
        return "Your <b>CONFIRM PASSWORD</b> do not match!";
    }
}

//create function to check duplicated(SAME) username
function checkSameUserName($username){
    $found = false;
    
    //Step 1: create connection
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    //Step 1.1 clean $id, remove special character, prevent
    //sql error when exexuting sql code
    $username = $con->real_escape_string($username);
    
    //Step 2: sql statement
     $sql ="SELECT * FROM User WHERE Username = '$username'";
     
     //Step 3.process sql
     $result = $con->query($sql);
     
     if($result->num_rows >0){
         //result found -> SAME STUDENT ID DETECTED
         $found = true;
     }else{
         //no result found -> NO PROBLEM
     }
     $result->free(); //release memory usage
     $con->close();
     
     return $found;
     
}

function checkUserName($username){
    if($username == null){
        return "Please enter your <b>User Name</b>.";
        
    }else if(strlen($username) >30){
        return "Your <b>name</b> is too long.";
        
    }else if(!preg_match("/^[A-Za-z \.]+$/", $username)){
        return 'Invalid <b>name</b>.';
    }
    
}

//function to return all types of gender
function getGender(){
    return array(
        'M' => 'Male',
        'F' => 'Female'
    );
}

function validateDate($date, $format = 'Y/m/d') { 
    $d = DateTime::createFromFormat($format, $date); 
    return $d && $d->format($format) === $date; 
} 


