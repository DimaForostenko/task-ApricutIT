<?php
require './config/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $releaseDate = $_POST["releaseDate"];

   // Load poster file
    $targetDir = './src/files_img/';
    $posterName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $posterName;
    $uploadOk = 1;
    $imageFileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Checking if the file exists and its type
    if (!empty($_FILES["image"]["tmp_name"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Файл не является изображением.";
            $uploadOk = 0;
        }
    }

    /// Checking for the presence and validation of data
    if (empty($name) || empty($releaseDate) || $uploadOk == 0) {
        echo "Заполните все обязательные поля и загрузите постер.";
    } else {
        /// SQL query to insert movie data
        $sql = "INSERT INTO movie (name, description, releaseDate, image) 
                VALUES (:name, :description, :releaseDate, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':releaseDate', $releaseDate);
        $stmt->bindParam(':image', $targetFilePath);

        if ($stmt->execute()) {
            // Load poster file
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
            echo "Movie added successfully.";
        } else {
            echo "Error adding movie.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <link rel="stylesheet" href="./src/css/add_movie.css">
</head>
<body>
    <h2>Adding  movie</h2>
    <form action="add_movie.php" method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name" required><br>
    Description: <textarea name="description"></textarea><br>
    Date of release: <input type="date" name="releaseDate" required><br>
    Poster: <input type="file" name="image" accept="image/*" required><br>
        <input type="submit" value="Add">
    </form>
    <a href="./index.php">Go to main page</a>
</body>
</html>
