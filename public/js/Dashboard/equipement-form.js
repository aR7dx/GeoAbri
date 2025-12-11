// Gestion de la navigation par étapes dans le formulaire d'équipement
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('#equipementTabs button[data-bs-toggle="tab"]');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.querySelector('#addEquipementModal form');
    
    let currentStep = 0;
    let maxStepReached = 0; // Étape maximum atteinte
    const totalSteps = tabs.length;

    // Fonction pour valider les champs requis d'un onglet
    function validateCurrentTab() {
        const currentTabPane = document.querySelector(`#equipementTabContent .tab-pane:nth-child(${currentStep + 1})`);
        const requiredFields = currentTabPane.querySelectorAll('input[required], textarea[required], select[required]');
        
        let isValid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Ajouter un message d'erreur si pas déjà présent
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'Ce champ est requis.';
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            } else {
                field.classList.remove('is-invalid');
                // Supprimer le message d'erreur s'il existe
                const errorMsg = field.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('invalid-feedback')) {
                    errorMsg.remove();
                }
            }
        });
        
        return isValid;
    }

    // Fonction pour afficher l'étape courante
    function showStep(step) {
        if (step >= 0 && step < totalSteps) {
            // Activer l'onglet correspondant
            const tab = new bootstrap.Tab(tabs[step]);
            tab.show();
            currentStep = step;
            
            // Mettre à jour l'étape maximum atteinte
            if (step > maxStepReached) {
                maxStepReached = step;
            }
            
            // Gérer l'affichage des boutons
            prevBtn.style.display = (step === 0) ? 'none' : 'inline-block';
            nextBtn.style.display = (step === totalSteps - 1) ? 'none' : 'inline-block';
            submitBtn.style.display = (step === totalSteps - 1) ? 'inline-block' : 'none';
            
            // Mettre à jour l'état des onglets (activer/désactiver)
            updateTabsState();
        }
    }

    // Fonction pour mettre à jour l'état cliquable des onglets
    function updateTabsState() {
        tabs.forEach((tab, index) => {
            if (index <= maxStepReached) {
                tab.style.pointerEvents = 'auto';
                tab.style.opacity = '1';
            } else {
                tab.style.pointerEvents = 'none';
                tab.style.opacity = '0.5';
            }
        });
    }

    // Bouton Précédent
    prevBtn.addEventListener('click', function() {
        showStep(currentStep - 1);
    });

    // Bouton Suivant
    nextBtn.addEventListener('click', function() {
        // Valider les champs requis avant de passer à l'étape suivante
        if (validateCurrentTab()) {
            showStep(currentStep + 1);
        }
    });

    // Permettre le clic sur les onglets déjà visités
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', function(e) {
            // Autoriser le clic seulement sur les onglets déjà atteints
            if (index <= maxStepReached) {
                e.preventDefault();
                showStep(index);
            } else {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Initialiser l'affichage
    showStep(0);
    updateTabsState();
});
