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
    console.log(equipement);
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
        if (equipement_description) {
            equipement_description.innerHTML = equipement.observations && equipement.observations !== null 
                ? `<p class="mb-0">${equipement.observations}</p>`
                : `<p class="mb-0 text-muted fst-italic">Aucune description disponible</p>`;
        }

        // show location
        const equipement_location = document.getElementById('equipement-location');
        if (equipement_location) {
            equipement_location.innerHTML = `
                <p class="mb-1 text-black"><i class="bi bi-geo-alt me-2"></i><strong>Coordonnées:</strong> ${equipement.lat}, ${equipement.lon}</p>
                <p class="mb-1 text-black"><i class="bi bi-building me-2"></i><strong>Commune:</strong> ${equipement.commune || 'N/A'}</p>
            `;
        }

        // show the email of the equipment owner
        if (equipement_email) {
            equipement_email.innerHTML = `
                <div class="d-flex flex-row align-items-center gap-2">
                    <i class="bi bi-envelope"></i>
                    <span><strong>${equipement.email ?? equipement.id + '@gmail.com' ?? 'Non renseigné'}</strong></span>
                </div>
            `;
        }

        // Jauge de capacité (demi-cercle)
        const equipement_capacity = document.getElementById('equipement-capacity');
        if (equipement_capacity && (equipement.capacite_actuelle || equipement.capacite_maximale)) {
            const current = equipement.capacite_actuelle || 0;
            const max = equipement.capacite_maximale || 5000;
            const ratio = max > 0 ? Math.min(current / max, 1) : 0;
            const angle = ratio * Math.PI;
            const x = 100 - 80 * Math.cos(angle);
            const y = 90 - 80 * Math.sin(angle);
            const largeArc = ratio > 0.5 ? 1 : 0;
            
            equipement_capacity.innerHTML = `
                <div class="position-relative" style="width: 200px; height: 100px;">
                    <svg width="200" height="100" viewBox="0 0 200 100">
                        <!-- Arc de fond (gris) -->
                        <path d="M 20 90 A 80 80 0 0 1 180 90" fill="none" stroke="#e9ecef" stroke-width="15" stroke-linecap="round"/>
                        <!-- Arc de progression (vert) -->
                        <path d="M 20 90 A 80 80 0 ${largeArc} 1 ${x} ${y}" 
                              fill="none" stroke="#198754" stroke-width="15" stroke-linecap="round"/>
                        <!-- Valeurs aux extrémités -->
                        <text x="10" y="100" font-size="12" fill="#6c757d">0</text>
                        <text x="175" y="100" font-size="12" fill="#6c757d">${max}</text>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center" style="margin-top: 10px;">
                        <div class="fs-3 fw-bold text-success">${current}</div>
                        <small class="text-muted">personnes</small>
                    </div>
                </div>
            `;
        } else if (equipement_capacity) {
            equipement_capacity.innerHTML = `<p class="text-muted fst-italic">Capacité non renseignée</p>`;
        }

        // Caractéristiques techniques
        const equipement_technical = document.getElementById('equipement-technical');
        if (equipement_technical) {
            let technicalHTML = '';
            
            if (equipement.nature) {
                technicalHTML += `
                    <div class="col-md-6">
                        <div class="card bg-info-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-cloud-sun me-1"></i><strong>Nature:</strong> ${equipement.nature}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.aire_nature_sol) {
                technicalHTML += `
                    <div class="col-md-6">
                        <div class="card bg-info-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-grid-3x3-gap me-1"></i><strong>Sol:</strong> ${equipement.aire_nature_sol}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.chauffage_energie) {
                technicalHTML += `
                    <div class="col-md-6">
                        <div class="card bg-info-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-fire me-1"></i><strong>Chauffage:</strong> ${equipement.chauffage_energie}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            equipement_technical.innerHTML = technicalHTML || `<p class="text-muted fst-italic col-12">Aucune caractéristique renseignée</p>`;
        }

        // Commodités
        const equipement_amenities = document.getElementById('equipement-amenities');
        if (equipement_amenities) {
            let amenitiesHTML = '';
            
            if (equipement.vestiaires_sportifs_nb) {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-door-closed me-1"></i><strong>Vestiaires sportifs:</strong> ${equipement.vestiaires_sportifs_nb}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.vestiaires_arbitres_nb) {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-door-open me-1"></i><strong>Vestiaires arbitres:</strong> ${equipement.vestiaires_arbitres_nb}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.places_tibune_nb) {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-people me-1"></i><strong>Places en tribune:</strong> ${equipement.places_tibune_nb}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.aire_eclairage === 'Oui') {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-lightbulb me-1"></i><strong>Éclairage</strong></small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.douches === 'Oui') {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-droplet me-1"></i><strong>Douches</strong></small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.sanitaires === 'Oui') {
                amenitiesHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-clipboard-check me-1"></i><strong>Sanitaires</strong></small>
                            </div>
                        </div>
                    </div>`;
            }
            
            equipement_amenities.innerHTML = amenitiesHTML || `<p class="text-muted fst-italic col-12">Aucune commodité renseignée</p>`;
        }

        // Accessibilité
        const equipement_accessibility = document.getElementById('equipement-accessibility');
        if (equipement_accessibility) {
            let accessibilityHTML = '';
            
            if (equipement.acces_handi_mobilite && equipement.acces_handi_mobilite !== 'Aucun') {
                accessibilityHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-wheelchair me-1"></i><strong>PMR:</strong> ${equipement.acces_handi_mobilite}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.acces_handi_sensoriel && equipement.acces_handi_sensoriel !== 'Aucun') {
                accessibilityHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-ear me-1"></i><strong>Sensoriel:</strong> ${equipement.acces_handi_sensoriel}</small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.acces_libre === 'Oui') {
                accessibilityHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-unlock me-1"></i><strong>Accès libre</strong></small>
                            </div>
                        </div>
                    </div>`;
            }
            
            if (equipement.ouverture_saisonniere === 'Oui') {
                accessibilityHTML += `
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-calendar-range me-1"></i><strong>Ouverture saisonnière</strong></small>
                            </div>
                        </div>
                    </div>`;
            }
            
            equipement_accessibility.innerHTML = accessibilityHTML || `<p class="text-muted fst-italic col-12">Aucune information d'accessibilité</p>`;
        }

        // Dimensions
        const equipement_dimensions = document.getElementById('equipement-dimensions');
        const equipement_dimensions_section = document.getElementById('equipement-dimensions-section');
        if (equipement_dimensions) {
            let dimensionsHTML = '';
            let hasDimensions = false;
            
            if (equipement.aire_longueur) {
                dimensionsHTML += `
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-arrow-left-right me-1"></i><strong>Longueur:</strong> ${equipement.aire_longueur} m</small>
                            </div>
                        </div>
                    </div>`;
                hasDimensions = true;
            }
            
            if (equipement.aire_largeur) {
                dimensionsHTML += `
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-arrow-down-up me-1"></i><strong>Largeur:</strong> ${equipement.aire_largeur} m</small>
                            </div>
                        </div>
                    </div>`;
                hasDimensions = true;
            }
            
            if (equipement.aire_hauteur) {
                dimensionsHTML += `
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-arrow-bar-up me-1"></i><strong>Hauteur:</strong> ${equipement.aire_hauteur} m</small>
                            </div>
                        </div>
                    </div>`;
                hasDimensions = true;
            }
            
            if (equipement.aire_surface) {
                dimensionsHTML += `
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-bounding-box me-1"></i><strong>Surface:</strong> ${equipement.aire_surface} m²</small>
                            </div>
                        </div>
                    </div>`;
                hasDimensions = true;
            }
            
            if (hasDimensions) {
                equipement_dimensions.innerHTML = dimensionsHTML;
                equipement_dimensions_section.style.display = 'block';
            } else {
                equipement_dimensions_section.style.display = 'none';
            }
        }
    }
}

