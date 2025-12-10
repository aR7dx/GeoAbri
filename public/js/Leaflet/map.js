const coordonnees_paris = [48.8566, 2.3522] // coordonnes par defaut sur la carte

function resizeMap() {
    const mapObject = document.getElementById('map');
    if (mapObject?.classList.contains('resize-map')) {
        mapObject.style.height = `${window.innerHeight - parseInt(window.getComputedStyle(document.getElementById('navbar')).height, 10)}px`;
    }
}
resizeMap();

// Listener pour le redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    resizeMap();
});


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
 * create the cluster and the polygons layer on the map with optimized settings
 */
const clusterGroup = L.markerClusterGroup({
    chunkedLoading: true,
    chunkInterval: 200,
    chunkDelay: 50,
    maxClusterRadius: 80,
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false,
    zoomToBoundsOnClick: true,
    removeOutsideVisibleBounds: true
}).addTo(map);
const polygonsGroup = L.featureGroup().addTo(map);
let rangeCircle = null; // Cercle de rayon pour la recherche

/**
 * Dessine un cercle de rayon sur la carte
 */
function drawRangeCircle(lat, lon, radiusKm) {
    // Supprimer l'ancien cercle s'il existe
    if (rangeCircle) {
        map.removeLayer(rangeCircle);
    }
    
    // Dessiner le nouveau cercle (rayon en mètres)
    rangeCircle = L.circle([lat, lon], {
        color: '#0d6efd',
        fillColor: '#0d6efd',
        fillOpacity: 0.1,
        weight: 2,
        radius: radiusKm * 1000
    }).addTo(map);
}

/**
 * Obtient le centre du cercle de recherche (commune ou utilisateur)
 */
async function getSearchCenter() {
    const urlParams = new URLSearchParams(window.location.search);
    const commune = urlParams.get('commune');
    
    // Priorité 1 : Coordonnées de la commune
    if (commune && commune.trim() !== '') {
        const coordinates = await fetchCommuneCoordinates(commune);
        if (coordinates) {
            return { lat: coordinates.lat, lon: coordinates.lon };
        }
    }
    
    // Priorité 2 : Coordonnées de l'utilisateur
    const client_coords = sessionStorage.getItem('client_coordinates');
    if (client_coords) {
        const coords = JSON.parse('[' + client_coords + ']');
        return { lat: coords[0], lon: coords[1] };
    }
    
    return null;
}

/**
 * Gère l'affichage du cercle de rayon si spécifié
 */
async function handleRangeCircle() {
    const urlParams = new URLSearchParams(window.location.search);
    const range = urlParams.get('range');
    
    // Pas de cercle si range n'est pas spécifié ou supérieur à 100 (> 200km)
    if (!range || range === '' || parseInt(range) > 100) {
        if (rangeCircle) {
            map.removeLayer(rangeCircle);
            rangeCircle = null;
        }
        return;
    }
    
    const radiusKm = parseInt(range) * 2; // Conversion: valeur * 2 = km
    const center = await getSearchCenter();
    
    if (center) {
        drawRangeCircle(center.lat, center.lon, radiusKm);
    }
}

/**
 * This function place set the location on the map and add if its precised a marker on the map with a popup
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


// Debounce et seuil de déplacement pour éviter trop de requêtes
let fetchTimeout;
let lastBounds = null;
let lastZoom = null;
const MOVEMENT_THRESHOLD = 0.3; // 30% de déplacement minimum

function shouldRefreshMarkers() {
    if (!lastBounds) return true;
    
    const currentBounds = map.getBounds();
    const currentCenter = map.getCenter();
    const lastCenter = lastBounds.getCenter();
    const currentZoom = map.getZoom();
    
    // Calculer la distance entre les centres
    const distance = map.distance(currentCenter, lastCenter);
    
    // Calculer la taille de la zone visible
    const boundsSize = map.distance(
        currentBounds.getNorthEast(),
        currentBounds.getSouthWest()
    );
    
    // on rafraichi quand :
    // - le déplacement > 30% de la zone visible
    // - ou alors si on a dézoomé (zoom réduit, donc zone plus grande)
    return (distance / boundsSize > MOVEMENT_THRESHOLD) || 
           (lastZoom !== null && currentZoom < lastZoom);
}

function debouncedFetchEquipements() {
    clearTimeout(fetchTimeout);
    fetchTimeout = setTimeout(() => {
        if (shouldRefreshMarkers()) {
            lastBounds = map.getBounds();
            lastZoom = map.getZoom();
            fetchFilteredEquipements();
        }
    }, 300);
}

// premier chargement des marqueurs visibles
map.whenReady(() => {
    
    setGeolocation();
    lastBounds = map.getBounds();
    lastZoom = map.getZoom();
    fetchFilteredEquipements();

    if(search_input !== null) {
        fetchFilteredSuggestions(search_input.value);
    }
    
    // Afficher le cercle de rayon si nécessaire
    setTimeout(() => {
        handleRangeCircle();
    }, 500);
});
map.on('moveend', debouncedFetchEquipements);
map.on('zoomend', debouncedFetchEquipements);