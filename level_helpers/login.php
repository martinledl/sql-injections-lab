<?php
session_start();

require_once "../include/db.php";

$safeCode = isset($_SESSION["safeCode"]) ? $_SESSION["safeCode"] : false;

$pdo = connect_db();

function login_successful($user, $query) {
    $_SESSION["id"] = $user["id"];
    $_SESSION["username"] = $user["username"];

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        'code' => 'OK',
        'message' => 'Logged in successfully',
        'query' => $query
    ]);
}


function login_failed($query) {
    unset($_SESSION["id"]);
    unset($_SESSION["username"]);

    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode([
        'code' => 'FAILED',
        'message' => 'Wrong credentials',
        'query' => $query
    ]);
}

function bad_query($error, $query) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'code' => 'FAILED',
        'message' => 'Bad query',
        'error' => $error,
        'query' => $query
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];

    if ($safeCode) {
        // Safe login
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", htmlspecialchars($username), PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user !== false && password_verify(htmlspecialchars($_POST["password"]), $user["password"])) {
            login_successful($user, $statement->queryString);
        } else {
            login_failed($statement->queryString);
        }
    } else {
        // Unsafe login
        $password = hash("sha256", $_POST["password"]);

        // Password validation
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

        try {
            $statement = $pdo->prepare($query);
        } catch (PDOException $e) {
            bad_query($e, $query);
            exit;
        }

        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (isset($user) && $user !== false) {
            login_successful($user, $query);
        } else {
            login_failed($query);
        }
    }

    
    exit;
} else {
    http_response_code(400);
}
