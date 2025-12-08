const advancedFilters = document.getElementById('advanced-filters');
const form = document.querySelector('form');

if (form !== null && advancedFilters !== null) {
    form.addEventListener('submit', function (event) {
        const inputs = form.querySelectorAll('[name]');

        inputs.forEach(input => {
            // supprime de l'url les champs vides notamment les filtres avancés
            if (!input.value.trim() || (!filtersVisible && advancedFilters.contains(input))) {
                input.name = '';
            }
        });

        // Check if all inputs are empty
        const allInputsEmpty = Array.from(inputs).every(input => !input.name);

        // Empeche d'avoir un "?" dans l'URL si aucun filtres ou recherche n'est rempli
        if (allInputsEmpty) {
            event.preventDefault();
            window.location.href = window.location.pathname;
        }
    });
}