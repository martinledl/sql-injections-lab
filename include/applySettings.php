<?php

$showQueries = false;
$showAttackBank = false;
$safeCode = false;

if (!isset($_SESSION["showQueries"])) {
    $_SESSION["showQueries"] = $showQueries;
    $_SESSION["showAttackBank"] = $showAttackBank;
    $_SESSION["safeCode"] = $safeCode;
} else {
    $showQueries = $_SESSION["showQueries"];
    $showAttackBank = $_SESSION["showAttackBank"];
    $safeCode = $_SESSION["safeCode"];
}
