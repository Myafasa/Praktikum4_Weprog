<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan. Gunakan PUT."]);
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

$valid_type = ["TV Series", "Movie", "OVA", "ONA", "Special"];
if (isset($data->type) && !in_array($data->type, $valid_type)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Type tidak valid. Pilih: " . implode(", ", $valid_type)]);
    exit;
}

$valid_status = ["Watching", "Completed", "Plan to Watch", "Dropped"];
if (isset($data->status) && !in_array($data->status, $valid_status)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Status tidak valid. Pilih: " . implode(", ", $valid_status)]);
    exit;
}

if (isset($data->rating) && ($data->rating < 0 || $data->rating > 10)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Rating harus antara 0 dan 10."]);
    exit;
}

$anime->id   = (int) $data->id;
$existingRow = $anime->readOne();
if (!$existingRow) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Anime tidak ditemukan."]);
    exit;
}

$anime->judul   = isset($data->judul)   ? htmlspecialchars($data->judul)  : $existingRow['judul'];
$anime->type    = isset($data->type)    ? $data->type                     : $existingRow['type'];
$anime->genre   = isset($data->genre)   ? htmlspecialchars($data->genre)  : $existingRow['genre'];
$anime->studio  = isset($data->studio)  ? htmlspecialchars($data->studio) : $existingRow['studio'];
$anime->rating  = isset($data->rating)  ? (float) $data->rating           : $existingRow['rating'];
$anime->status  = isset($data->status)  ? $data->status                   : $existingRow['status'];
$anime->episode = isset($data->episode) ? (int) $data->episode            : $existingRow['episode'];

if ($anime->update()) {
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Data anime '{$anime->judul}' berhasil diperbarui."
    ]);
} else {
    http_response_code(503);
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui data anime."]);
}
?>