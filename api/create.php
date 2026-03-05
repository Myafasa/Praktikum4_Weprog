<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan. Gunakan POST."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Anime.php';

$database = new Database();
$db       = $database->getConnection();
$anime    = new Anime($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->judul) || empty($data->genre)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Field 'judul' dan 'genre' wajib diisi."]);
    exit;
}

$valid_type = ["TV Series", "Movie", "OVA", "ONA", "Special"];
if (!empty($data->type) && !in_array($data->type, $valid_type)) {
    http_response_code(400);
    echo json_encode([
        "status"  => "error",
        "message" => "Type tidak valid. Pilih: " . implode(", ", $valid_type)
    ]);
    exit;
}

$valid_status = ["Watching", "Completed", "Plan to Watch", "Dropped"];
if (!empty($data->status) && !in_array($data->status, $valid_status)) {
    http_response_code(400);
    echo json_encode([
        "status"  => "error",
        "message" => "Status tidak valid. Pilih: " . implode(", ", $valid_status)
    ]);
    exit;
}

if (isset($data->rating) && ($data->rating < 0 || $data->rating > 10)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Rating harus antara 0 dan 10."]);
    exit;
}

$anime->judul   = htmlspecialchars($data->judul);
$anime->type    = isset($data->type)    ? $data->type                         : null;
$anime->genre   = htmlspecialchars($data->genre);
$anime->studio  = isset($data->studio)  ? htmlspecialchars($data->studio)     : null;
$anime->rating  = isset($data->rating)  ? (float) $data->rating               : null;
$anime->status  = isset($data->status)  ? $data->status                       : "Plan to Watch";
$anime->episode = isset($data->episode) ? (int) $data->episode                : null;

if ($anime->create()) {
    http_response_code(201);
    echo json_encode([
        "status"  => "success",
        "message" => "Anime '{$anime->judul}' berhasil ditambahkan ke list."
    ]);
} else {
    http_response_code(503);
    echo json_encode(["status" => "error", "message" => "Gagal menambahkan anime."]);
}
?>