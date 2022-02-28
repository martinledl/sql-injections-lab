<?php
session_start();

require_once "../include/db.php";

// Check if safeCode setting is set
$safeCode = isset($_SESSION["safeCode"]) ? $_SESSION["safeCode"] : false;

// Prepare target database
$pdo = connect_db();

// Set user details to session, return JSON data about successful login
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

// Make sure no user datils are stored in session, return JSON data about unsuccessful login
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

// Return JSON data about bad query error
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

// Check for the correct request method and both username and password being sent
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];

    if ($safeCode) {
        // Perform safe login
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", htmlspecialchars($username), PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Password validation
        if ($user !== false && password_verify(htmlspecialchars($_POST["password"]), $user["password"])) {
            login_successful($user, $statement->queryString);
        } else {
            login_failed($statement->queryString);
        }
    } else {
        // Unsafe login by encrypting provided password and then checking it with database
        // Beside SQL injection can leak information about which password hashing algorithm is used
        $password = hash("sha256", $_POST["password"]);
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

        try {
            $statement = $pdo->prepare($query);
        } catch (PDOException $e) {
            bad_query($e, $query);
            exit;
        }

        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // If the user with given credentials has been found, return successful login
        if (isset($user) && $user !== false) {
            login_successful($user, $query);
        } else {
            login_failed($query);
        }
    }

    
    exit;
} else {
    // Something went wrong with the request -> return just error code 400 (Bad Request)
    http_response_code(400);
}
