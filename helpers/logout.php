<?php

session_start();

// Unset some of the session variables.
unset($_SESSION["id"]);
unset($_SESSION["username"]);

?>
