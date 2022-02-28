<?php

// Create database file, create $pdo for later use
function connect_db()
{
    try {
        $pdo = new PDO("sqlite:" . $_SERVER['DOCUMENT_ROOT'] . "/db/database.db");
        $pdo->exec("PRAGMA foreign_keys = ON;");
        $pdo->exec("PRAGMA case_sensitive_like = false;");      // For search purposes

        return $pdo;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}