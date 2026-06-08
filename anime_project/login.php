<?php
// Start the session so we can use $_SESSION
session_start();

// If already logged in, no need to be here, go to anime.php
if (isset($_SESSION["user"])) {
    header("Location: anime.php");
    exit();
}

// If the login form is submitted
if (isset($_POST["email"])) {
    // Open the connection to the database
    $db = new PDO("mysql:host=localhost;dbname=anime_db", "root", "");

    // Grab the email and password from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Search for the user in the database using their email
    $query = "SELECT * FROM users WHERE email=:email";
    $stmt = $db->prepare($query);
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetchAll();

    // If no user found with that email, send them back to login
    if (empty($user)) {
        header("Location: login.php");
        exit();
    }

    // Check if the submitted password matches the hashed password in the database
    $is_password_match = password_verify($password, $user[0]["password"]);

    // If password is correct, let them in
    if ($is_password_match) {
        $_SESSION["user"] = $user[0];
        header("Location: anime.php");
        exit();
    } else {
        // If password is wrong, send them back to login
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Anime Review</title>
    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!-- bg-light gives a light grey background using Bootstrap -->
<body class="bg-light">
    <!-- Container centers the card on the screen, my-5 adds top and bottom margin -->
    <div class="container my-5 mx-auto" style="max-width: 500px;">
        <h1 class="h1 mb-4 text-center">Login</h1>
        <!-- Card gives a white box -->
        <div class="card p-4">
            <!-- method POST hides the data from the URL -->
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <!-- name="email" is how PHP reads this input via $_POST['email'] -->
                    <input type="email" class="form-control" name="email" placeholder="Email"/>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <!-- name="password" is how PHP reads this input via $_POST['password'] -->
                    <input type="password" class="form-control" name="password" placeholder="Password"/>
                </div>

                <!-- d-grid makes the button full width -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
            </form>
        </div>
        <!-- Link to register page for new users -->
        <div class="text-center mt-3">
            <a href="register.php">Don't have an account? Sign up here</a>
        </div>
    </div>
</body>
</html>