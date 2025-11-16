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


const map = L.map('map').setView([coordonnees_paris[0],coordonnees_paris[1]], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


function setLocation(latitude, longitude, marker=false, text='') {
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
                setLocation(lat, lon, marker=true, text="Vous êtes ici !");
            }, 
            function (error) {
                alert("Impossible de vous géolocaliser.");
            }
        );
    }
    else {
        alert("La géolocalisation n'est pas supportée sur votre navigateur");
    }
}


let client_coords = sessionStorage.getItem('client_coordinates');
console.log(client_coords);


if (client_coords) {
    client_coords = JSON.parse('[' + client_coords + ']');
    setLocation(client_coords[0], client_coords[1], marker=true, text="Vous êtes ici !");
}
else if (!client_coords) {
    setGeolocation();
}

const geolocateMebtn = document.getElementById('geolocateMe');
if (geolocateMebtn) {
    geolocateMebtn.addEventListener('click', () => {
    setGeolocation();
});
}