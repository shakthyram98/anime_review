<?php
// Connect to the database
$db = new PDO("mysql:host=localhost;dbname=anime_db", "root", "");

// Read the action from the URL e.g. ?action=getAllAnime
$action = isset($_GET["action"]) ? $_GET["action"] : null;

// Decide what to do based on the action
switch ($action) {
    // Get all anime with their genre name
    case "getAllAnime":
        $query = "SELECT anime.*, genres.name as genre_name 
                  FROM anime 
                  JOIN genres ON anime.genre_id = genres.id";
        $stmt = $db->prepare($query);
        $stmt->execute([]);
        $anime = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($anime);
        break;

    // Get all genres
    case "getAllGenres":
        $query = "SELECT * FROM genres";
        $stmt = $db->prepare($query);
        $stmt->execute([]);
        $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($genres);
        break;

    // Get all reviews for a specific anime
    case "getReviewsByAnime":
        $anime_id = $_GET["anime_id"];
        $query = "SELECT reviews.*, users.name as user_name 
                  FROM reviews 
                  JOIN users ON reviews.user_id = users.id 
                  WHERE reviews.anime_id = :anime_id";
        $stmt = $db->prepare($query);
        $stmt->execute([":anime_id" => $anime_id]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($reviews);
        break;

    // Get one specific anime by id
    case "getOneAnime":
        $id = $_GET["id"];
        $query = "SELECT anime.*, genres.name as genre_name 
                  FROM anime 
                  JOIN genres ON anime.genre_id = genres.id 
                  WHERE anime.id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id]);
        $anime = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($anime);
        break;

    // Delete an anime by id
    case "deleteAnime":
        $id = $_POST["id"];
        $query = "DELETE FROM anime WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id]);
        // Return JSON response to tell JavaScript if it worked
        echo json_encode([
            "success" => true,
            "message" => "Anime deleted successfully",
        ]);
        break;

    // Delete a review by id
    case "deleteReview":
        $id = $_POST["id"];
        $query = "DELETE FROM reviews WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id]);
        // Return JSON response to tell JavaScript if it worked
        echo json_encode([
            "success" => true,
            "message" => "Review deleted successfully",
        ]);
        break;

    // Delete a genre by id
    case "deleteGenre":
        $id = $_POST["id"];
        $query = "DELETE FROM genres WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id]);
        // Return JSON response to tell JavaScript if it worked
        echo json_encode([
            "success" => true,
            "message" => "Genre deleted successfully",
        ]);
        break;
}
?>
