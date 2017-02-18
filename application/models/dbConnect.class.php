<?php
// application/models/dbConnect.class.php

//this is the ClearDB database url that is connected to our Heroku app
$CLEARDB_DATABASE_URL = "mysql://b246c25ab72aba:3c0dafda@us-cdbr-iron-east-04.cleardb.net/heroku_d0dc4a6713d6673?reconnect=true";
// parse the url for the various components and 
// save the components required for a connection
$url = parse_url($CLEARDB_DATABASE_URL);
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

// var_dump displays all the components of a variable
// Note: useful for testing; uncomment to see the variable components
// var_dump($url);

// our DB connection
$mysqli = new mysqli($server, $username, $password, $db);

// if our connection fails a connect_errno exists 
if ($mysqli->connect_errno) {
    
    echo "Sorry, this website is experiencing problems.";
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}

?>