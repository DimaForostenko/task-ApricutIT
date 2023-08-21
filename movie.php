<?php
require './config/connect.php';

// Get the movie ID from the GET parameter
$movieId = $_GET['id'];

// SQL query to get information about the selected movie
$sql = "SELECT * FROM movie WHERE movieId = :movieId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':movieId', $movieId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Movie not found";
} else {
    echo " Name: " . $row["name"] . "<br>";
    echo "<img src='" . $row["image"] . "' alt='Постер'><br>";
    echo "Date of release: " . $row["releaseDate"] . "<br>";
    echo "Description: " . $row["description"] . "<br>";
}
?>
