<?php
include 'config/database.php';
$pdo = (new Database())->getConnection();

$stmt = $pdo->query("SELECT * FROM anime ORDER BY rating DESC");
$anime = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
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
        </div>
    </header>

    <div class="hero-strip">
        <div class="hero-inner">
            <span class="hero-label">MY LIST</span>
            <h1 class="hero-title">Anime Collection</h1>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= count($anime); ?></span>
                    <span class="stat-label">Total Anime</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number"><?= count(array_filter($anime, fn($a) => $a['status'] === 'Watching')); ?></span>
                    <span class="stat-label">Watching</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number"><?= count(array_filter($anime, fn($a) => $a['status'] === 'Completed')); ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-inner">
            <span class="filter-label">Sort by:</span>
            <button class="filter-btn active" data-sort="rating">Rating</button>
            <button class="filter-btn" data-sort="title">Title</button>
            <button class="filter-btn" data-sort="status">Status</button>
            <button class="filter-btn" data-sort="type">Type</button>
        </div>
    </div>

    <main class="main-content">
        <div class="container">

            <a href="tambah.php" class="card add-card">
                <div class="add-icon">+</div>
                <span class="add-label">Add New Anime</span>
            </a>

            <?php foreach($anime as $index => $row): ?>
                <div class="card anime-card"
                     style="animation-delay: <?= $index * 0.05 ?>s"
                     data-rating="<?= htmlspecialchars($row['rating']); ?>"
                     data-title="<?= htmlspecialchars(strtolower($row['judul'])); ?>"
                     data-status="<?= htmlspecialchars(strtolower($row['status'])); ?>"
                     data-type="<?= htmlspecialchars(strtolower($row['type'])); ?>">

                    <div class="rank-badge">#<?= $index + 1 ?></div>

                    <div class="card-header">
                        <div class="card-type-tag"><?= htmlspecialchars($row['type']); ?></div>
                        <div class="card-status-dot <?= strtolower(str_replace(' ', '-', $row['status'])); ?>"></div>
                    </div>

                    <h3 class="card-title"><?= htmlspecialchars($row['judul']); ?></h3>

                    <div class="card-meta">
                        <span class="meta-item studio-icon"><?= htmlspecialchars($row['studio']); ?></span>
                        <span class="meta-item genre-tag"><?= htmlspecialchars($row['genre']); ?></span>
                    </div>

                    <div class="card-divider"></div>

                    <div class="card-bottom">
                        <div class="rating-block">
                            <span class="star">★</span>
                            <span class="rating-score"><?= htmlspecialchars($row['rating']); ?></span>
                            <span class="rating-max">/10</span>
                        </div>
                        <div class="episode-block">
                            <span class="ep-count"><?= htmlspecialchars($row['episode']); ?></span>
                            <span class="ep-label">eps</span>
                        </div>
                    </div>

                    <div class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])); ?>">
                        <?= htmlspecialchars($row['status']); ?>
                    </div>

                    <div class="actions">
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">✎ Edit</a>
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Hapus anime ini?')">✕ Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="site-footer">
        <p>MiniAnimeList &copy; <?= date('Y') ?> — Inspired by <a href="https://myanimelist.net" target="_blank">MyAnimeList</a></p>
    </footer>

<script>
    const container   = document.querySelector('.container');
    const addCard     = document.querySelector('.add-card');
    const filterBtns  = document.querySelectorAll('.filter-btn');
    let   currentSort = 'rating';

    function getCards() {
        return [...document.querySelectorAll('.anime-card')];
    }

    function sortCards(sortBy) {
        const cards = getCards();

        cards.sort((a, b) => {
            if (sortBy === 'rating') {
                // Descending: highest rating first
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            } else if (sortBy === 'title') {
                // Ascending: A → Z
                return a.dataset.title.localeCompare(b.dataset.title);
            } else if (sortBy === 'status') {
                // Custom order: Watching → Completed → Plan to Watch → Dropped
                const order = { 'watching': 0, 'completed': 1, 'plan to watch': 2, 'dropped': 3 };
                return (order[a.dataset.status] ?? 9) - (order[b.dataset.status] ?? 9);
            } else if (sortBy === 'type') {
                // Ascending: A → Z
                return a.dataset.type.localeCompare(b.dataset.type);
            }
            return 0;
        });

        // Re-append: add-card stays first, then sorted anime cards
        container.innerHTML = '';
        container.appendChild(addCard);
        cards.forEach((card, i) => {
            // Update rank badge
            const badge = card.querySelector('.rank-badge');
            if (badge) badge.textContent = '#' + (i + 1);

            // Replay fade-in animation
            card.style.animation = 'none';
            card.offsetHeight; // force reflow
            card.style.animation = '';
            card.style.animationDelay = (i * 0.05) + 's';

            container.appendChild(card);
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentSort = btn.dataset.sort;
            sortCards(currentSort);
        });
    });
</script>

</body>
</html>