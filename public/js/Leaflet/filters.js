/**
 * Gestion des filtres avancés de la carte interactive
 */

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

// Charger les options au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    loadFilterOptions();
});
