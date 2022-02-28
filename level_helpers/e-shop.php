<?php
session_start();

require_once "../include/db.php";

// Check if safeCode setting is set
$safeCode = isset($_SESSION["safeCode"]) ? $_SESSION["safeCode"] : false;

// Prepare target database
$pdo = connect_db();

// Get value of "search" in post request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = $_POST["search"];

    // Perform database search safely, if safeCode option is true
    if ($safeCode) {
        // If for some reason $search is empty, return no data, because the variable should never be empty
        if (strlen($search) === 0) {
            $data = ['results' => [], 'query' => null];
        } else {
            $statement = $pdo->prepare("SELECT * FROM shop_items WHERE title LIKE '%' || :search || '%' ");
            $statement->bindValue(":search", htmlspecialchars($search), PDO::PARAM_STR);
            $statement->execute();
    
            $data = ['results' => $statement->fetchAll(PDO::FETCH_ASSOC), 'query' => $statement->queryString];
        }
    // VULNERABLE: insert $search right into the query without any sanitization or escaping
    } else {
        $query = "SELECT * FROM shop_items WHERE title LIKE '%$search%'";
        $stm = $pdo->prepare($query);
        $stm->execute();
    
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $data = ['results' => $results, 'query' => $query];
    }

    // Everything ready to return successfully
    // Return response code 200 and JSON data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);

    exit;
} else {
    // Something went wrong with the request -> return just error code 400 (Bad Request)
    http_response_code(400);
}