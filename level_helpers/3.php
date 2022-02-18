<?php
session_start();

require_once "../include/db.php";

$pdo = connect_db();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    header('Content-Type: application/json');

    $username = $_POST["username"];
    # $password = hash("sha256", $_POST["password"]);
    $query = "SELECT * FROM users WHERE username = '$username'";

    try {
        $statement = $pdo->prepare($query);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            'code' => 'FAILED',
            'message' => 'Bad query',
            'error' => $e,
            'query' => $query
        ]);
        exit;
    }

    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($user) && hash("sha256", $_POST["password"]) === $user["password"]) {
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        http_response_code(200);
        echo json_encode([
            'code' => 'OK',
            'message' => 'Logged in successfully',
            'query' => $query
        ]);
    } else {
        unset($_SESSION["id"]);
        unset($_SESSION["username"]);
        http_response_code(401);
        echo json_encode([
            'code' => 'FAILED',
            'message' => 'Wrong credentials',
            'query' => $query
        ]);
    }
    exit;
} else {
    http_response_code(400);
}
