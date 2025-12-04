const search_menu = document.getElementById('search-menu');
const search_input = document.getElementById('search-input');
const search_options_btn = document.getElementById('search-options-btn');
const equipement_menu = document.getElementById('equipement-menu');
const back_button = document.getElementById('back-button');

function afficherEquipement(equipement) {
    if (!equipement || equipement === null || equipement === '' || equipement === 0) return;

    // open the equipement menu
    if (!equipement_menu.classList.contains('show-menu')) {

        search_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('d-none');
        equipement_menu.classList.add('d-flex');
        equipement_menu.classList.add('show-menu');
        updateEquipementView(equipement);
        map.flyTo([equipement.lat, equipement.lon], map.getZoom());
    } 
    // close the equipement menu
    else if (equipement_menu.classList.contains('show-menu')) {
        
        equipement_menu.classList.remove('show-menu');
        
        setTimeout(() => {
            equipement_menu.classList.add('show-menu');
            updateEquipementView(equipement);
        }, 100);
    }
}

function updateEquipementView(equipement) {
    const equipement_menu = document.getElementById('equipement-menu'); // equipement menu
    const equipement_name = document.getElementById('span-equipement-name'); // equipement name field
    const equipement_website_container = document.getElementById('equipement-website-container'); // equipement website
    const equipement_itinerary_container = document.getElementById('equipement-itinerary-container');  // equipement itinerary

    console.log(equipement);

    if (equipement_menu.classList.contains('show-menu')) {
        // show the equipement name
        equipement_name.textContent = equipement.name;

        // show the equipement website
        // equipement not always have a website
        if (equipement_website_container && equipement.website !== null) {
            equipement_website_container.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="m8 0 .412.01A7.97 7.97 0 0 1 13.29 2a8.04 8.04 0 0 1 2.548 4.382 8 8 0 1 1-15.674 0 8 8 0 0 1 1.361-3.078A8 8 0 0 1 2.711 2 7.96 7.96 0 0 1 8 0m0 1a7 7 0 0 0-5.958 3.324C2.497 6.192 6.669 7.827 6.5 8c-.5.5-1.034.884-1 1.5.07 1.248 2.259.774 2.5 2 .202 1.032-1.051 3 0 3 1.5-.5 3.798-3.186 4-5 .138-1.242-2-2-3.5-2.5-.828-.276-1.055.648-1.5.5S4.5 5.5 5.5 5s1 0 1.5.5c1 .5.5-1 1-1.5.838-.838 3.16-1.394 3.605-2.001A6.97 6.97 0 0 0 8 1"/></svg>
                <strong><a href="${equipement.website}" target="_blank">${equipement.website ?? 'N/A'}</a></strong>
            `;
            equipement_website_container.classList.remove('d-none');
        }
        else if (equipement_website_container) equipement_website_container.classList.add('d-none');

        // show itinerary and share buttons
        // https://www.google.com/maps/dir/?api=1&destination=${lat}%2C${lon}
        if (equipement.lat && equipement.lon) {
            equipement_itinerary_container.innerHTML = `
            <div class="d-flex flex-row gap-2">
                <a class="btn btn-primary w-100" href="https://www.google.com/maps/dir/?api=1&destination=${equipement.lat}%2C${equipement.lon}" target="_blank">Itinéraire</a>
                <a class="btn btn-light w-100">Partager</a>
            </div>
                `;
        }
    }
}

async function afficherSuggestions(query, data) {

    const suggestion_list = document.getElementById('suggestions-list');
    const suggestions_results = document.getElementById('suggestions-results');
    const suggestions_no_results = document.getElementById('suggestions-no-results');

    if (data.length > 0) {
        suggestion_list.innerHTML = `<p class="m-1 ms-2">Suggestions (${data.length}):<strong></strong></p>`;
        
        data.forEach(item => {
            let id = item['id'] ?? item['place_id'];
            let addresstype = item['addresstype'] !== "postcode" ? item['addresstype'] : 'Ville';
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
                        <span><strong>${item['display_name'] ?? item['name']}</strong></span>
                        <small>${item['commune'] ?? addresstype ?? 'Inconnu'}</small>
                    </div>
                </div>
            `;

            // when clicking on a suggestion
            suggestionItem.addEventListener('click', async function (event) {
                event.preventDefault();

                if (!id.toString().toUpperCase().startsWith("I")) {
                    let polygoneInfos = await fetchPolygoneCityInfos(item);
                    
                    if (polygoneInfos !== null) {
                        drawPolygone(polygoneInfos);
                    }
                } else {
                    polygonsGroup.clearLayers();

                    let url = new URL(window.location.href);
                    url.searchParams.set('id', id);
                    window.history.pushState({ path: url.href }, '', url.href);

                    map.flyTo([lat, lon], map.getZoom());
                }

                if (!suggestions_results.classList.contains('d-none')) {
                    suggestions_results.classList.add('d-none');
                    //search_input.value = "";
                    // TODO
                    // il faudrait afficher le menu flotant avec les infos de la ville cible
                }
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

document.addEventListener('DOMContentLoaded', async () => {
    let url = new URL(window.location.href);
    let paramId = url.searchParams.get("id");
    if (paramId !== null && paramId !== "") {

        //let fakeData = { id: "I766810013", name: "Salle de billard", lat: 49.40957, lon: 1.09221, activites: "Billard (Français (carambole),Snooker,Anglais,Américain)", website: "https://www.billard-club-sottevillais.com/" };
        //let fakeData = { id: "I765910002", name: "Manège", lat: 49.44348, lon: 1.22869, activites: "Dressage, Equitation, Horse - Ball, Saut d'obstacle", website: null };
        
        let equipement = await fetchEquipementById(paramId);
        if (equipement !== null) {
            afficherEquipement(equipement);
        }
    }
});

// search input on top left of the interactive map page
let debounceTime;
if (search_input !== null) {
    search_input.addEventListener('input', () => {
        clearTimeout(debounceTime);

        debounceTime = setTimeout(() => {
            fetchFilteredSuggestions(search_input.value);
        }, 250);
    });
}

search_options_btn.addEventListener('click', () => {
    alert("Paramètres avancés de recherche en cours de développement...");
});

// back button on the top right of the equipement infos menu
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
