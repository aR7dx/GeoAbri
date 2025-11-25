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
                L.marker([parseFloat(equipement.latitude), parseFloat(equipement.longitude)]).on('click', () => {

                    let url = new URL(window.location.href);
                    url.searchParams.set('id', equipement.id);
                    window.history.pushState({ path: url.href }, '', url.href);
                    
                    afficherEquipement(equipement);
                    map.setView([equipement.latitude, equipement.longitude], 13);

                }).bindPopup(equipement.name)
            );
        });
    } catch (err) {
        return;
    }
}

async function fetchFilteredSuggestions(query=null) {

    if (query === null || query === "") {
        let url = new URL(window.location.href);
        let param_value = url.searchParams.get('q');
        query = param_value !== "" ? param_value : null;
    }

    if (query === null) return;

    let fetchEquipementsUrl = filteredUrl('/api/map-suggestions', map.getBounds(), customParams={ q: query });
    try {
        const fetchPlacesUrl = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);

        if (fetchPlacesUrl.ok) {
            console.log(await fetchPlacesUrl.json());
        }
    }
    catch(err) {
        //
    }

    try {
        const res = await fetch(fetchEquipementsUrl);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des suggestions a échouée).

        afficherSuggestions(query, res);
    } catch (err) {
        return;
    }
}
