<?php
require "header.php";

// Admin only check — kick out if not admin
if ($_SESSION["user"]["role"] != "admin") {
    header("Location: anime.php");
    exit();
}

// Get all genres
$query = "SELECT * FROM genres";
$stmt = $db->prepare($query);
$stmt->execute([]);
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Genres</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery for AJAX requests -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Genres</h1>
            <!-- Button to add new genre -->
            <a href="genre-form.php" class="btn btn-primary btn-sm">Add New Genre</a>
        </div>
        <div class="card p-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genres as $genre): ?>
                    <tr>
                        <td><?= $genre["id"] ?></td>
                        <td><?= $genre["name"] ?></td>
                        <td class="text-end">
                            <!-- d-flex puts buttons side by side -->
                            <div class="d-flex justify-content-end gap-2">
                                <!-- Edit button -->
                                <a href="genre-form.php?id=<?= $genre[
                                    "id"
                                ] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <!-- Delete button -->
                               <button class="btn btn-danger btn-sm delete-btn" value="<?= $genre[
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
            event.preventDefault();
            var id = $(this).val();
            var confirmed = confirm('Are you sure you want to delete this genre?');
            if(confirmed) {
              $.ajax({
                url: "http://localhost/anime_review/anime_project/backend/index.php?action=deleteGenre",
                type: "POST",
                data: { id: id },
             success: function(response) {
                console.log(response);
                alert("Genre deleted successfully!");
                window.location.href = "http://localhost/anime_review/anime_project/genre-manage.php";
            },
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