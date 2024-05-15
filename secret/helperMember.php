<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "assignment");

/*
 
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
*/

session_start(); // Start the session to access session variables
// Get the logged-in user's username from the session
$username = $_SESSION['username'];

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

function getGender() {
    return array(
        'M' => '👨 Male',
        'F' => '👩 Female'
    );
}

function checkEmpty($name) {
    if (empty($name)) {
        return "cannot be empty";
    }
    return "";
}

function mbFullName() {
    $fullName = trim($_POST["mbFullName"]);
    $error = checkEmpty($fullName);
    if (!empty($error)) {
        return "<b>Full Name </b>" . $error;
    } else if (!preg_match("/^[A-Za-z \.]+$/", $fullName)) {
        return "Invalid <b>name</b>.";
    }
    return "";
}

function mbGender() {
    if (!isset($_POST["mbGender"])) {
        return "Please select your <b>Gender</b>.";
    }
    return "";
}

function mbUserName() {
    $userName = trim($_POST["mbUserName"]);
    $error = checkEmpty($userName);
    if (!empty($error)) {
        return "<b>User Name </b>" . $error;
    } else if (!preg_match("/^[A-Za-z0-9_]+$/", $userName)) {
        return "Invalid <b>User Name</b>.";
    }
    return "";
}

function mbBirthdate(){
    $birthdate = trim($_POST["mbBirthdate"]);
    $error = checkEmpty($birthdate);
    if (!empty($error)) {
        return "Please select your <b>Birthdate</b>!";
    }
    return "";
}

function mbPhoneNumber() {
    $phoneNum = trim($_POST["mbPhoneNum"]);
    $error = checkEmpty($phoneNum);
    if (!empty($error)) {
        return "Please enter your <b>PHONE</b>!";
    } elseif (!preg_match("/^01[0-9]-\d{7,8}$/", $phoneNum)) {
        return "Invalid <b>PHONE</b>!";
    }
    return "";
}

function mbEmail() {
    $email = trim($_POST["mbEmail"]);
    $error = checkEmpty($email);
    $emailPattern = '/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,6}$/';
    if (!empty($error)) {
        return "<b>Email </b>" . $error;
    } else if (!preg_match($emailPattern, $email)) {
        return "Please enter the correct <b>email</b>";
    }
    return "";
}

function checkFullName($name){
    if($name == null){
        return "Please enter your <b>name</b>.";

    }else if(strlen($name) >30){
        return "Your <b>name</b> is too long.";

    }else if(!preg_match('/^[A-Za-z /]+$/', $name)){
        return 'Invalid <b>name</b>.';
    }

}

function checkFeedback() {
    $pattern = '/[#$^*]/';
    $clnFeedback = trim($_POST["feedbackDesc"]);
    if (preg_match($pattern, $clnFeedback)) {
        return "<p>The feedback should not contain special characters.</p>";
    }
    return "";
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to fetch user data

if (isset($_COOKIE["login-user"])) {
    $username = $_COOKIE["login-user"];

    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($con -> connect_error){
        die("Connection failed: ". $con->connect_error);
    }

    $sql = "SELECT * FROM USER WHERE USERNAME = '$username'";

    //pass sql into connection to execute
    $result = $con->query($sql);
    if($row = $result->fetch_object()){
    $fullname = $row->fullname ;
    $gender = $row->gender;
    $birthDate = $row->birthdate;
    $phone = $row->phone_number;
    $email = $row->email_address; 
    $password = $row->password;
    }else{
        echo "Unable to process.[<a href='login.php'>Try again</a>]";
    }
    $result->free();
    $con->close();
}