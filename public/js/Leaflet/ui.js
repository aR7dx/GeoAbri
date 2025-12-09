const search_menu = document.getElementById('search-menu');
const search_input = document.getElementById('search-input');
const search_options_btn = document.getElementById('search-options-btn');
const advanced_filters = document.getElementById('advanced-filters');
const range_input = document.getElementById('range-input');
const equipement_menu = document.getElementById('equipement-menu');
const back_button = document.getElementById('back-button');

let debounceTime;
let filtersVisible = false;

function afficherEquipement(equipement) {
    if (!equipement || equipement === null || equipement === '' || equipement === 0) return;

    // open the equipement menu
    if (!equipement_menu.classList.contains('show-menu')) {

        search_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('d-none');

        map.flyTo([equipement.lat, equipement.lon], map.getZoom());

        equipement_menu.classList.add('d-flex');
        equipement_menu.classList.add('show-menu');
        updateEquipementView(equipement);
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

async function updateEquipementView(equipement) {
    const equipement_menu = document.getElementById('equipement-menu'); // equipment menu
    const equipement_name = document.getElementById('span-equipement-name'); // equipment name field
    const equipement_display_img = document.getElementById('equipement-display-img');
    const equipement_website_container = document.getElementById('equipement-website-container'); // equipment website
    const equipement_itinerary_container = document.getElementById('equipement-itinerary-container');  // equipment itinerary
    const equipement_description = document.getElementById('equipement-description'); // equipment description
    const equipement_email = document.getElementById('equipement-email'); // equipment email

    if (equipement_menu.classList.contains('show-menu')) {
        // show the equipment name
        equipement_name.textContent = equipement.name;

        // Lazy loading de l'image pour ne pas bloquer l'affichage
        equipement_display_img.innerHTML = `<span>Chargement de l'image...</span>`;
        fetchEquipementDisplayImage(equipement.name, equipement.commune).then(imageUrl => {

            if (imageUrl !== null) {
                equipement_display_img.innerHTML = `
                <img src="${imageUrl}" style="height: 187px; width: 100%;"></img>
                `;
            }
            else equipement_display_img.innerHTML = `<span>Pas d'image disponible.</span>`;
        }).catch(() => {
            equipement_display_img.innerHTML = `<span>Pas d'image disponible.</span>`;
        });

        // show the equipment website
        // equipement not always have a website
        if (equipement_website_container && equipement.website !== null) {
            equipement.website = equipement.website.includes("http") ? equipement.website : 'https://' + equipement.website;

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

        // show the description of the equipment
        if (equipement_description && equipement.description && equipement.description !== null) {
            equipement_description.innerHTML = `
            <span>${equipement.description}</span>
            <hr>
            `;
        }

        // show the email of the equipment owner
        if (equipement_email) {
            equipement_email.innerHTML = `
            <div class="d-flex flex-row align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>
                <span><strong>${equipement.email ?? equipement.id.concat("@gmail.com")}</strong></span>
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
                    } else {
                        map.flyTo([lat, lon], 11);
                    }
                } else {
                    polygonsGroup.clearLayers();

                    let url = new URL(window.location.href);
                    url.searchParams.set('id', id);
                    window.history.pushState({ path: url.href }, '', url.href);

                    afficherEquipement(item);
                }

                if (!suggestions_results.classList.contains('d-none')) {
                    suggestions_results.classList.add('d-none');
                    // TODO
                    // il faudrait afficher le menu flotant avec les infos de la ville cible
                }
            });

            if (suggestion_list.classList.contains('d-none')){
                suggestion_list.classList.remove('d-none');
            }
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

function masquerSuggestions() {
    const suggestion_list = document.getElementById('suggestions-list');
    
    if (suggestion_list) {
        if (!suggestion_list.classList.contains('d-none')) {
            suggestion_list.classList.add('d-none');
        }
    }
    return;
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

        let equipement = await fetchEquipementById(paramId);
        if (equipement !== null) {
            afficherEquipement(equipement);
        }
    }
});

// search input on top left of the interactive map page
if (search_input !== null) {
    search_input.addEventListener('input', () => {
        clearTimeout(debounceTime);

        debounceTime = setTimeout(() => {
            fetchFilteredSuggestions(search_input.value);
        }, 250);
    });
}

// advanced filters toggle button on the top left of the interactive map
if (search_options_btn !== null && advanced_filters !== null) {
    search_options_btn.addEventListener('click', () => {
        filtersVisible = !filtersVisible;
        advanced_filters.style.display = filtersVisible ? 'block' : 'none';
    });
}

if (range_input) {
    const rangeOutput = document.getElementById('range-input-label');

    range_input.addEventListener('input', function () {
        rangeOutput.textContent = (this.value * 2).toString() + "km";
    });
}

// back button on the top right of the equipement infos menu
if (back_button !== null) {
    back_button.addEventListener('click', () => {
        equipement_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('show-menu');

        const url = new URL(window.location.href)
        url.searchParams.delete('id');
        window.history.pushState({ path: url.href }, '', url.href);

        fetchFilteredEquipements();

        equipement_menu.classList.add('d-none');
        equipement_menu.classList.remove('d-flex');
        
        search_menu.classList.remove('hidden-menu');
        search_menu.classList.remove('d-none');
        search_menu.classList.add('d-flex');
    });
}
