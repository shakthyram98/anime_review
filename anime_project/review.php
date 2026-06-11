<?php
require "header.php";

// Get the anime_id from the URL e.g. review.php?anime_id=1
$anime_id = isset($_GET["anime_id"]) ? $_GET["anime_id"] : null;

// If no anime_id in URL, send back to anime list
if ($anime_id == null) {
    header("Location: anime.php");
    exit();
}

// Ask the waiter for all reviews for this anime
$json = file_get_contents(
    "http://localhost/anime_review/anime_project/backend/index.php?action=getReviewsByAnime&anime_id=$anime_id",
);

// Unpack the JSON box into a PHP array
$reviews = json_decode($json, true);

// Get the anime details for the page title
$anime_json = file_get_contents(
    "http://localhost/anime_review/anime_project/backend/index.php?action=getOneAnime&id=$anime_id",
);
$anime = json_decode($anime_json, true);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $anime["title"] ?> Reviews</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery for AJAX requests -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 800px;">

        <!-- Top bar with back button and logout button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="anime.php" class="btn btn-link">← Back to Anime List</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>

        <!-- Anime title and genre -->
        <div class="mb-4">
            <h1><?= $anime["title"] ?></h1>
            <span class="badge bg-primary"><?= $anime["genre_name"] ?></span>
            <p class="mt-2"><?= $anime["description"] ?></p>
        </div>

        <!-- Add review button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Reviews</h4>
            <a href="review-form.php?anime_id=<?= $anime_id ?>" class="btn btn-primary btn-sm">Add Review</a>
        </div>

        <!-- Show reviews -->
        <?php if (empty($reviews)): ?>
            <div class="alert alert-info">No reviews yet. Be the first to review!</div>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Reviewer name -->
                    <h6 class="card-subtitle mb-2 text-muted"><?= $review[
                        "user_name"
                    ] ?></h6>

                    <!-- Review comment -->
                    <p class="card-text"><?= $review["comment"] ?></p>

                    <!-- Review date -->
                    <small class="text-muted"><?= $review[
                        "created_at"
                    ] ?></small>

                    <!-- Edit/Delete buttons — only show if admin OR own review -->
                    <?php if (
                        $_SESSION["user"]["role"] == "admin" ||
                        $_SESSION["user"]["id"] == $review["user_id"]
                    ): ?>
                    <div class="mt-2 d-flex gap-2">
                        <!-- Edit button -->
                        <a href="review-form.php?id=<?= $review[
                            "id"
                        ] ?>&anime_id=<?= $anime_id ?>" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Delete button — value stores the review id -->
                        <button class="btn btn-danger btn-sm delete-btn" value="<?= $review[
                            "id"
                        ] ?>">Delete</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
    <script>
    // When a delete button is clicked
    $('.delete-btn').click(function(event) {

        // Stop the button from doing its default action
        event.preventDefault();

        // Get the review id from the button's value
        var id = $(this).val();

        // Show a confirmation warning to the user
        var confirmed = confirm('Are you sure you want to delete this review?');

        // If user clicked OK
        if(confirmed) {

            // Send a POST request to backend using jQuery ajax
            $.ajax({
                // Put action in the URL as GET parameter
                url: "http://localhost/anime_review/anime_project/backend/index.php?action=deleteReview",
                // POST method to send data
                type: "POST",
                // Only send id as POST data
                data: { id: id },
                // If request was successful
                success: function(response) {
                    console.log(response);
                    alert("Review deleted successfully!");
                    // Reload the page to show updated list
                    location.reload();
                },
                // If something went wrong
                error: function(xhr, status, error) {
                    console.log("Error: ", error);
                    alert("Something went wrong. Please try again.");
                }
            });
        }
    });
    </script>
</body>
</html>