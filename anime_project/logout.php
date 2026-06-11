<?php
// Start the session so we can access $_SESSION
session_start();

// Remove the user's wristband (log them out)
// unset($_SESSION["user"]) only removes ONE item from the session, like just taking back the wristband.
unset($_SESSION["user"]);

// Send them back to the login page
header("Location: login.php");
exit();
?>
