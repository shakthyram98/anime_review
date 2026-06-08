<?php
// Start the session so we can use $_SESSION
session_start();

// If already logged in, no need to be here, go to anime.php
if (isset($_SESSION["user"])) {
    header("Location: anime.php");
    exit();
}

// If the register form is submitted
if (isset($_POST["name"])) {
    // Open the connection to the database
    $db = new PDO("mysql:host=localhost;dbname=anime_db", "root", "");

    // Grab all the form inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password == $confirm_password) {
        // Lock the password before saving
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user into database
        $query =
            "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $hashed_password,
        ]);

        // Send to login page after registration
        header("Location: login.php");
        exit();
    } else {
        // Passwords don't match, go back to register
        header("Location: register.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | Anime Review</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!-- bg-light gives a light grey background using Bootstrap -->
<body class="bg-light">
    
<!-- Container centers the card on the screen -->
    <div class="container my-5 mx-auto" style="max-width: 500px;">
        <h1 class="h1 mb-4 text-center">Register</h1>
        <!-- Card gives a white box -->
        <div class="card p-4">
            <!-- method POST hides the data from the URL -->
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <!-- name="name" is how PHP reads this input via $_POST['name'] -->
                    <input type="text" class="form-control" name="name" placeholder="Name"/>
                </div>

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

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <!-- name="confirm_password" is how PHP reads this via $_POST['confirm_password'] -->
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password"/>
                </div>
                <!-- d-grid makes the button full width -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
                
            </form>
        </div>
        <!-- Link to login page for existing users -->
        <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>