<?php

$showQueries = false;
$showAttackBank = false;

if (!isset($_SESSION["showQueries"])) {
    $_SESSION["showQueries"] = $showQueries;
    $_SESSION["showAttackBank"] = $showAttackBank;
} else {
    $showQueries = $_SESSION["showQueries"];
    $showAttackBank = $_SESSION["showAttackBank"];
}
