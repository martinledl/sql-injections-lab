<?php
session_start();

require_once "../include/db.php";

$safeCode = isset($_SESSION["safeCode"]) ? $_SESSION["safeCode"] : false;

$pdo = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = $_POST["search"];
    error_log($safeCode);

    if ($safeCode) {
        if (strlen($_POST["search"]) === 0) {
            $data = ['results' => [], 'query' => null];
        } else {
            $statement = $pdo->prepare("SELECT * FROM shop_items WHERE title LIKE '%' || :search || '%' ");
            $statement->bindValue(":search", htmlspecialchars($search), PDO::PARAM_STR);
            $statement->execute();
    
            $data = ['results' => $statement->fetchAll(PDO::FETCH_ASSOC), 'query' => $statement->queryString];
        }
    } else {
        $query = "SELECT * FROM shop_items WHERE title LIKE '%$search%'";
        $stm = $pdo->prepare($query);
        $stm->execute();
    
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $data = ['results' => $results, 'query' => $query];
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);

    exit;
} else {
    http_response_code(400);
}