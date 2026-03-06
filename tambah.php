<?php
include 'config/database.php';

$database = new database();
$pdo      = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul   = $_POST['judul'];
    $type = $_POST['type'];
    $genre   = $_POST['genre'];
    $studio  = $_POST['studio'];
    $rating  = $_POST['rating'];
    $status  = $_POST['status'];
    $episode = $_POST['episode'];

    $sql = "INSERT INTO anime (judul, type, genre, studio, rating, status, episode) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$judul, $type, $genre, $studio, $rating, $status, $episode])) {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Anime</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniAnimeList</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="navbar-inner">
            <div class="navbar-brand">
                <span class="brand-icon">▶</span>
                <span class="brand-text">Mini<strong>AnimeList</strong></span>
            </div>
            <nav class="navbar-links">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="tambah.php" class="btn-add-nav">+ Add Anime</a>
            </nav>
        </div>
    </header>

<div class="form-wrapper">
    <div class="card">

        <h2>Tambah Anime</h2>

        <form method="POST">

            <label>Judul</label>
            <input type="text" name="judul" required>

            <label>Type</label>
            <select name="type" required>
                <option value="TV Series">TV Series</option>
                <option value="Movie">Movie</option>
                <option value="OVA">OVA</option>
                <option value="ONA">ONA</option>
                <option value="Special">Special</option>
            </select>

            <label>Genre</label>
            <input type="text" name="genre" required>

            <label>Studio</label>
            <input type="text" name="studio">

            <label>Episode</label>
            <input type="number" name="episode">

            <label>Rating</label>
            <input type="number" name="rating" min="0" max="10" step="0.1" required>

            <label>Status</label>
            <select name="status">
                <option value="Watching">Watching</option>
                <option value="Completed">Completed</option>
                <option value="Plan to Watch">Plan to Watch</option>
                <option value="Dropped">Dropped</option>
            </select>

            <button type="submit">Simpan</button>

        </form>

    </div>
</div>

</body>
</html>