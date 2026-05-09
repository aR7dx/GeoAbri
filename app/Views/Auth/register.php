<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="<?= asset('css/register.css') ?>/>

<body class="bg-light">
    <div id="register-panel" class="card p-4 ms-auto me-auto border-black shadow">

        <div>
            <strong>
                <a href="<?= $router->generate('home'); ?>" class="text-start text-decoration-none text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>    
                    Retour à l'accueil
                </a>
            </strong>
        </div>

        <div class="text-center d-flex flex-column gap-3 mb-4">
            <div class="bg-black p-3 m-auto rounded-circle" style="width: fit-content;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#fff" class="bi bi-lock" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                </svg>
            </div>
            <h2 class="text-muted">Inscrivez-vous en créant votre compte</h2>
        </div>

        <div class="mb-4 px-3">
            <div class="d-flex justify-content-between align-items-center position-relative">
                <!-- Barre de fond -->
                <div class="position-absolute z-0" style="height: 2px; background-color: #dee2e6; top: 50%; left: 20px; right: 20px;"></div>
                <!-- Barre de progression -->
                <div class="position-absolute bg-warning z-0" id="progress-bar" style="height: 2px; top: 50%; left: 20px; width: 0%; transition: width 0.3s ease;"></div>
                
                <div class="d-flex justify-content-between w-100 position-relative" style="z-index: 1;">
                    <div class="text-center">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto step-indicator" style="width: 40px; height: 40px; border: 3px solid white;" data-step="1">
                            <strong>1</strong>
                        </div>
                        <small class="d-block mt-1 text-muted">Type</small>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto step-indicator" style="width: 40px; height: 40px; border: 3px solid white;" data-step="2">
                            <strong>2</strong>
                        </div>
                        <small class="d-block mt-1 text-muted">Identité</small>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto step-indicator" style="width: 40px; height: 40px; border: 3px solid white;" data-step="3">
                            <strong>3</strong>
                        </div>
                        <small class="d-block mt-1 text-muted">Contact</small>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto step-indicator" style="width: 40px; height: 40px; border: 3px solid white;" data-step="4">
                            <strong>4</strong>
                        </div>
                        <small class="d-block mt-1 text-muted">Sécurité</small>
                    </div>
                </div>
            </div>
        </div>

        <form id="form" action="" method="POST" class="d-flex flex-column gap-3" novalidate>
            
            <!-- Étape 1: Type de compte -->
            <div class="form-step" data-step="1">
                <h4 class="mb-3">Choisissez votre type de compte</h4>
                <div class="list-group list-group-radio d-grid gap-2 border-0">
                    
                    <div class="position-relative">
                        <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="type" id="typeUtilisateur" value="utilisateur" checked required>
                        <label class="list-group-item py-3 pe-5 radio-card" for="typeUtilisateur" style="cursor: pointer; border: 2px solid #dee2e6; border-radius: 0.375rem; transition: all 0.3s ease;">
                            <strong class="fw-semibold">Utilisateur</strong>
                            <span class="d-block small opacity-75">Compte personnel pour accéder aux équipements</span>
                        </label>
                    </div>

                    <div class="position-relative">
                        <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="type" id="typeAssociation" value="association" required>
                        <label class="list-group-item py-3 pe-5 radio-card" for="typeAssociation" style="cursor: pointer; border: 2px solid #dee2e6; border-radius: 0.375rem; transition: all 0.3s ease;">
                            <strong class="fw-semibold">Association</strong>
                            <span class="d-block small opacity-75">Compte pour une association ou organisation</span>
                        </label>
                    </div>
                    <div class="position-relative">
                        <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="type" id="typeCollectivite" value="collectivite" required>
                        <label class="list-group-item py-3 pe-5 radio-card" for="typeCollectivite" style="cursor: pointer; border: 2px solid #dee2e6; border-radius: 0.375rem; transition: all 0.3s ease;">
                            <strong class="fw-semibold">Collectivité</strong>
                            <span class="d-block small opacity-75">Compte pour une collectivité territoriale</span>
                        </label>
                    </div>
                    <div class="position-relative">
                        <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="type" id="typeClub" value="club" required>
                        <label class="list-group-item py-3 pe-5 radio-card" for="typeClub" style="cursor: pointer; border: 2px solid #dee2e6; border-radius: 0.375rem; transition: all 0.3s ease;">
                            <strong class="fw-semibold">Club</strong>
                            <span class="d-block small opacity-75">Compte pour un club sportif ou culturel</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Étape 2: Identité -->
            <div class="form-step d-none" data-step="2">
                <h4 class="mb-3">Vos informations personnelles</h4>
                <div class="d-flex gap-2">
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
            </div>

            <!-- Étape 3: Contact et adresse -->
            <div class="form-step d-none" data-step="3">
                <h4 class="mb-3">Vos coordonnées</h4>
                <div class="d-flex flex-column gap-2">
                    <!-- Email -->
                    <div class="form-floating">
                        <input class="form-control" type="email" id="email" name="email" placeholder="Email :" required>
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

                    <div class="d-flex gap-2">
                        <!-- Ville -->
                        <div class="form-floating w-100">
                            <input class="form-control" type="text" id="ville" name="ville" placeholder="Ville :" required>
                            <label for="ville">Ville :</label>
                            <div class="invalid-feedback">
                                Vous devez entrer la ville.
                            </div>
                        </div>
                        <!-- Code Postal -->
                        <div class="form-floating w-50">
                            <input class="form-control" type="number" id="codePostal" name="codePostal" placeholder="Code postal :" required>
                            <label for="codePostal">CP :</label>
                            <div class="invalid-feedback">
                                Vous devez entrer le code postal.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 4: Mot de passe -->
            <div class="form-step d-none" data-step="4">
                <h4 class="mb-3">Sécurisez votre compte</h4>
                <div class="d-flex flex-column gap-2">
                    <div class="form-floating">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Mot de passe :" required>
                        <label for="password">Mot de passe :</label>
                        <div class="invalid-feedback">
                            Vous devez entrer votre mot de passe.
                        </div>
                    </div>

                    <div class="form-floating">
                        <input class="form-control" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmation :" required>
                        <label for="confirmPassword">Confirmation :</label>
                        <div class="invalid-feedback">
                            Les mots de passe ne correspondent pas.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons de navigation -->
            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">Précédent</button>
                <button type="button" class="btn btn-warning ms-auto" id="nextBtn">Suivant</button>
                <button type="submit" class="btn btn-success ms-auto" id="submitBtn" style="display: none;">Créer le compte</button>
            </div>
        </form>
    </div>

    
    <script defer src=<?= asset('/js/CheckForms/checkRegisterForm.js') ?>></script>

</body>
</html>