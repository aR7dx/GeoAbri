let filterOptions = null;

/**
 * Charge les options de filtres depuis l'API
 */
async function loadFilterOptions() {
    try {
        const res = await fetch('/api/map/filters');
        if (!res.ok) {
            console.error('Erreur lors du chargement des options de filtres');
            return;
        }

        filterOptions = await res.json();
        populateCategorySelect();
        populateActivitesSelect();
    } catch (err) {
        console.error('Erreur:', err);
    }
}

/**
 * Remplit le select des catégories
 */
function populateCategorySelect() {
    const categorySelect = document.getElementById('category-select');
    if (!categorySelect || !filterOptions || !filterOptions.categories) return;

    const currentValue = new URLSearchParams(window.location.search).get('category') || '';

    categorySelect.innerHTML = '<option value="">Toutes les catégories</option>';

    filterOptions.categories.forEach(item => {
        const option = document.createElement('option');
        option.value = item.category;
        option.textContent = `${item.category} (${item.count})`;
        
        if (currentValue === item.category) {
            option.selected = true;
        }
        
        categorySelect.appendChild(option);
    });
}

/**
 * Remplit le select des activités
 */
function populateActivitesSelect() {
    const activitesSelect = document.getElementById('activites-select');
    if (!activitesSelect || !filterOptions || !filterOptions.activites) return;

    const currentValue = new URLSearchParams(window.location.search).get('activites') || '';

    activitesSelect.innerHTML = '<option value="">Toutes les activités</option>';

    filterOptions.activites.forEach(item => {
        const option = document.createElement('option');
        option.value = item.activite;
        option.textContent = `${item.activite} (${item.count})`;
        
        if (currentValue === item.activite) {
            option.selected = true;
        }
        
        activitesSelect.appendChild(option);
    });
}

/**
 * Récupère les coordonnées d'une commune via Nominatim
 */
async function fetchCommuneCoordinates(commune) {
    if (!commune || commune.trim() === '') return null;
    
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(commune + ', France')}&limit=1`;
        const res = await fetch(url);
        
        if (!res.ok) return null;
        
        const data = await res.json();
        
        if (data && data.length > 0) {
            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon),
                displayName: data[0].display_name
            };
        }
        
        return null;
    } catch (err) {
        console.error('Erreur lors de la récupération des coordonnées de la commune:', err);
        return null;
    }
}

/**
 * Centre la carte sur la commune si elle est spécifiée dans l'URL
 */
async function centerMapOnCommune() {
    const urlParams = new URLSearchParams(window.location.search);
    const commune = urlParams.get('commune');
    
    if (!commune || commune.trim() === '') return;
    
    const coordinates = await fetchCommuneCoordinates(commune);
    
    if (coordinates && typeof map !== 'undefined') {
        // Voler vers la commune avec un zoom approprié
        map.flyTo([coordinates.lat, coordinates.lon], 12, {
            duration: 1.5
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadFilterOptions();
    
    setTimeout(() => {
        centerMapOnCommune();
    }, 500);
});
