<?php
// Prepare database folder and the .db file
$db_dir = __DIR__ . "/../db/";
$dbfilename = $db_dir . "database.db";

if (!file_exists($db_dir)) {
    mkdir($db_dir);
}

if (!file_exists($dbfilename)) {
    touch($dbfilename);
} else {
    unlink($dbfilename);
    touch($dbfilename);
}

$webroot = __DIR__ . "/..";

try {
    $pdo = new PDO("sqlite:" . $dbfilename);
} catch (PDOException $e) {
    die($e->getMessage());
}

// Foreign key support
$pdo->exec("PRAGMA foreign_keys = ON;");

// Create shop items table
$stm = $pdo->prepare("CREATE TABLE IF NOT EXISTS shop_items (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    price FLOAT NOT NULL
)");
$stm->execute();

// Insert default products
$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('Sluchátka', 499)");
$stm->execute();

$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('iPad', 18590)");
$stm->execute();

$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('Klávesnice', 1090)");
$stm->execute();

$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('Počítačová myš', 420)");
$stm->execute();

$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('Nabíječka', 299)");
$stm->execute();

$stm = $pdo->prepare("INSERT INTO shop_items (title, price) VALUES ('Mikrofon', 2180)");
$stm->execute();


// Create users table
$stm = $pdo->prepare("CREATE TABLE IF NOT EXISTS users (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)");
$stm->execute();

// Insert default users

$stm = $pdo->prepare("INSERT INTO users (username, password) VALUES ('steve', :password)");
$stm->bindValue("password", hash("sha256", "daisy"), PDO::PARAM_STR);
$stm->execute();

$stm = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', :password)");
$stm->bindValue("password", hash("sha256", "toor"), PDO::PARAM_STR);
$stm->execute();