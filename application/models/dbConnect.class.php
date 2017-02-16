<?php

// Connection to mySQL database

$user = 'root';
$pass = '';
$db = 'gTheatre';

$db = new mysqli('localhost', $user, $pass, $db) or die("Something went terribly wrong when you tried to connect to the Database");

echo "Hello World, from dbConnect.class.php";

?>