<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>/>

<body>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> 
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3"> 
            <h1 class="h2">Demandes en attentes</h1>
        </div> 
        
        <?php if (isset($pendings) && !empty($pendings)): ?>
            <div class="list-group">
                <?php foreach ($pendings as $pending): ?>
                     <form method="POST" class="list-group-item list-group-item-action" aria-current="true">

                        <input type="hidden" name="id" value="<?= base64_encode($pending['id_demande']); ?>">

                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($pending['nom']); ?></h5>
                            <small><?= htmlspecialchars($pending['date_debut']); ?></small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($pending['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="badge rounded-pill text-bg-warning"><?= htmlspecialchars($pending["status"]); ?></small>
                            <div class="d-flex flex-row gap-2">
                                <button type="submit" name="accept" value="1" class="btn btn-success">Accepter</button>
                                <button type="submit" name="deny" value="1" class="btn btn-danger">Refuser</button>
                            </div>
                        </div>
                </form>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="d-flex flex-row justify-content-center align-items-center">
                <span class="text-danger">Aucune demande en cours.</span>
            </div>
        <?php endif; ?>

    </main>


</body>
</html>