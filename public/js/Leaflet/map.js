const coordonnees_paris = [48.8566, 2.3522] // coordonnes par defaut sur la carte

function resizeMap() {
    const mapObject = document.getElementById('map');
    if (mapObject?.classList.contains('resize-map')) {
        mapObject.style.height = `${window.innerHeight - parseInt(window.getComputedStyle(document.getElementById('navbar')).height, 10)}px`;
    }
}
resizeMap();

/*
 * TODO
 * Dans le futur il faudra ajouter un listener sur l'evement de redimensionnement de la fenetre pour adapter 
 * la taille de l'element map car actuellement cela ne ce fait qu'au chargement de la page.
*/ 


const map = L.map('map', { zoomControl: false }).setView([coordonnees_paris[0],coordonnees_paris[1]], 12);
L.control.zoom({ position: 'bottomright' }).addTo(map);

// Contrôle personnalisé pour la géolocalisation
L.Control.Geolocate = L.Control.extend({
    onAdd: function(map) {
        const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        
        Object.assign(container.style, { backgroundColor: 'white', width: '35px', height: '35px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' });

        container.title = 'Recentrer';
        container.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cursor-fill" viewBox="0 0 16 16"><path d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/></svg>';
        
        container.onclick = () => setGeolocation();
        return container;
    }
});

L.control.geolocate = function(opts) {
    return new L.Control.Geolocate(opts);
};

/**
 * add the geolocate button on the bottom right of the map
 */
L.control.geolocate({ position: 'bottomright' }).addTo(map);

/**
 * add attribution on the map
 */
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);


/**
 * utilisation d'une icône rouge pour les markers
 */
const redIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    iconSize: [25, 41], // Taille de l'icône
    iconAnchor: [12, 41], // Point d'ancrage de l'icône
    popupAnchor: [1, -34], // Point d'ancrage de la popup
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    shadowSize: [41, 41], // Taille de l'ombre
    shadowAnchor: [12, 41] // Point d'ancrage de l'ombre
});

/**
 * create the cluster and the polygons layer on the map
 */
const clusterGroup = L.markerClusterGroup().addTo(map);
const polygonsGroup = L.featureGroup().addTo(map);

/**
 * This function place set the location on the map and add if its precised a marker on the map with a popup
 * @param {float} lat 
 * @param {float} lon 
 * @param {boolean} marker 
 * @param {int} zoom 
 * @param {string} text 
 */
function setLocation(lat, lon, marker=false, zoom=13, text="📍 Vous êtes ici !") {
    if (marker) {
        const userMarker = L.circleMarker([lat, lon], {
            radius: 10,
            fillColor: "lightblue",
            fillOpacity: 0.4,
            color: "blue",
            weight: 3,
        }).addTo(map);

        userMarker.bindPopup(text);
    }

    map.setView([lat, lon], zoom);
}

/**
 * This function try to get the geolocation of the user if it haven't already it and use the setLocation function next
 */
function setGeolocation () {
    let client_coords = sessionStorage.getItem('client_coordinates');

    if (client_coords) {
        client_coords = JSON.parse('[' + client_coords + ']');
        setLocation(client_coords[0], client_coords[1], marker=true);
    }
    else {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    sessionStorage.setItem('client_coordinates', [lat, lon]);
                    setLocation(lat, lon, marker=true);
                }, 
                function (err) {
                    alert("Impossible de vous géolocaliser.");
                }
            );
        }
        else {
            alert("La géolocalisation n'est pas supportée sur votre navigateur");
        }
    }
}

/**
 * use the data paramatrer to draw a polygon representing the area of a city
 * @param {Array} data 
 */
function drawPolygone (data) {
    polygonsGroup.clearLayers();

    const polygonLayer = L.geoJSON(data[0].geojson, {
        style: {
            color: "blue",
            weight: 2,
            fillColor: "lightblue",
            fillOpacity: 0.4
        }
    }).addTo(map);

    polygonsGroup.addLayer(polygonLayer);
    map.fitBounds(polygonLayer.getBounds());
} 


// Premier chargement des marqueurs visibles
map.whenReady(() => {
    setGeolocation();
    fetchFilteredEquipements();
    let v_input = search_input !== null ? search_input.value : null;
    fetchFilteredSuggestions(v_input);
});
map.on('moveend', () => fetchFilteredEquipements());
map.on('zoomend', () => fetchFilteredEquipements());

document.addEventListener('resize', () => {
    resizeMap();
});