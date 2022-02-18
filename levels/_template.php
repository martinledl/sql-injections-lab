<?php
session_start();

include "../include/applySettings.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/styles/1.css">
    <title>SQL Injections Lab | Template</title>
</head>

<body>
    <a href="/index.php" class="m-2 btn btn-secondary">&larr; Zpět</a>

    <main class="container text-center">
        

        <h6 class="m-0" id="query" style="visibility: <?= $showQueries ? 'visible' : 'hidden' ?>;"></h6>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <script>

        $(document).ready(function() {
            $("#search").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: "/level_helpers/1.php",
                    type: "POST",
                    data: {
                        search: $("#searchField").val()
                    },
                    success: function(response) {
                        renderResults(response);
                    },
                    error: function(response) {
                        renderResults(response, true);
                    }
                });

            });
        });
    </script>
</body>

</html>