<?php
session_start();

include "include/applySettings.php";

?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <title>SQL Injections Lab</title>
</head>

<body>
    <main class="container py-3 p-sm-3">
        <h1 class="text-center my-5">SQL Injections Lab</h1>
        <section class="mb-5">
            <div class="d-flex justify-content-evenly flex-wrap">
                <div class="card m-2" style="width: 20rem">
                    <div class="card-body position-relative pb-5">
                        <h3 class="card-title">Intro</h3>
                        <p class="card-text">of the card's content.</p>
                        <a href="/intro" class="btn btn-primary position-absolute bottom-0 mb-3">Otevřít &rarr;</a>
                    </div>
                </div>
                <div class="card m-2" style="width: 20rem;">
                    <div class="card-body position-relative pb-5">
                        <h3 class="card-title">E-shop</h3>
                        <p class="card-text">Nejjednodušší ukázka SQL injekce na stránce teoretického obchodu, kde se dají vyhledávat produkty.</p>
                        <a href="/levels/e-shop.php" class="btn btn-primary position-absolute bottom-0 mb-3">Otevřít &rarr;</a>
                    </div>
                </div>
                <div class="card m-2" style="width: 20rem;">
                    <div class="card-body position-relative pb-5">
                        <h3 class="card-title">Přihlášení</h3>
                        <p class="card-text">Pokud je zranitelný přihlašovací formulář, je možné se pomocí SQL injekce přihlásit i bez hesla.</p>
                        <a href="/levels/login.php" class="btn btn-primary position-absolute bottom-0 mb-3">Otevřít &rarr;</a>
                    </div>
                </div>

            </div>

        </section>
    </main>

    <!-- Settings button -->
    <button class="position-absolute top-0 end-0 btn p-2" data-bs-toggle="modal" data-bs-target="#settingsModal">
        <i class="fas fa-cog fs-4"></i>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nastavení</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="showQueries" <?= $showQueries ? "checked" : "" ?>>
                        <label class="form-check-label" for="showQueries">
                            Zobrazit SQL query
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="showAttackBank" <?= $showAttackBank ? "checked" : "" ?>>
                        <label class="form-check-label" for="showAttackBank">
                            Zobrazit nabídku útoků
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="safeCode" <?= $safeCode ? "checked" : "" ?>>
                        <label class="form-check-label" for="safeCode">
                            Zabezpečit tuto webovou stránku
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-primary" id="saveSettings" data-bs-dismiss="modal">Uložit změny</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        $("#saveSettings").click(function() {
            $.ajax({
                url: "/helpers/settings.php",
                type: "POST",
                data: {
                    showQueries: $("#showQueries").is(':checked'),
                    showAttackBank: $("#showAttackBank").is(':checked'),
                    safeCode: $("#safeCode").is(':checked')
                }
            })
        });
    </script>
</body>

</html>
