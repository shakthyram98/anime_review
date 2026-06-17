<?php
require "header.php";

// Ask the waiter for all anime (JSON)
$json = file_get_contents(
    "http://localhost/anime_review/anime_project/backend/index.php?action=getAllAnime",
);

// Unpack the JSON box into a PHP array
$anime_list = json_decode($json, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anime List</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Anime List</h1>
            <div>
                <!-- only admin sees the Manage buttons -->
                <?php if ($_SESSION["user"]["role"] == "admin"): ?>
                <a href="anime-manage.php" class="btn btn-primary btn-sm me-2">Manage Anime</a>
                <!-- new manage genres button for admin -->
                <a href="genre-manage.php" class="btn btn-secondary btn-sm me-2">Manage Genres</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
        <div class="row">
            <!-- Loop through each anime and display as a card -->
            <?php foreach ($anime_list as $anime): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">

                    <!-- Show image if it exists, show placeholder if it doesn't -->
                    <?php if ($anime["image_url"]): ?>
                        <img src="<?= $anime["image_url"] ?>" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>

                        <!-- Grey placeholder when no image -->
                        <div class="bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <span class="text-white">No Image</span>
                        </div>
                        
                    <?php endif; ?>
                    <div class="card-body">
                        <!-- Anime title -->
                        <h5 class="card-title"><?= $anime["title"] ?></h5>

                        <!-- Genre badge -->
                        <span class="badge bg-primary mb-2"><?= $anime[
                            "genre_name"
                        ] ?></span>

                        <!-- Description -->
                        <p class="card-text"><?= $anime["description"] ?></p>

                        <!-- Link to reviews for this anime -->
                        <a href="review.php?anime_id=<?= $anime[
                            "id"
                        ] ?>" class="btn btn-primary btn-sm">See Reviews</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>