const search_menu = document.getElementById('search-menu');
const equipement_menu = document.getElementById('equipement-menu');

function afficherEquipement(equipement) {
    if (!equipement || equipement === null || equipement === '' || equipement === 0) return;

    // Ouverture de la fiche
    if (!equipement_menu.classList.contains('show-menu')) {

        search_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('d-none');
        equipement_menu.classList.add('d-flex');
        equipement_menu.classList.add('show-menu');
        updateEquipementView(equipement);
    } 
    // Fermeture de la fiche
    else if (equipement_menu.classList.contains('show-menu')) {
        
        equipement_menu.classList.remove('show-menu');
        
        setTimeout(() => {
            equipement_menu.classList.add('show-menu');
            updateEquipementView(equipement);
        }, 100);
    }
}

function updateEquipementView(equipement) {
    const equipement_menu = document.getElementById('equipement-menu');

    // elements
    const equipement_name = document.getElementById('span-equipement-name');
    const equipement_website_container = document.getElementById('equipement-website-container');
    const equipement_website = document.getElementById('span-equipement-website');

    if (equipement_menu.classList.contains('show-menu')) {
        equipement_name.textContent = equipement.name;

        // Les equipements n'ont pas forcement d'url enregistrées
        if (equipement_website && equipement.website !== null) {
            equipement_website_container.classList.remove('d-none');
            equipement_website.href = equipement.website;
            equipement_website.textContent = equipement.website;
        }
        else if (equipement_website) equipement_website_container.classList.add('d-none');
    }
}

async function afficherSuggestions (query, res) {
    let data;
    let error = false;

    try {
        data = await res.json();
    }
    catch (e) {
        if (e instanceof SyntaxError) {
            error = true;
        }
    }

    const suggestion_list = document.getElementById('suggestions-list');
    const suggestions_results = document.getElementById('suggestions-results');
    const suggestions_no_results = document.getElementById('suggestions-no-results');

    if (!error && data.length > 0) {
        suggestion_list.innerHTML = `<p class="m-1 ms-2">Suggestions (${data.length}):<strong></strong></p>`;
        
        data.forEach(suggestion => {
            suggestion_list.innerHTML += `
            <a href="/map?id=${suggestion['id']}" class="py-1 suggestions-items text-decoration-none text-black">
                <div class="d-flex align-items-center position-relative gap-4">
                        <p class="suggestions-items-icon position-relative">📍</p>
                    <div class="d-flex flex-column ms-2">
                        <span><strong>${suggestion['name']}</strong></span>
                        <small>${suggestion['commune'] ?? 'Inconnu'}</small>
                    </div>
                </div>
            </a>
            `;
        });
        if (!suggestions_no_results.classList.contains('d-none')) {
            suggestions_no_results.classList.add('d-none');
        }
        if (suggestions_results.classList.contains('d-none')) {
            suggestions_results.classList.remove('d-none');
        }
    }
    else if (query !== null) {
        suggestion_list.innerHTML = "";
        if (!suggestions_results.classList.contains('d-none')) {
            suggestions_results.classList.add('d-none');
        }
        if (suggestions_no_results.classList.contains('d-none')) {
            suggestions_no_results.classList.remove('d-none');
        }
    }
    else {
        suggestion_list.innerHTML = "";
        suggestions_results.classList.remove('d-none');
        suggestions_no_results.classList.remove('d-none');
    }
}

// search input on top left of the interactive map page
const search_input = document.getElementById('search-input');
let debounceTime;
if (search_input !== null) {
    search_input.addEventListener('input', () => {
        clearTimeout(debounceTime);

        debounceTime = setTimeout(() => {
            fetchFilteredSuggestions(query=search_input.value);
        }, 250);
    });
}

// back button on the top right of the equipement infos menu
const back_button = document.getElementById('back-button');
if (back_button !== null) {
    back_button.addEventListener('click', () => {
        equipement_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('show-menu');

        const url = new URL(window.location.href)
        url.searchParams.delete('id');
        window.history.pushState({ path: url.href }, '', url.href);

        equipement_menu.classList.add('d-none');
        equipement_menu.classList.remove('d-flex');
        
        search_menu.classList.remove('hidden-menu');
        search_menu.classList.remove('d-none');
        search_menu.classList.add('d-flex');
    });
}
