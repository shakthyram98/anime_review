<?php
// Start the session so we can access $_SESSION
session_start();

// Remove the user's record (log them out)
// unset($_SESSION["user"]) only removes ONE item from the session.
unset($_SESSION["user"]);

// Send them back to the login page
header("Location: login.php");
exit();
?>
