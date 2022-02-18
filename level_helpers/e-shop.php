<?php

require_once "../include/db.php";

$pdo = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = $_POST["search"];
    $query = "SELECT * FROM shop_items WHERE title LIKE '%$search%'";
    $stm = $pdo->prepare($query);
    $stm->execute();

    $results = $stm->fetchAll(PDO::FETCH_ASSOC);
    $data = ['results' => $results, 'query' => $query];

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
} else {
    http_response_code(400);
}