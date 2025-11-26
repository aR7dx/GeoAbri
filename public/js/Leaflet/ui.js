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

async function afficherSuggestions (query, data) {

    const suggestion_list = document.getElementById('suggestions-list');
    const suggestions_results = document.getElementById('suggestions-results');
    const suggestions_no_results = document.getElementById('suggestions-no-results');

    if (data.length > 0) {
        suggestion_list.innerHTML = `<p class="m-1 ms-2">Suggestions (${data.length}):<strong></strong></p>`;
        
        data.forEach(item => {
            let id = item['id'] ?? item['place_id'];
            let lat = item['lat'];
            let lon = item['lon'];
            let icon = findWhichIcon(item, id);

            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add("py-1", "suggestions-items", "text-decoration-none", "text-black");
            suggestionItem.style.cursor = "pointer";

            suggestionItem.innerHTML += `
                <div class="d-flex align-items-center position-relative gap-4">
                    <p class="suggestions-items-icon position-relative bg-light rounded p-2">${icon}</p>
                    <div class="d-flex flex-column ms-2">
                        <span><strong>${item['name'] ?? item['display_name']}</strong></span>
                        <small>${item['commune'] ?? item['addresstype'] ?? 'Inconnu'}</small>
                    </div>
                </div>
            `;

            // code quand on clique sur une suggestion
            suggestionItem.addEventListener('click', function (event) {
                event.preventDefault();

                // TODO 
                // peut etre ajouter l'id dans l'url pour pouvoir partager le lieu avec une url
                // ou simplement pour lors du rechargement de la page reafficher le dernier lieux
                map.setView([lat, lon], 13);
            });

            suggestion_list.appendChild(suggestionItem);
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

function findWhichIcon(item, id) {
    let icons = {
        "city": "🌆",
        "village": "🏡",
        "equipement": "🏛️",
        "salle_omnisports": "🏆",
        "salle_de_billard": "🎱",
        "location": "📍"
    };
    // TODO
    // recuperer plus de parametre des equipement pour determiner plus précisement le type de lieu
    // et avoir une icon plus précise

    if (item['name'].toLowerCase().includes("salle de billard")) return icons['salle_de_billard']
    else if (item['name'].toLowerCase().includes("salle omnisports")) return icons['salle_omnisports']
    else if (id.toString().toUpperCase().startsWith("I")) return icons['equipement'];
    else if (item['addresstype'] === "city") return icons['city'];
    else if (item['addresstype'] === "village") return icons['village'];
    return icons['location'];
}

// search input on top left of the interactive map page
const search_input = document.getElementById('search-input');
let debounceTime;
if (search_input !== null) {
    search_input.addEventListener('input', () => {
        clearTimeout(debounceTime);

        debounceTime = setTimeout(() => {
            fetchFilteredSuggestions(search_input.value);
        }, 250);
    });
}

const search_options_btn = document.getElementById('search-options-btn');
search_options_btn.addEventListener('click', () => {
    alert("Paramètres avancés de recherche en cours de développement...");
});

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
