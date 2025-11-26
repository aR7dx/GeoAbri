/**
 * url to fetch with the api
 */
function filteredUrl(basedUrl, bounds, customParams={}) {
    const params = new URLSearchParams(new URL(window.location).search);

    Object.keys(customParams).forEach(key => {
        params.set(key, customParams[key]);
    });

    params.set('minLat', bounds.getSouth());
    params.set('maxLat', bounds.getNorth());
    params.set('minLon', bounds.getWest());
    params.set('maxLon', bounds.getEast());

    return basedUrl + '?' + params.toString();
}

/**
 * fonction de recuperation des resultats de lieux avec une requete sql filtrées et affichage des resultat sur la carte
 *  */
async function fetchFilteredEquipements() {
    
    let fetchUrl = filteredUrl('/api/map-equipements', map.getBounds());

    try {
        const res = await fetch(fetchUrl);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des lieux a échouée).

        const data = await res.json();
        clusterGroup.clearLayers();
        data.forEach(equipement => {
            clusterGroup.addLayer(
                L.marker([parseFloat(equipement.lat), parseFloat(equipement.lon)]).on('click', () => {

                    let url = new URL(window.location.href);
                    url.searchParams.set('id', equipement.id);
                    window.history.pushState({ path: url.href }, '', url.href);
                    
                    afficherEquipement(equipement);
                    map.setView([equipement.lat, equipement.lon], 13);

                }).bindPopup(equipement.name)
            );
        });
    } catch (err) {
        return;
    }
}

async function fetchFilteredSuggestions(query=null) {
    let url = new URL(window.location.href);

    if (query === null || query === "") {
        let param_value = url.searchParams.get('q');
        query = param_value !== "" ? param_value : null;
    }

    if (query === null) return;

    let data = [];

    try {
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
    catch (err) {
        if (err.message.includes("NetworkError")) {
            console.log("Malheureusement cette api ne fonctionne pas en local car ce n'est pas une url https.");
        }
    }

    try {
        let fetchEquipementsUrl = filteredUrl('/api/map-suggestions', map.getBounds(), customParams={ q: query });
        const res = await fetch(fetchEquipementsUrl);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des suggestions a échouée).
        
        const equipementsData = await res.json();
        data = data.concat(equipementsData);

    } catch (err) {
        return;
    }

    afficherSuggestions(query, data);
}

async function fetchPolygoneCityInfos(item) {
    const osmId = item.osm_id;
    const osmType = item.osm_type.charAt(0).toUpperCase();

    let url = `https://nominatim.openstreetmap.org/lookup?format=json&polygon_geojson=1&osm_ids=${osmType}${osmId}`;

    try {
        const res = await fetch(url);
        const data = await res.json();
        if (!data || !data[0].geojson) return;
        
        return data ?? data[0].geojson;
    }
    catch (err) {
        return null;
    }
}