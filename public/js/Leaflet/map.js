const coordonnees_paris = [48.8566, 2.3522] // coordonnes par defaut sur la carte

const mapObject = document.getElementById('map');
if (mapObject?.classList.contains('resize-map')) {
    mapObject.style.height = `${window.innerHeight - parseInt(window.getComputedStyle(document.getElementById('navbar')).height, 10)}px`;
}

/*
 * TODO
 * Dans le futur il faudra ajouter un listener sur l'evement de redimensionnement de la fenetre pour adapter 
 * la taille de l'element map car actuellement cela ne ce fait qu'au chargement de la page.
*/ 


const map = L.map('map', { zoomControl: false }).setView([coordonnees_paris[0],coordonnees_paris[1]], 12);
L.control.zoom({ position: 'bottomright' }).addTo(map);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);


/*
url = new URL(window.location.href)
url.searchParams.set('id', equipement.id);
window.history.pushState({ path: url.href }, '', url.href);*/

const clusterGroup = L.markerClusterGroup().addTo(map);


function setLocation(latitude, longitude, marker=false, text="📍 Vous êtes ici !") {
    map.setView([latitude, longitude], 13);

    if (marker) {
        L.marker([latitude, longitude]).addTo(map).bindPopup(text).openPopup();
    }
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
else if (1==1) {
    //
}
else if (!client_coords && last_equipements_coords == []) {
    setGeolocation();
}


// Premier chargement des marqueurs visibles
map.whenReady(() => {
    fetchFilteredMarkers();
});
map.on('moveend', () => fetchFilteredMarkers());
map.on('zoomend', () => fetchFilteredMarkers());