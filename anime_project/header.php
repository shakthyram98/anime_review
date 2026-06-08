<?php
// Using "session_start" could use $_SESSION throughout the page
session_start();

// Putting like this will connect to my database
$db = new PDO("mysql:host=localhost;dbname=anime_db", "root", "");

// Checks if the user is NOT logged in
if (!isset($_SESSION["user"])) {
    // Kicks the user back to the login page
    header("Location: login.php");

    // Stops all code from running after the redirect
    exit();
}
?>
<!---Using this file, I don't need to put "$db = new PDO("mysql:host=localhost;dbname=anime_db", "root", "");" for every file. Just put "require('header.php')" at the top of every protected page.  --->