// Gestion des alertes d'urgence sur la carte

let currentAlert = null;
let alertCheckInterval = null;
let cachedEquipements = [];

/**
 * Initialiser la vérification des alertes
 */
function initAlertSystem() {
    // Vérifier les alertes toutes les 30 secondes
    checkAlertsInView();
    alertCheckInterval = setInterval(checkAlertsInView, 30000);
    
    // Vérifier aussi quand la carte bouge
    map.on('moveend', checkAlertsInView);
    
    // Gestionnaires d'événements pour les boutons
    const goToButton = document.getElementById('alert-go-to-equipement');
    const dismissButton = document.getElementById('alert-dismiss');
    const closeButton = document.getElementById('alert-close');
    
    if (goToButton) {
        goToButton.addEventListener('click', goToNearestEquipement);
    }
    
    if (dismissButton) {
        dismissButton.addEventListener('click', dismissAlert);
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', dismissAlert);
    }
}

/**
 * Vérifier s'il y a des alertes dans la zone visible de la carte
 */
async function checkAlertsInView() {
    try {
        const bounds = map.getBounds();
        const ne = bounds.getNorthEast();
        const sw = bounds.getSouthWest();
        
        const response = await fetch(
            `/api/alerts/active?lat_min=${sw.lat}&lat_max=${ne.lat}&lon_min=${sw.lng}&lon_max=${ne.lng}`
        );
        
        if (!response.ok) return;
        
        const data = await response.json();
        
        if (data.success && data.alerts && data.alerts.length > 0) {
            // Prendre l'alerte la plus importante (niveau le plus élevé)
            const alert = data.alerts[0];
            showAlert(alert);
            enterEmergencyMode();
        } else {
            // Pas d'alerte, quitter le mode urgence
            exitEmergencyMode();
        }
    } catch (error) {
        console.error('Erreur lors de la vérification des alertes:', error);
    }
}

/**
 * Afficher l'alerte dans le composant
 */
function showAlert(alert) {
    currentAlert = alert;
    
    const alertNotification = document.getElementById('alert-notification');
    const alertTitle = document.getElementById('alert-title');
    const alertDescription = document.getElementById('alert-description');
    
    if (alertTitle) {
        alertTitle.textContent = alert.nom || 'Alerte en cours';
    }
    
    if (alertDescription) {
        alertDescription.textContent = alert.description || 'Une alerte est active dans votre zone';
    }
    
    if (alertNotification) {
        alertNotification.classList.remove('d-none');
    }
}

/**
 * Masquer l'alerte
 */
function dismissAlert() {
    const alertNotification = document.getElementById('alert-notification');
    if (alertNotification) {
        alertNotification.classList.add('d-none');
    }
    currentAlert = null;
}

/**
 * Activer le mode urgence (effet visuel rouge)
 */
function enterEmergencyMode() {
    const mapElement = document.getElementById('map');
    if (mapElement && !mapElement.classList.contains('emergency-mode')) {
        mapElement.classList.add('emergency-mode');
    }
}

/**
 * Quitter le mode urgence
 */
function exitEmergencyMode() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.classList.remove('emergency-mode');
    }
    dismissAlert();
}

/**
 * Calculer la distance entre deux points (formule de Haversine)
 */
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Rayon de la Terre en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

/**
 * Trouver l'équipement le plus proche de l'alerte
 */
function findNearestEquipement(alertLat, alertLon) {
    if (!cachedEquipements || cachedEquipements.length === 0) {
        console.error('Aucun équipement en cache');
        return null;
    }
    
    let nearest = null;
    let minDistance = Infinity;
    
    for (const equipement of cachedEquipements) {
        if (equipement.lat && equipement.lon) {
            const distance = calculateDistance(
                alertLat,
                alertLon,
                parseFloat(equipement.lat),
                parseFloat(equipement.lon)
            );
            
            if (distance < minDistance) {
                minDistance = distance;
                nearest = equipement;
            }
        }
    }
    
    return nearest;
}

/**
 * Naviguer vers l'équipement le plus proche et ouvrir son menu
 */
async function goToNearestEquipement() {
    if (!currentAlert) {
        console.error('Aucune alerte active');
        return;
    }
    
    const alertLat = parseFloat(currentAlert.lat);
    const alertLon = parseFloat(currentAlert.lon);
    
    // Trouver l'équipement le plus proche
    const nearest = findNearestEquipement(alertLat, alertLon);
    
    if (!nearest) {
        alert('Aucun équipement trouvé à proximité');
        return;
    }
    
    // Récupérer les données complètes de l'équipement
    const equipementData = await fetchEquipementById(nearest.id);
    
    if (equipementData) {
        // Centrer la carte sur l'équipement
        map.flyTo([equipementData.lat, equipementData.lon], 15);
        
        // Mettre à jour l'URL
        let url = new URL(window.location.href);
        url.searchParams.set('id', equipementData.id);
        window.history.pushState({ path: url.href }, '', url.href);
        
        // Afficher le menu de l'équipement
        afficherEquipement(equipementData);
        
        // Masquer l'alerte
        dismissAlert();
    } else {
        alert('Erreur lors de la récupération des données de l\'équipement');
    }
}

/**
 * Mettre à jour le cache des équipements
 */
function updateEquipementsCache(equipements) {
    cachedEquipements = equipements;
}

// Initialiser le système d'alertes quand la page est chargée
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAlertSystem);
} else {
    initAlertSystem();
}

// Exporter les fonctions pour utilisation externe
window.updateEquipementsCache = updateEquipementsCache;