async function afficherSuggestions(query, data) {

    const suggestion_list = document.getElementById('suggestions-list');
    const suggestions_results = document.getElementById('suggestions-results');
    const search_no_results = document.getElementById('search-no-results');

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

        if (!search_no_results.classList.contains('d-none')) {
            search_no_results.classList.add('d-none');
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
        if (search_no_results.classList.contains('d-none')) {
            search_no_results.classList.remove('d-none');
        }
    }
    else {
        suggestion_list.innerHTML = "";
        suggestions_results.classList.remove('d-none');
        search_no_results.classList.remove('d-none');
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
            // Ne pas afficher les suggestions si les filtres avancés sont visibles
            if (!filtersVisible) {
                fetchFilteredSuggestions(search_input.value);
            }
        }, 250);
    });
}

// advanced filters toggle button on the top left of the interactive map
if (search_options_btn !== null && advanced_filters !== null) {
    search_options_btn.addEventListener('click', () => {
        filtersVisible = !filtersVisible;
        advanced_filters.style.display = filtersVisible ? 'block' : 'none';
        
        // Masquer les suggestions quand on ouvre les filtres avancés
        if (filtersVisible) {
            masquerSuggestions();
        }
    });
}

if (range_input) {
    const rangeOutput = document.getElementById('range-input-label');

    range_input.addEventListener('input', function () {
        rangeOutput.textContent = (this.value * 2).toString() + "km";
        
        // Mettre à jour le cercle en temps réel
        const radiusKm = parseInt(this.value) * 2;
        
        if (parseInt(this.value) <= 100 && typeof getSearchCenter !== 'undefined') {
            getSearchCenter().then(center => {
                if (center && typeof drawRangeCircle !== 'undefined') {
                    drawRangeCircle(center.lat, center.lon, radiusKm);
                }
            });
        } else if (typeof rangeCircle !== 'undefined' && rangeCircle && typeof map !== 'undefined') {
            // Supprimer le cercle si range > 100
            map.removeLayer(rangeCircle);
            rangeCircle = null;
        }
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
