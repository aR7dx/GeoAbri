/**
 * url to fetch with the api
 */
function filteredUrl(basedUrl, bounds, customParams={}) {
    const params = new URLSearchParams({
        ...customParams,
        minLat: bounds.getSouth(),
        maxLat: bounds.getNorth(),
        minLon: bounds.getWest(),
        maxLon: bounds.getEast(),
        ...Object.fromEntries(new URLSearchParams(window.location.search).entries()) // necessaire de rajouter les parametres actuels pour prendre en compte les filtres de recherche
    });
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

    let fetchUrl = filteredUrl('/api/map-suggestions', map.getBounds(), customParams={ q: query });

    try {
        const res = await fetch(fetchUrl);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des suggestions a échouée).

        afficherSuggestions(query, res);
    } catch (err) {
        return;
    }
}
