// Gestion des réservations d'équipements

let currentEquipementId = null;

/**
 * Initialiser le formulaire de réservation pour un équipement
 */
function initReservationForm(equipementId) {
    currentEquipementId = equipementId;
    const reservationForm = document.getElementById('reservation-form');
    const equipementIdInput = document.getElementById('reservation-equipement-id');
    const dateDebutInput = document.getElementById('reservation-date-debut');
    const dateFinInput = document.getElementById('reservation-date-fin');
    
    if (!reservationForm) return; // L'utilisateur n'est pas connecté
    
    if (!equipementId) {
        console.error('Erreur: equipementId est undefined ou null');
        return;
    }
    
    console.log('Initialisation du formulaire de réservation pour:', equipementId);
    
    // Définir l'ID de l'équipement
    if (equipementIdInput) {
        equipementIdInput.value = equipementId;
    }
    
    // Définir la date minimale à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    if (dateDebutInput) dateDebutInput.min = today;
    if (dateFinInput) dateFinInput.min = today;
    
    // Vérifier si l'équipement est actuellement réservé
    checkCurrentReservationStatus(equipementId);
    
    // Charger les réservations de l'utilisateur pour cet équipement
    loadUserReservations(equipementId);
}

/**
 * Vérifier si l'équipement est actuellement réservé
 */
async function checkCurrentReservationStatus(equipementId) {
    try {
        const response = await fetch(`/api/reservations/current?installation_numero=${equipementId}`);
        const data = await response.json();
        
        const statusDiv = document.getElementById('reservation-status');
        const equipementName = document.getElementById('span-equipement-name');
        
        if (statusDiv) {
            if (data.is_reserved) {
                statusDiv.innerHTML = `
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Cet équipement est actuellement réservé</strong>
                    </div>
                `;
                
                // Ajouter un badge sur le nom de l'équipement
                if (equipementName && !equipementName.querySelector('.badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-warning text-dark ms-2';
                    badge.innerHTML = '<i class="bi bi-lock-fill me-1"></i>Réservé';
                    equipementName.appendChild(badge);
                }
            } else {
                statusDiv.innerHTML = `
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Cet équipement est disponible</strong>
                    </div>
                `;
                
                // Retirer le badge si présent
                if (equipementName) {
                    const existingBadge = equipementName.querySelector('.badge');
                    if (existingBadge) existingBadge.remove();
                }
            }
        }
    } catch (error) {
        console.error('Erreur lors de la vérification du statut:', error);
    }
}

/**
 * Charger les réservations de l'utilisateur
 */
async function loadUserReservations(equipementId) {
    try {
        const response = await fetch(`/api/reservations/equipement?installation_numero=${equipementId}`);
        const data = await response.json();
        
        const reservationsContainer = document.getElementById('reservations-container');
        const reservationsList = document.getElementById('user-reservations-list');
        
        if (data.success && data.reservations && data.reservations.length > 0) {
            if (reservationsList) reservationsList.style.display = 'block';
            
            if (reservationsContainer) {
                reservationsContainer.innerHTML = data.reservations.map(reservation => `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-range me-1"></i>
                                        Du ${formatDate(reservation.date_debut)} au ${formatDate(reservation.date_fin)}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>
                                        ${reservation.user_prenom} ${reservation.user_nom}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } else {
            if (reservationsList) reservationsList.style.display = 'none';
        }
    } catch (error) {
        console.error('Erreur lors du chargement des réservations:', error);
    }
}

/**
 * Vérifier la disponibilité pour les dates sélectionnées
 */
async function checkAvailability(equipementId, dateDebut, dateFin) {
    try {
        const response = await fetch(
            `/api/reservations/check?installation_numero=${equipementId}&date_debut=${dateDebut}&date_fin=${dateFin}`
        );
        const data = await response.json();
        
        const messageDiv = document.getElementById('availability-message');
        if (messageDiv) {
            if (data.available) {
                messageDiv.className = 'alert alert-success mb-3';
                messageDiv.innerHTML = '<i class="bi bi-check-circle me-2"></i>Période disponible !';
            } else {
                messageDiv.className = 'alert alert-danger mb-3';
                messageDiv.innerHTML = '<i class="bi bi-x-circle me-2"></i>Période déjà réservée !';
            }
            messageDiv.classList.remove('d-none');
        }
        
        return data.available;
    } catch (error) {
        console.error('Erreur lors de la vérification de disponibilité:', error);
        return false;
    }
}

/**
 * Formater une date au format français
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

/**
 * Gérer la soumission du formulaire de réservation
 */
if (document.getElementById('reservation-form')) {
    document.getElementById('reservation-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitButton = document.getElementById('submit-reservation');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Réservation en cours...';
        
        const formData = {
            installation_numero: document.getElementById('reservation-equipement-id').value,
            date_debut: document.getElementById('reservation-date-debut').value,
            date_fin: document.getElementById('reservation-date-fin').value
        };
        
        try {
            const response = await fetch('/api/reservations/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            const messageDiv = document.getElementById('availability-message');
            
            if (data.success) {
                messageDiv.className = 'alert alert-success mb-3';
                messageDiv.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + data.message;
                messageDiv.classList.remove('d-none');
                
                // Réinitialiser le formulaire
                document.getElementById('reservation-form').reset();
                
                // Recharger les informations
                checkCurrentReservationStatus(formData.installation_numero);
                loadUserReservations(formData.installation_numero);
                
                // Désactiver le formulaire si l'équipement devient réservé
                setTimeout(() => {
                    messageDiv.classList.add('d-none');
                }, 5000);
            } else {
                messageDiv.className = 'alert alert-danger mb-3';
                messageDiv.innerHTML = '<i class="bi bi-x-circle me-2"></i>' + data.message;
                messageDiv.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Erreur lors de la réservation:', error);
            const messageDiv = document.getElementById('availability-message');
            messageDiv.className = 'alert alert-danger mb-3';
            messageDiv.innerHTML = '<i class="bi bi-x-circle me-2"></i>Erreur lors de la réservation';
            messageDiv.classList.remove('d-none');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
    
    // Vérifier la disponibilité quand les dates changent
    const dateDebutInput = document.getElementById('reservation-date-debut');
    const dateFinInput = document.getElementById('reservation-date-fin');
    
    if (dateDebutInput && dateFinInput) {
        dateFinInput.addEventListener('change', () => {
            const dateDebut = dateDebutInput.value;
            const dateFin = dateFinInput.value;
            const equipementId = document.getElementById('reservation-equipement-id').value;
            
            if (dateDebut && dateFin && equipementId) {
                checkAvailability(equipementId, dateDebut, dateFin);
            }
        });
        
        dateDebutInput.addEventListener('change', () => {
            // Mettre à jour la date minimale de fin
            dateFinInput.min = dateDebutInput.value;
            
            const dateDebut = dateDebutInput.value;
            const dateFin = dateFinInput.value;
            const equipementId = document.getElementById('reservation-equipement-id').value;
            
            if (dateDebut && dateFin && equipementId) {
                checkAvailability(equipementId, dateDebut, dateFin);
            }
        });
    }
}
