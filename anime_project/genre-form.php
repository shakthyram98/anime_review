<?php
// Require header — checks login and opens database
require "header.php";

// Admin only check — kick out if not admin
if ($_SESSION["user"]["role"] != "admin") {
    header("Location: anime.php");
    exit();
}

// Check if we're editing an existing genre
$genre = null;
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($id != null) {
    // Get the existing genre data to pre-fill the form
    $query = "SELECT * FROM genres WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $id]);
    $genre = $stmt->fetch(PDO::FETCH_ASSOC);
}

// If form is submitted
if (isset($_POST["name"])) {
    $name = $_POST["name"];

    if ($id != null) {
        // Update existing genre
        $query = "UPDATE genres SET name=:name WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":name" => $name,
            ":id" => $id,
        ]);
    } else {
        // Insert new genre
        $query = "INSERT INTO genres (name) VALUES (:name)";
        $stmt = $db->prepare($query);
        $stmt->execute([":name" => $name]);
    }

    // Send back to genre manage page
    header("Location: genre-manage.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? "Edit Genre" : "Add Genre" ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 500px;">
        <h1><?= $id ? "Edit Genre" : "Add Genre" ?></h1>
        <div class="card p-4">
            <!-- method POST hides data from URL -->
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Genre Name</label>
                    <!-- If editing, pre-fill with existing name -->
                    <input type="text" class="form-control" name="name" value="<?= $genre
                        ? $genre["name"]
                        : "" ?>"/>
                </div>
                <!-- d-grid makes button full width -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><?= $id
                        ? "Update Genre"
                        : "Add Genre" ?></button>
                </div>
            </form>
        </div>
        <!-- Back button -->
        <div class="text-center mt-3">
            <a href="genre-manage.php" class="btn btn-link">Back to Manage Genres</a>
        </div>
    </div>
</body>
</html>