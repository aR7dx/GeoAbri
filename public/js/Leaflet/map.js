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
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);

const clusterGroup = L.markerClusterGroup().addTo(map);
const polygonsGroup = L.featureGroup().addTo(map);


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


function setLocation(lat, lon, marker=false, zoom=13, text="📍 Vous êtes ici !") {
    if (marker) {
        L.marker([lat, lon]).addTo(map).bindPopup(text).openPopup();
    }

    map.setView([lat, lon], zoom);
}

function setGeolocation () {
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


let client_coords = sessionStorage.getItem('client_coordinates');

if (client_coords) {
    client_coords = JSON.parse('[' + client_coords + ']');
    setLocation(client_coords[0], client_coords[1], marker=true);
}
else if (!client_coords) {
    setGeolocation();
}


// Premier chargement des marqueurs visibles
map.whenReady(() => {
    fetchFilteredEquipements();
    let v_input = search_input !== null ? search_input.value : null;
    fetchFilteredSuggestions(v_input);
});
map.on('moveend', () => fetchFilteredEquipements());
map.on('zoomend', () => fetchFilteredEquipements());

document.addEventListener('resize', () => {
    resizeMap();
});