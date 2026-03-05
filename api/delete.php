<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan. Gunakan DELETE."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Anime.php';

$database = new Database();
$db       = $database->getConnection();
$anime    = new Anime($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Field 'id' wajib diisi."]);
    exit;
}

$anime->id   = (int) $data->id;
$existingRow = $anime->readOne();

if (!$existingRow) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Anime dengan id={$anime->id} tidak ditemukan."]);
    exit;
}

$judulAnime = $existingRow['judul'];

if ($anime->delete()) {
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Anime '{$judulAnime}' berhasil dihapus dari list."
    ]);
} else {
    http_response_code(503);
    echo json_encode(["status" => "error", "message" => "Gagal menghapus anime."]);
}
?>