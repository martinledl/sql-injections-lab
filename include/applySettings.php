<?php

$showQueries = false;

if (!isset($_SESSION["showQueries"])) {
    $_SESSION["showQueries"] = $showQueries;
} else {
    $showQueries = $_SESSION["showQueries"];
}
