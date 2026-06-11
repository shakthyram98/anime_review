<?php
require "header.php";

// Admin only check — kick out if not admin
if ($_SESSION["user"]["role"] != "admin") {
    header("Location: anime.php");
    exit();
}

// Check if we're editing an existing anime
$anime = null;
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($id != null) {
    // Get the existing anime data to pre-fill the form
    $query = "SELECT * FROM anime WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $id]);
    $anime = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all genres for the dropdown
$genre_query = "SELECT * FROM genres";
$genre_stmt = $db->prepare($genre_query);
$genre_stmt->execute([]);
$genres = $genre_stmt->fetchAll(PDO::FETCH_ASSOC);

// If form is submitted
if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $genre_id = $_POST["genre_id"];
    // Get image URL from form — use empty string if not provided
    $image_url = isset($_POST["image_url"]) ? $_POST["image_url"] : "";

    if ($id != null) {
        // Update existing anime, now includes image_url
        $query =
            "UPDATE anime SET title=:title, description=:description, genre_id=:genre_id, image_url=:image_url WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":title" => $title,
            ":description" => $description,
            ":genre_id" => $genre_id,
            ":image_url" => $image_url,
            ":id" => $id,
        ]);
    } else {
        // Insert new anime, now includes image_url
        $query =
            "INSERT INTO anime (title, description, genre_id, image_url) VALUES (:title, :description, :genre_id, :image_url)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":title" => $title,
            ":description" => $description,
            ":genre_id" => $genre_id,
            ":image_url" => $image_url,
        ]);
    }

    // Send back to anime manage page
    header("Location: anime-manage.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? "Edit Anime" : "Add Anime" ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <h1><?= $id ? "Edit Anime" : "Add Anime" ?></h1>
        <div class="card p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <!-- If editing, pre-fill with existing title -->
                    <input type="text" class="form-control" name="title" value="<?= $anime
                        ? $anime["title"]
                        : "" ?>"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <!-- If editing, pre-fill with existing description -->
                    <textarea class="form-control" name="description" rows="4"><?= $anime
                        ? $anime["description"]
                        : "" ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Genre</label>
                    <!-- Dropdown showing all genres -->
                    <select class="form-control" name="genre_id">
                        <option value="">Select a genre</option>
                        <?php foreach ($genres as $genre): ?>
                        <!-- If editing, pre-select the current genre -->
                        <option value="<?= $genre["id"] ?>" <?= $anime &&
                        $anime["genre_id"] == $genre["id"]
                            ? "selected"
                            : "" ?>>
                            <?= $genre["name"] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image URL (optional)</label>
                    <!-- If editing, pre-fill with existing image URL -->
                    <input type="text" class="form-control" name="image_url" value="<?= $anime
                        ? $anime["image_url"]
                        : "" ?>" placeholder="https://example.com/image.jpg"/>
                </div>
                
                <!-- d-grid makes button full width -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><?= $id
                        ? "Update Anime"
                        : "Add Anime" ?></button>
                </div>
            </form>
        </div>
        <!-- Back button -->
        <div class="text-center mt-3">
            <a href="anime-manage.php" class="btn btn-link">Back to Manage Anime</a>
        </div>
    </div>
</body>
</html>