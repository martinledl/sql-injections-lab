<?php
session_start();

include "../include/applySettings.php";

$user_logged_in = false;

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
    $user_logged_in = true;
    $username = $_SESSION["username"];
}

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
    <title>SQL Injections Lab | 3</title>
</head>

<body>
    <header class="border-bottom p-2 d-flex justify-content-between align-items-center">
        <a href="#" class="m-2 btn btn-secondary" onclick="resetLevel()">&larr; Zpět</a>
        <?php if ($user_logged_in) : ?>
            <form action="" method="get" id="logout">
                <button type="submit" class="btn btn-outline-secondary me-3 mb-0">Odhlásit se</button>
                <span>Přihlášen jako: <b><?= $username ?></b></span>
            </form>
        <?php endif ?>
    </header>

    <main class="container text-center form">
        <form class="mt-5 m-auto" style="width: 30vw;" method="post" id="login">
            <div class="form-floating">
                <input type="username" class="form-control" id="username" placeholder="Uživatelské jméno" required>
                <label for="username">Uživatelské jméno</label>
            </div>
            <div class="form-floating mt-1">
                <input type="password" class="form-control" id="password" placeholder="Heslo" required>
                <label for="password">Heslo</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Přihlásit se</button>
            <p class='text-danger mt-3' id="errorMessage"></p>
        </form>

        <h6 class="m-0" id="query" style="visibility: <?= $showQueries ? 'visible' : 'hidden' ?>;"></h6>
    </main>

    <div class="p-2 position-absolute bottom-0 end-0 form-check me-2 mb-2">
        <input class="form-check-input" type="checkbox" value="" id="disallowSpecialChars">
        <label class="form-check-label" for="disallowSpecialChars">
            Zakázat speciální znaky
        </label>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <script>
        window.onload = function() {
            let query = sessionStorage.getItem("lastQuery");
            if (query !== null && query !== undefined) {
                displayQuery(query);
                sessionStorage.removeItem("lastQuery");
            }
        }

        function displayQuery(query) {
            document.getElementById("query").innerHTML = `Query: <code>${query}</code>`;
        }

        function resetLevel() {
            sessionStorage.removeItem("lastQuery");

            $.ajax({
                url: '/helpers/logout.php',
                type: 'get',
                success: function(response) {
                    window.location.href = "/index.php";
                }
            });
        }

        function validateForm(input) {
            let valid = /[^a-zA-Z0-9\-]/;

            if (!valid.test(input)) {
                return true;
            } else {
                return false;
            }
        }



        $(document).ready(function() {
            $("#login").submit(function(event) {
                event.preventDefault();

                let username = $("#username").val();
                let password = $("#password").val();
                let disallowSpecialChars = $("#disallowSpecialChars").is(':checked');

                if ((disallowSpecialChars && validateForm(username)) || disallowSpecialChars === false) {
                    $.ajax({
                        url: "/level_helpers/3.php",
                        type: "POST",
                        data: {
                            username: username,
                            password: password
                        },
                        success: function(response) {
                            sessionStorage.setItem("lastQuery", response.query);
                            window.location.href = "/levels/3.php";
                        },
                        error: function(response) {
                            if (response.status === 401) {
                                displayQuery(response.responseJSON.query);
                                document.getElementById("errorMessage").innerHTML = "Špatné přihlašovací údaje";
                            } else if (response.status === 400 && query !== null) {
                                displayQuery(response.responseJSON.query);
                                document.getElementById("errorMessage").innerHTML = "Špatné databázové query";
                            } else {
                                alert(`Někde se stala chyba...\n\nError: ${response.status}\n${response.statusText}`)
                            }
                        }
                    });
                } else {
                    alert("Formulář obsahuje nepovolené znaky");
                }
            });

            $("#logout").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: '/helpers/logout.php',
                    type: 'get',
                    success: function(response) {
                        window.location.href = "/levels/3.php";
                    }
                });
            });
        });
    </script>
</body>

</html>
