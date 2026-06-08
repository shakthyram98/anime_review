<?php
// Start the session so we can access $_SESSION
session_start();

// Remove the user's wristband (log them out)
// session_destroy() = destroys the ENTIRE session, like burning down the whole restaurant.
// unset($_SESSION["user"]) only removes ONE item from the session, like just taking back the wristband.
unset($_SESSION["user"]);

// Send them back to the login page
header("Location: login.php");
exit();
?>
