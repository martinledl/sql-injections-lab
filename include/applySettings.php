<?php

// Default values for settings
$showQueries = false;
$showAttackBank = false;
$safeCode = false;

// If there is a value for this setting in session, save it
if (isset($_SESSION["showQueries"])) {
    $showQueries = $_SESSION["showQueries"];
} else {
    $_SESSION["showQueries"] = $showQueries;
}

if (isset($_SESSION["showAttackBank"])) {
    $showAttackBank = $_SESSION["showAttackBank"];
} else {
    $_SESSION["showAttackBank"] = $showAttackBank;
}

if (isset($_SESSION["safeCode"])) {
    $safeCode = $_SESSION["safeCode"];
} else {
    $_SESSION["safeCode"] = $safeCode;
}
