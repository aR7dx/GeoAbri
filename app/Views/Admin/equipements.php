<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="/public/css/dashboard.css"/>

<script defer src="/public/js/Dashboard/equipements.js"></script>

<body>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestion des équipements</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-primary">Ajouter un équipement</button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Equipements</h5>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <!-- Search -->
                            <div class="form-outline mb-2" data-mdb-input-init>
                                <input type="search" id="search-input" class="form-control" placeholder="Rechercher un équipement..." aria-label="Search" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">

                <!-- Table -->
                <div class="table-responsive">
                    <table id="equipements-table" class="table table-hover mb-0">
                        <thead class="table-light w-100">
                            <tr>
                                <th>ID</th>
                                <th>NOM</th>
                                <th>TYPE</th>
                                <th>ADRESSE</th>
                                <th>PROPRIETAIRE</th>
                                <th class="d-flex justify-content-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="text-muted">
                        <span id="nb-equipements-results"></span>
                    </div>
                    <nav>
                        <ul id="equipements-pagination" class="pagination pagination-sm mb-0"></ul>
                    </nav>
                </div>
            </div>
        </div>

    </main>

</body>
</html>