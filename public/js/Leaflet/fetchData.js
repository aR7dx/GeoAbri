/**
 * Cache pour éviter les requêtes redondantes
 */
const requestCache = new Map();
let currentFetchController = null;

/**
 * url to fetch with the api
 */
function filteredUrl(basedUrl, bounds, customParams={}) {
    const params = new URLSearchParams(new URL(window.location).search);

    Object.keys(customParams).forEach(key => {
        params.set(key, customParams[key]);
    });

    params.set('minLat', bounds.getSouth().toFixed(4));
    params.set('maxLat', bounds.getNorth().toFixed(4));
    params.set('minLon', bounds.getWest().toFixed(4));
    params.set('maxLon', bounds.getEast().toFixed(4));

    return basedUrl + '?' + params.toString();
}

/**
 * Afficher/masquer l'overlay de chargement
 */
function toggleLoadingOverlay(show) {
    let overlay = document.getElementById('map-loading-overlay');
    
    if (!overlay) {
        // Créer l'overlay s'il n'existe pas
        overlay = document.createElement('div');
        overlay.id = 'map-loading-overlay';
        overlay.className = 'position-absolute top-0 start-0 w-100 h-100 d-none';
        overlay.style.cssText = 'background: rgba(255, 255, 255, 0.5); z-index: 9999; backdrop-filter: blur(2px);';
        overlay.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3 fw-semibold text-dark">Chargement des équipements...</p>
            </div>
        `;
        document.getElementById('map').appendChild(overlay);
    }
    
    if (show) {
        overlay.classList.remove('d-none');
        map.dragging.disable();
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
        map.boxZoom.disable();
        map.keyboard.disable();
    } else {
        overlay.classList.add('d-none');
        map.dragging.enable();
        map.touchZoom.enable();
        map.doubleClickZoom.enable();
        map.scrollWheelZoom.enable();
        map.boxZoom.enable();
        map.keyboard.enable();
    }
}

/**
 * fonction de recuperation des resultats de lieux avec une requete sql filtrées et affichage des resultat sur la carte
 *  */
async function fetchFilteredEquipements() {
    let fetchUrl = filteredUrl('/api/map/equipements', map.getBounds());

    // Vérifier le cache
    if (requestCache.has(fetchUrl)) {
        const cachedData = requestCache.get(fetchUrl);
        updateMarkers(cachedData);
        return;
    }

    // Afficher l'overlay de chargement
    toggleLoadingOverlay(true);

    // Annuler la requête précédente si elle existe
    if (currentFetchController) {
        currentFetchController.abort();
    }
    currentFetchController = new AbortController();

    try {
        const res = await fetch(fetchUrl, { signal: currentFetchController.signal });
        if (!res.ok) {
            return;
        }

        const data = await res.json();
        
        // Mettre en cache (limiter à 10 entrées)
        if (requestCache.size > 10) {
            const firstKey = requestCache.keys().next().value;
            requestCache.delete(firstKey);
        }
        requestCache.set(fetchUrl, data);

        updateMarkers(data);
    } catch (err) {
        if (err.name === 'AbortError') {
            toggleLoadingOverlay(false);
            return;
        }
    } finally {
        toggleLoadingOverlay(false);
    }
}

/**
 * Vérifie si un équipement est dans le rayon de recherche
 */
function isWithinRange(equipLat, equipLon, centerLat, centerLon, radiusKm) {
    // Formule de Haversine pour calculer la distance entre deux points GPS
    const R = 6371; // Rayon de la Terre en km
    const dLat = (equipLat - centerLat) * Math.PI / 180;
    const dLon = (equipLon - centerLon) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(centerLat * Math.PI / 180) * Math.cos(equipLat * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c;
    
    return distance <= radiusKm;
}

/**
 * Mise à jour des markers avec chunking pour éviter les freezes
 */
async function updateMarkers(data) {
    clusterGroup.clearLayers();
    
    // Vérification de la structure des données
    if (!data || typeof data !== 'object') return;
    
    let equipements = data['equipements'] || data.equipements || [];
    
    if (!Array.isArray(equipements)) return;
    
    // Filtrer par rayon si spécifié
    const urlParams = new URLSearchParams(window.location.search);
    const range = urlParams.get('range');
    
    if (range && range !== '' && parseInt(range) <= 100) {
        const radiusKm = parseInt(range) * 2; // Conversion: valeur * 2 = km
        const center = await getSearchCenter();
        
        if (center) {
            equipements = equipements.filter(equipement => {
                if (!equipement || !equipement.lat || !equipement.lon) return false;
                return isWithinRange(
                    parseFloat(equipement.lat),
                    parseFloat(equipement.lon),
                    center.lat,
                    center.lon,
                    radiusKm
                );
            });
        }
    }
    
    if (equipements.length === 0) return;
    
    const chunkSize = 200;
    let index = 0;

    function addChunk() {
        const chunk = equipements.slice(index, index + chunkSize);
        
        const markers = chunk.map(equipement => {
            if (!equipement || !equipement.lat || !equipement.lon) return null;
            
            return L.marker([parseFloat(equipement.lat), parseFloat(equipement.lon)], { icon: redIcon }).on('click', async () => {
                let url = new URL(window.location.href);
                url.searchParams.set('id', equipement.id);
                window.history.pushState({ path: url.href }, '', url.href);

                let completeData = await fetchEquipementById(equipement.id);
                equipement = completeData !== null ? completeData : equipement;

                afficherEquipement(equipement);
            });
        }).filter(marker => marker !== null);

        clusterGroup.addLayers(markers);
        
        index += chunkSize;
        if (index < equipements.length) {
            requestAnimationFrame(addChunk);
        }
    }

    if (equipements.length > 0) {
        addChunk();
    }
}

/**
 * Fonction de recuperation des informations d'un equipement via son id 
 */
async function fetchEquipementById(id) {
    console.log("debug");
    let fetchUrl = `/api/map/suggestions?q=${encodeURIComponent(id)}`;

    console.log(fetchUrl);
    try
    {
        const res = await fetch(fetchUrl);
        if (!res.ok) return;

        const data = await res.json();

        if (data.length !== 1) return null;
        return data[0];
    }
    catch (err)
    {
        return;
    }
}

async function fetchFilteredSuggestions(query=null) {
    if (query === null || query === "") {
        masquerSuggestions();
        return;
    }

    let url = new URL(window.location.href);

    if (query === null || query === "") {
        let param_value = url.searchParams.get('q');
        query = param_value !== "" ? param_value : null;
    }

    if (query === null) return;

    let data = [];

    try 
    {
        if (url.href.startsWith("https://")) {
            let fetchPlacesUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;
            const res = await fetch(fetchPlacesUrl);
            if (res.ok) {
                const placesData = await res.json();
                placesData.forEach(place => {

                    // On autorise que les villes et code postaux et que ceux qui sont en France
                    // On peut autoriser de nouveaux pays ou types en les rajoutant dans la liste
                    let countries = ["France"];
                    let types = ["city", "village", "postcode"];

                    if (types.includes(place['addresstype']) && countries.some(country => place['display_name'].includes(country))) {
                        if (place['addresstype'] === "postcode") {
                            place['name'] = place['display_name'];
                        }
                        data = data.concat(place);
                    }
                });
            }
        }
    }
    catch (err) 
    {
        if (err.message.includes("NetworkError")) {
            console.warn("Malheureusement cette api ne fonctionne pas en local car ce n'est pas une url https.");
        }
    }

    try 
    {
        let fetchEquipementsUrl = filteredUrl('/api/map/suggestions', map.getBounds(), customParams={ q: query });
        const res = await fetch(fetchEquipementsUrl);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des suggestions a échouée).
        
        const equipementsData = await res.json();
        data = data.concat(equipementsData);

    } catch (err) 
    {
        return;
    }

    afficherSuggestions(query, data);
}

async function fetchPolygoneCityInfos(item) {
    if (item.osm_id === undefined || item.osm_type === undefined) return null;

    if (!window.location.href.startsWith('https://')) {
        console.warn("Malheureusement cette api ne fonctionne pas en local car ce n'est pas une url https.");
        return null;
    }

    const osmId = item.osm_id;
    const osmType = item.osm_type.charAt(0).toUpperCase();

    let url = `https://nominatim.openstreetmap.org/lookup?format=json&polygon_geojson=1&osm_ids=${osmType}${osmId}`;

    try 
    {
        const res = await fetch(url);
        const data = await res.json();
        if (!data || !data[0].geojson) return;
        
        return data;
    }
    catch (err) 
    {
        return null;
    }
}

async function fetchEquipementDisplayImage(name, city) {
    const searchQuery = `"${name.toLowerCase()}" "${city.toLowerCase()}"`;

    const url = `https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search&gsrsearch=${encodeURIComponent(searchQuery)}&gsrnamespace=6&gsrlimit=1&prop=imageinfo&iiprop=url&origin=*`;

    try {
        const res = await fetch(url);

        if (!res.ok) throw new Error();

        const data = await res.json();

        const pages = data.query ? data.query.pages : {};
        const pageId = Object.keys(pages)[0];

        if (pageId && pageId !== "-1" && pages[pageId].imageinfo) {
            const imageUrl = pages[pageId].imageinfo[0].url;

            if (imageUrl.endsWith('.jpg') || imageUrl.endsWith('.png')) {
                return imageUrl;
            }

            return await fetchEquipementGenericDisplayImage(name, city);
        }
        return await fetchEquipementGenericDisplayImage(name, city);
    }
    catch(err) {
        return null;
    }
}

async function fetchEquipementGenericDisplayImage(name) {
    const searchQuery = `"${name.toLowerCase()}"`;

    const url = `https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search&gsrsearch=${encodeURIComponent(searchQuery)}&gsrnamespace=6&gsrlimit=1&prop=imageinfo&iiprop=url&origin=*`;

    try {
        const res = await fetch(url);

        if (!res.ok) throw new Error();

        const data = await res.json();

        const pages = data.query ? data.query.pages : {};
        const pageId = Object.keys(pages)[0];

        if (pageId && pageId !== "-1" && pages[pageId].imageinfo) {
            const imageUrl = pages[pageId].imageinfo[0].url;

            if (imageUrl.endsWith('.jpg') || imageUrl.endsWith('.png')) {
                return imageUrl;
            }

            return null;
        }
        return null;
    }
    catch(err) {
        return null;
    }
}