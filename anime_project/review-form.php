<?php
// Checks login and opens database
require "header.php";

// This will get the anime_id from URL
$anime_id = isset($_GET["anime_id"]) ? $_GET["anime_id"] : null;

// No anime_id, it would bring back to anime list
if ($anime_id == null) {
    header("Location: anime.php");
    exit();
}

// This to check if we're editing the review form
$review = null;
$review_id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($review_id != null) {
    $query = "SELECT * FROM reviews WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $review_id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    // This is a security check, where users can only edit their OWN reviews
    if (
        $_SESSION["user"]["role"] !== "admin" &&
        $review["user_id"] != $_SESSION["user"]["id"]
    ) {
        header("Location: anime.php");
        exit();
    }
}

// This checks if the form is submitted or not
if (isset($_POST["comment"])) {
    $comment = $_POST["comment"];
    $user_id = $_SESSION["user"]["id"];

    if ($review_id != null) {
        // Update existing review
        $query = "UPDATE reviews SET comment=:comment WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":comment" => $comment,
            ":id" => $review_id,
        ]);
    } else {
        // Insert new review
        $query =
            "INSERT INTO reviews (user_id, anime_id, comment) VALUES (:user_id, :anime_id, :comment)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":user_id" => $user_id,
            ":anime_id" => $anime_id,
            ":comment" => $comment,
        ]);
    }

    // Send back to review page
    header("Location: review.php?anime_id=$anime_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $review_id ? "Edit Review" : "Add Review" ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <h1><?= $review_id ? "Edit Review" : "Add Review" ?></h1>
        <div class="card p-4">
            <!-- method POST hides data from URL -->
            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">Comment</label>
                    <!-- If editing, pre-fill with existing comment -->
                    <textarea class="form-control" name="comment" rows="5"><?= $review
                        ? $review["comment"]
                        : "" ?></textarea>
                </div>
                <!-- d-grid makes button full width -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><?= $review_id
                        ? "Update Review"
                        : "Add Review" ?></button>
                </div>
            </form>
        </div>
        <!-- Back button -->
        <div class="text-center mt-3">
            <a href="review.php?anime_id=<?= $anime_id ?>" class="btn btn-link">Back to Reviews</a>
        </div>
    </div>
</body>
</html>