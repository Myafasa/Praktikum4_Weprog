<?php
include 'config/Database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Anime</title>
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

        <h2>Edit Anime</h2>

        <form method="POST">

            <label>Judul</label>
            <input type="text" name="judul"
                value="<?= htmlspecialchars($data['judul']); ?>" required>

            <label>Type</label>
            <select name="type" required>
                <option value="TV Series" <?= $data['type']=='TV Series'?'selected':''; ?>>TV Series</option>
                <option value="Movie" <?= $data['type']=='Movie'?'selected':''; ?>>Movie</option>
                <option value="OVA" <?= $data['type']=='OVA'?'selected':''; ?>>OVA</option>
                <option value="ONA" <?= $data['type']=='ONA'?'selected':''; ?>>ONA</option>
                <option value="Special" <?= $data['type']=='Special'?'selected':''; ?>>Special</option>
            </select>

            <label>Genre</label>
            <input type="text" name="genre"
                value="<?= htmlspecialchars($data['genre']); ?>" required>

            <label>Studio</label>
            <input type="text" name="studio"
                value="<?= htmlspecialchars($data['studio']); ?>">

            <label>Episode</label>
            <input type="number" name="episode"
                value="<?= htmlspecialchars($data['episode']); ?>">

            <label>Rating</label>
            <input type="number" name="rating" min="0" max="10" step="0.1"
                value="<?= htmlspecialchars($data['rating']); ?>" required>

            <label>Status</label>
            <select name="status">
                <option value="Watching" <?= $data['status']=='Watching'?'selected':''; ?>>Watching</option>
                <option value="Completed" <?= $data['status']=='Completed'?'selected':''; ?>>Completed</option>
                <option value="Plan to Watch" <?= $data['status']=='Plan to Watch'?'selected':''; ?>>Plan to Watch</option>
                <option value="Dropped" <?= $data['status']=='Dropped'?'selected':''; ?>>Dropped</option>
            </select>

            <button type="submit">Update</button>

        </form>

    </div>
</div>

</body>
</html>