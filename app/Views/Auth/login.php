<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>
    <div class="row justify-content-center" style="padding-top: 10em;">
        <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
            <div class="card-body">
                <h1>Connexion</h1>
                <p class="text-muted">Connectez-vous à votre compte</p>
                <div class="mb-3">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" placeholder="Email">
                </div>
                <div class="mb-4">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Mot de passe">
                </div>
                <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-primary px-4">Se connecter</button>
                </div>
                </div>
            </div>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
                <div>
                <h2>S'inscrire</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <button type="button" class="btn btn-primary active mt-3">
                    <a href="inscription.php" style="text-decoration: none; color: #fff">S'inscrire</a>
                </button>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

</body>
</html>