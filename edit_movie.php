<?php
require './config/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movieId = $_POST["movieId"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $releaseDate = $_POST["releaseDate"];

    // SQL query to get information about the movie
    $sqlSelect = "SELECT * FROM movie WHERE movieId = :movieId";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->bindParam(':movieId', $movieId);
    $stmtSelect->execute();
    $rowSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);

   // Load new poster file if selected
    if (!empty($_FILES["image"]["tmp_name"])) {
        // Удаление старого файла постера
        if (!empty($rowSelect["image"])) {
            unlink($rowSelect["image"]);
        }

        // Load new file
        $targetDir = "./src/files_img/";
        $posterName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $posterName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

       // Update the path to the poster in the database
        $sqlUpdate = "UPDATE movie SET name = :name, description = :description, releaseDate = :releaseDate, image = :image WHERE movieId = :movieId";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':name', $name);
        $stmtUpdate->bindParam(':description', $description);
        $stmtUpdate->bindParam(':releaseDate', $releaseDate);
        $stmtUpdate->bindParam(':image', $targetFilePath);
        $stmtUpdate->bindParam(':movieId', $movieId);

        if ($stmtUpdate->execute()) {
            echo "The movie has been successfully updated.";
        } else {
            echo "Movie update error.";
        }
    } else {
        // Update movie information without changing the poster
        $sqlUpdate = "UPDATE movie SET name = :name, description = :description, releaseDate = :releaseDate WHERE movieId = :movieId";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':name', $name);
        $stmtUpdate->bindParam(':description', $description);
        $stmtUpdate->bindParam(':releaseDate', $releaseDate);
        $stmtUpdate->bindParam(':movieId', $movieId);

        if ($stmtUpdate->execute()) {
            echo "The movie has been successfully updated.";
        } else {
            echo "Movie update error.";
        }
    }
}

// Get movie id from GET parameter
$movieId = $_GET['id'];

// SQL query to get information about the movie
$sql = "SELECT * FROM movie WHERE movieId = :movieId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':movieId', $movieId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film editing</title>
    <link rel="stylesheet" href="./src/css/edit_movie.css/";>
</head>
<body>
    <h2>Film editing</h2>
    <form action="edit_movie.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="movieId" value="<?php echo $row['movieId']; ?>">
        Name: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
        Description:<textarea name="description"><?php echo $row['description']; ?></textarea><br>
        Date of release: <input type="date" name="releaseDate" value="<?php echo $row['releaseDate']; ?>" required><br>
        Poster: <input type="file" name="image" accept="image/*"><br>
        <input type="submit" value="Save">
    </form>
    <a href="./index.php">Go to main page</a>
</body>
</html>
