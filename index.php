
<?php
    // Connect to database
    require './config/connect.php';
    
    // Get the total number of movies to paginate
    $sqlCount = "SELECT COUNT(*) as total FROM movie";
    $resultCount = $pdo->query($sqlCount);
    $rowCount = $resultCount->fetch(PDO::FETCH_ASSOC);
    $totalFilms = $rowCount['total'];

    // Calculate the total number of pages
    $filmsPerPage = 10;
    $totalPages = ceil($totalFilms / $filmsPerPage);

    // Current page (obtained, for example, from a GET parameter)
    $currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($currentpage - 1) * $filmsPerPage;

    // Check if there are any movies in the database
    if ($totalFilms > 0) {
        // Calculate index for LIMIT in SQL query
        $sql = "SELECT * FROM movie LIMIT $offset, $filmsPerPage";
        $result = $pdo->query($sql);
        $moviesData = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $movie = [
                "name" => $row["name"],
                "image" => $row["image"],
                "releaseDate" => $row["releaseDate"],
                "movieId" => $row["movieId"]
            ];
            $moviesData[] = $movie;
        }
      }

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie List</title>
  <!-- //<link rel="stylesheet" href="./src/css/reset.css"> -->
  <link rel="stylesheet" href="./src/css/index.css">
</head>
<body>

  <section class="cards-container">
    <h1 class="cards-title">Movie List</h1>
    <a href="./add_movie.php" class="button-add-movie">Add Movie</a>
    <?php if (empty($moviesData)) : ?>
      <p>No movies found. <a href="add_movie.php">Add a movie</a>.</p>
    <?php else : ?>
    <ul class="cards-list">
    <?php foreach ($moviesData as $movie) : ?>
        <li class="cards-wrapper">
          <article class="cards-container">
            <h2 class="card-name"><?php echo $movie["name"]; ?></h2>
            <img src="<?php echo $movie["image"]; ?>" alt="Постер" class="card-photo">
            <p class="card-info">Date of release: <?php echo $movie["releaseDate"]; ?></p>
            <ul class="card-buttons">
              <li><a class="card-button" href="movie.php?id=<?php echo $movie["movieId"]; ?>">Viewing</a></li>
              <li><a class="card-button" href="edit_movie.php?id=<?php echo $movie["movieId"]; ?>">Edit</a></li>
              <li><a class="card-button" href="delete_movie.php?id=<?php echo $movie["movieId"]; ?>">Delete</a></li>
            </ul>
          </article>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="pagination">
      <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
        <a href='index.php?page=<?php echo $page; ?>' class="button-pagination"><?php echo $page; ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </section>
  
</body>
</html>