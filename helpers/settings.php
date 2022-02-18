<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["showQueries"])) {
    $_SESSION["showQueries"] = $_POST["showQueries"] === "true";
    var_dump($_POST["showQueries"]);
}