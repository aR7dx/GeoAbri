<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="<?=  asset('css/dashboard.css') ?>"/>

<body>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestion des utilisateurs</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-primary">Ajouter un utilisateur</button>
            </div>
        </div>

        <div id="users-section">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Dernière connexion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rempli par JS -->
                    </tbody>
                </table>
            </div>

            <nav aria-label="Pagination utilisateurs">
                <ul class="pagination" id="users-pagination"></ul>
            </nav>
        </div>

    </main>

</body>
</html>