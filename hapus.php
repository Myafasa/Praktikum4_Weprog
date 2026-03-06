<?php
include 'config/Database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$sql = "DELETE FROM anime WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$id])) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data.";
}
?>
