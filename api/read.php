<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan. Gunakan GET."]);
    exit;
}

include_once '../config/database.php';
include_once '../models/anime.php';

$database = new Database();
$db       = $database->getConnection();
$anime    = new Anime($db);

if (!empty($_GET['status'])) {
    $anime->status = htmlspecialchars($_GET['status']);
    $stmt = $anime->readByStatus();
} else {
    $stmt = $anime->read();
}

$num = $stmt->rowCount();

if ($num > 0) {
    $anime_arr = [];
    while ($row = $stmt->fetch()) {
        $anime_arr[] = [
            "id"      => (int)   $row['id'],
            "judul"   =>         $row['judul'],
            "type"    =>         $row['type'],
            "genre"   =>         $row['genre'],
            "studio"  =>         $row['studio'],
            "rating"  => (float) $row['rating'],
            "status"  =>         $row['status'],
            "episode" => (int)   $row['episode']
        ];
    }
    http_response_code(200);
    echo json_encode(["status" => "success", "total" => $num, "data" => $anime_arr]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "not_found", "message" => "Tidak ada anime yang ditemukan."]);
}
?>