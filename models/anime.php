<?php
class Anime {
    private $conn;
    private $table_name = "anime";

    public $id;
    public $judul;
    public $type;    
    public $genre;
    public $studio;
    public $rating;  
    public $status;  
    public $episode; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ ALL
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetch();
    }

    public function readByStatus() {
        $query = "SELECT * FROM " . $this->table_name .
                 " WHERE status = ? ORDER BY judul ASC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute([$this->status]);
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name .
                 " (judul, type, genre, studio, rating, status, episode)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->judul,
            $this->type,
            $this->genre,
            $this->studio,
            $this->rating,
            $this->status,
            $this->episode
        ]);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name .
                 " SET judul = ?, type = ?, genre = ?, studio = ?,
                       rating = ?, status = ?, episode = ?
                   WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->judul,
            $this->type,
            $this->genre,
            $this->studio,
            $this->rating,
            $this->status,
            $this->episode,
            $this->id
        ]);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
}
?>