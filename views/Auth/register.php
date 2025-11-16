<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/views/Includes/meta.php'; ?>

<body class="bg-light">
    <div class="card p-3 ms-auto me-auto border-black" style="margin-top: 4em; margin-bottom: 4em; min-width: min-content; max-width: 35vw;">
        <div class="text-center d-flex flex-column gap-3 mb-3">
            <div class="bg-black p-3 m-auto rounded-circle" style="width: fit-content;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#fff" class="bi bi-lock" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                </svg>
            </div>
            <h2 class="text-muted">Inscrivez-vous en créant votre compte</h2>
        </div>

        <form id="form" action="" method="POST" class="d-flex flex-column gap-3" novalidate>
            <div class="form-group d-flex flex-column gap-2">
                <label><strong>Infomations:</strong></label>
                <div class="d-flex gap-1">
                    <!-- Nom -->
                    <div class="form-floating w-100">
                        <input class="form-control" type="text" id="nom" name="nom" placeholder="Nom :" required>
                        <label for="nom">Nom :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre nom.
                        </div>
                    </div>

                    <!-- Prénom -->
                    <div class="form-floating w-100">
                        <input class="form-control" type="text" id="prenom" name="prenom" placeholder="Prénom :" required>
                        <label for="prenom">Prénom :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre prénom.
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-floating">
                    <input class="form-control" type="email"  id="email" name="email" placeholder="Email :" required>
                    <label for="email">Email :</label>
                    <div class="invalid-feedback">
                        Vous devez entrer votre email.
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="form-floating">
                    <input class="form-control" type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" pattern="^0[1-9][0-9]{8}$" inputmode="numeric" maxlength="14" required>
                    <label for="telephone">Téléphone :</label>
                    <div class="invalid-feedback">
                        Vous devez entrer un numéro de téléphone valide.
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="form-group d-flex flex-column gap-2">
                <label><strong>Adresse:</strong></label>
                <div class="d-flex gap-1">
                    <!-- Numéro Adresse -->
                    <div class="form-floating w-25">
                        <input class="form-control" type="number"  id="numAdresse" name="numAdresse" placeholder="Numéro :" required>
                        <label for="numAdresse">Numéro :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre numéro d'adresse.
                        </div>
                    </div>
                    <!-- Nom Adresse -->
                    <div class="form-floating w-100">
                        <input class="form-control" type="text"  id="nomAdresse" name="nomAdresse" placeholder="Nom :" required>
                        <label for="nomAdresse">Nom :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre nom d'adresse.
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <!-- Ville -->
                    <div class="form-floating w-100">
                        <input class="form-control" type="text"  id="ville" name="ville" placeholder="Ville :" required>
                        <label for="ville">Ville :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre la ville.
                        </div>
                    </div>
                    <!-- Code Postal -->
                    <div class="form-floating w-50">
                        <input class="form-control" type="number"  id="codePostal" name="codePostal" placeholder="Code postal :" required>
                        <label for="codePostal">Code postal :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre le code postal.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="form-group d-flex flex-column gap-2">
                <label><strong>Mot de passe:</strong></label>

                <div class="form-floating">
                    <input class="form-control" type="password" id="password" name="password" placeholder="Mot de passe :" required>
                    <label for="password">Mot de passe :</label>
                    <div class="invalid-feedback">
                        Vous devez entrer votre mot de passe.
                    </div>
                </div>

                <div class="form-floating">
                    <input class="form-control" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confimation :" required>
                    <label for="confirmPassword">Confimation :</label>
                    <div class="invalid-feedback">
                        Vous devez entrer votre mot de passe.
                    </div>
                </div>
            </div>
            

            <!-- Bouton envoyer -->
            <button type="submit" class="btn btn-success">Créer le compte</button>
        </form>
    </div>

    
    <script defer src="/public/js/checkRegisterForm.js"></script>

</body>
</html>