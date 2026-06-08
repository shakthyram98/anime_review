<?php
// Require header — checks login and opens database
require "header.php";

// Admin only check, kick out if not admin
if ($_SESSION["user"]["role"] != "admin") {
    header("Location: anime.php");
    exit();
}

// Get all anime with their genre name
$query = "SELECT anime.*, genres.name as genre_name 
          FROM anime 
          JOIN genres ON anime.genre_id = genres.id";
$stmt = $db->prepare($query);
$stmt->execute([]);
$anime_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Anime</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery for AJAX requests -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Anime</h1>
            <!-- Button to add new anime -->
            <a href="anime-form.php" class="btn btn-primary btn-sm">Add New Anime</a>
        </div>
        
        <div class="card p-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anime_list as $anime): ?>
                    <tr>
                        <td><?= $anime["id"] ?></td>
                        <td><?= $anime["title"] ?></td>
                        <td><span class="badge bg-primary"><?= $anime[
                            "genre_name"
                        ] ?></span></td>
                        <td><?= $anime["description"] ?></td>
                        <td class="text-end">
                            <!-- d-flex puts buttons side by side, gap-2 adds space between them -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="anime-form.php?id=<?= $anime[
                                    "id"
                                ] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <!-- Delete button — value stores the anime id -->
                                <button class="btn btn-danger btn-sm delete-btn" value="<?= $anime[
                                    "id"
                                ] ?>">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Back button -->
        <div class="text-center mt-3">
            <a href="anime.php" class="btn btn-link">Back to Anime List</a>
        </div>
    </div>
    <script>
    // When a delete button is clicked
    $('.delete-btn').click(function(event) {

        // Stop the button from doing its default action
        event.preventDefault();

        // Get the anime id from the button's value
        var id = $(this).val();

        // Show a confirmation warning to the user
        var confirmed = confirm('Are you sure you want to delete this anime?');

        // If user clicked OK
        if(confirmed) {

            // Send a POST request to backend using jQuery ajax
            $.ajax({
                // Put action in the URL as GET parameter 
                url: "http://localhost/anime_review/anime_project/backend/index.php?action=deleteAnime",
                // POST method to send data
                type: "POST",
                // Data to send to the backend
                data: {
                    id: id
                },
                // If request was successful
                success: function(response) {
                    console.log(response);
                    alert("Anime deleted successfully!");
                    // Reload the page to show updated list
                    window.location.href = "http://localhost/anime_review/anime_project/anime-manage.php";
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