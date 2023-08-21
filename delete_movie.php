<?php
require './config/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $movieId = $_GET["id"];

   // Get information about the movie before deleting
    $sqlSelect = "SELECT * FROM movie WHERE movieId = :movieId";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->bindParam(':movieId', $movieId);
    $stmtSelect->execute();
    $rowSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if ($rowSelect) {
        // Deleting the poster file
        if (!empty($rowSelect["image"])) {
            unlink($rowSelect["image"]);
        }

        // SQL query to delete the movie
        $sqlDelete = "DELETE FROM movie WHERE movieId = :movieId";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->bindParam(':movieId', $movieId);

        if ($stmtDelete->execute()) {
            echo "The movie has been successfully deleted.";
            header("Location: index.php");
        } else {
            echo "Error while deleting movie.";
        }
    } else {
        echo "Movie not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete movie</title>
    <link rel="stylesheet" href="./src/css/delete.css/">
</head>
<body>
<a href="./index.php">Go to main page</a>
</body>
</html>