<?php
require "header.php";

// Get anime_id and review id from URL
$anime_id = isset($_GET["anime_id"]) ? $_GET["anime_id"] : null;
$review_id = isset($_GET["id"]) ? $_GET["id"] : null;

// If no anime_id, send back to anime list
if ($anime_id == null) {
    header("Location: anime.php");
    exit();
}

// Security check — only admin or the review owner can delete
if ($review_id != null) {
    $query = "SELECT * FROM reviews WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $review_id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    // If not admin AND nto the owner, kick out
    if (
        $_SESSION["user"]["role"] != "admin" &&
        $review["user_id"] != $_SESSION["user"]["id"]
    ) {
        header("Location: anime.php");
        exit();
    }

    // Delete the review
    $query = "DELETE FROM reviews WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $review_id]);
}

// Send back to review page
header("Location: review.php?anime_id=$anime_id");
exit();
?>
