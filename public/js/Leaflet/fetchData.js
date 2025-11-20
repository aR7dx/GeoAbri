function urlWithBounds(bounds) {
    const params = new URLSearchParams({
        ajax: '1',
        minLat: bounds.getSouth(),
        maxLat: bounds.getNorth(),
        minLon: bounds.getWest(),
        maxLon: bounds.getEast(),
        ...Object.fromEntries(new URLSearchParams(window.location.search).entries()) // necessaire de rajouter les parametres actuels pour prendre en compte les filtres de recherche
    });
    return '/map?' + params.toString();
}

/**
 * fonction de recuperation des resultats de lieux avec une requete sql filtrées et affichage des resultat sur la carte
 *  */
async function fetchFilteredMarkers() {
    
    let url = urlWithBounds(map.getBounds());

    try {
        const res = await fetch(url);
        if (!res.ok) return; // TODO (peut-etre afficher une notification ou une alert pour dire que la recuperation des lieux a échoué).

        const data = await res.json();
        clusterGroup.clearLayers();
        data.forEach(equipement => {
            clusterGroup.addLayer(
                L.marker([parseFloat(equipement.latitude), parseFloat(equipement.longitude)]).on('click', () => {

                    url = new URL(window.location.href)
                    url.searchParams.set('id', equipement.id);
                    window.history.pushState({ path: url.href }, '', url.href);
                    
                    afficherEquipement(equipement);
                    map.setView([equipement.latitude, equipement.longitude], 13);

                }).bindPopup(equipement.name)
            );
        });
    } catch (err) {
        if (err.name === 'AbortError') return;
        //console.error('La récupération des marqueurs a échoué', err);
    }
}