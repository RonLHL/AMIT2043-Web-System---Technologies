<?php

//function check event status
function checkEventStatus($eventStatus){
    if($eventStatus == null){
        return "Please select your event <b>Status</b>.";
        
    }else if(!array_key_exists($eventStatus, getEventStatus())){
        //whatever user select is NOT in our list
        return "Hello, wrong <b>Status</b>!";
    }
}

function checkEventDesc($enventDesc){
    if($enventDesc == null){
        return "Please enter your <b>EVENT Desciption</b>.";
    }
}

function checkEventVenue($enventVenue){
    if($enventVenue == null){
        return "Please enter your <b>EVENT Venue</b>.";
    }
}

function checkEventDate($enventDate){
    if($enventDate == null){
        return "Please select your <b>EVENT Date</b>.";
    }
}

function checkEventTime($enventTime){
    if($enventTime == null){
        return "Please select your <b>EVENT Time</b>.";
    }
}

function checkEventPrice($enventPrice){
    if($enventPrice == null){
        return "Please enter your <b>EVENT Price</b>.";
    }else if(substr(strval($enventPrice), 0, 1) == "-"){
        return "<b>EVENT Price</b> can not be negative number!";
    }
}

function checkSameEventID($enventID){
    $found = false;
    
    //Step 1: create connection
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    //Step 1.1 clean $id, remove special character, prevent
    //sql error when exexuting sql code
    $enventID = $con->real_escape_string($enventID);
    
    //Step 2: sql statement
     $sql ="SELECT * FROM EVENT WHERE ENVENT_ID = '$enventID'";
     
     //Step 3.process sql
     $result = $con->query($sql);
     
     if($result->num_rows >0){
         //result found -> SAME Envent ID DETECTED
         $found = true;
     }else{
         //no result found -> NO PROBLEM
     }
     $result->free(); //release memory usage
     $con->close();
     
     return $found;
     
}

function checkEventID($enventID){
    if($enventID == null){
        return "Please enter your <b>EVENT ID</b>.";
        
    }else if(strlen($enventID) > 10){
        return "Your <b>name</b> is too long.";
        
    }else if(!preg_match("/^E\d{1,9}$/", $enventID)){
        return 'Invalid <b>EVENT ID</b>. Please follow by E(0001)';
    }
    
}

function checkEventName($enventName){
    if($enventName == null){
        return "Please enter your <b>EVENT Name</b>.";
        
    }else if(strlen($enventName) > 50){
        return "Your <b>name</b> is too long.";
        
    }else if(!preg_match("/^[a-zA-Z0-9 \@\.\&\(\)\!\?\'\"\-]+$/", $enventName)){
        return 'Invalid <b>EVENT Name</b>.';
    }
    
}

//create function to return all types of event status
function getEventStatus(){
    return array(
        'A' => 'Available',
        'U' => 'Unavailable'
    );
}


define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "assignment");
