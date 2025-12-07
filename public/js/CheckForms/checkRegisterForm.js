const form = document.getElementById('form');
let currentStep = 1;
const totalSteps = 4;

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const submitBtn = document.getElementById('submitBtn');

// Fonction pour gérer le style des boutons radio
function updateRadioStyles() {
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        const label = document.querySelector(`label[for="${radio.id}"]`);
        if (label) {
            if (radio.checked) {
                label.style.borderColor = '#ffc107';
                label.style.backgroundColor = '#fff3cd';
            } else {
                label.style.borderColor = '#dee2e6';
                label.style.backgroundColor = 'white';
            }
        }
    });
}

// Ajouter des écouteurs sur les boutons radio
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', updateRadioStyles);
});

// Fonction pour afficher l'étape active
function showStep(step) {
    document.querySelectorAll('.form-step').forEach(stepEl => {
        stepEl.classList.add('d-none');
    });
    
    const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
    if (currentStepEl) {
        currentStepEl.classList.remove('d-none');
    }
    
    // Mise à jour des boutons
    prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
    nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
    submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
    
    // Mise à jour de la barre de progression
    updateProgressBar(step);
}

// Fonction pour mettre à jour la barre de progression
function updateProgressBar(step) {
    const progressBar = document.getElementById('progress-bar');
    // Calculer la largeur en fonction de l'espace entre les nœuds
    // Le conteneur de la barre a un padding de 20px de chaque côté
    const containerWidth = progressBar.parentElement.offsetWidth - 40; // -40px pour left et right
    const percentage = ((step - 1) / (totalSteps - 1)) * 100;
    progressBar.style.width = `calc(${percentage}% * ${containerWidth / progressBar.parentElement.offsetWidth})`;
    
    // Mise à jour des indicateurs d'étape
    document.querySelectorAll('.step-indicator').forEach(indicator => {
        const indicatorStep = parseInt(indicator.getAttribute('data-step'));
        // quand on revient en arriere
        if (indicatorStep < step) {
            indicator.classList.remove('bg-secondary', 'bg-warning', 'text-white');
            indicator.classList.add('bg-warning', 'text-dark');
        } 
        // sur la page actuelle
        else if (indicatorStep === step) {
            indicator.classList.remove('bg-secondary', 'bg-warning', 'text-white');
            indicator.classList.add('bg-warning', 'text-dark');
        } 
        else {
            indicator.classList.remove('bg-warning', 'text-dark');
            indicator.classList.add('bg-secondary', 'text-white');
        }
    });
}

// Fonction pour valider les champs de l'étape actuelle
function validateCurrentStep() {
    const currentStepEl = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    let valid = true;
    
    // Retirer les classes de validation précédentes
    currentStepEl.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    currentStepEl.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
    
    // Valider tous les champs de l'étape actuelle
    currentStepEl.querySelectorAll('input').forEach(input => {
        if (!input.checkValidity()) {
            input.classList.add('is-invalid');
            valid = false;
        } else {
            input.classList.add('is-valid');
        }
    });
    
    // Validation spécifique pour le code postal (étape 3)
    if (currentStep === 3) {
        const codePostal = form.querySelector('#codePostal');
        if (codePostal.value.length !== 5) {
            codePostal.classList.add('is-invalid');
            valid = false;
        }
    }
    
    // Validation spécifique pour les mots de passe (étape 4)
    if (currentStep === 4) {
        const password = form.querySelector('#password');
        const confirm = form.querySelector('#confirmPassword');
        if (password.value !== confirm.value) {
            confirm.classList.add('is-invalid');
            valid = false;
        }
    }
    
    return valid;
}

// Bouton suivant
nextBtn.addEventListener('click', function() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }
});

// Bouton précédent
prevBtn.addEventListener('click', function() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
});

// Soumission du formulaire
form.addEventListener('submit', function (e) {
    e.preventDefault();
    
    // Valider l'étape finale
    if (!validateCurrentStep()) {
        return;
    }
    
    // Valider toutes les étapes
    let allValid = true;
    for (let step = 1; step <= totalSteps; step++) {
        const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);
        stepEl.querySelectorAll('input').forEach(input => {
            if (!input.checkValidity()) {
                allValid = false;
            }
        });
    }
    
    // Validation du code postal
    const codePostal = form.querySelector('#codePostal');
    if (codePostal.value.length !== 5) {
        allValid = false;
    }
    
    // Validation des mots de passe
    const password = form.querySelector('#password');
    const confirm = form.querySelector('#confirmPassword');
    if (password.value !== confirm.value) {
        allValid = false;
    }
    
    if (allValid) {
        form.submit();
    }
});

// Initialiser la première étape et le style des radios
showStep(currentStep);
updateRadioStyles();
showStep(currentStep);