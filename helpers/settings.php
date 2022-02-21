<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["showQueries"]) && isset($_POST["showAttackBank"]) && isset($_POST["safeCode"])) {
    $_SESSION["showQueries"] = $_POST["showQueries"] === "true";
    $_SESSION["showAttackBank"] = $_POST["showAttackBank"] === "true";
    $_SESSION["safeCode"] = $_POST["safeCode"] === "true";
}