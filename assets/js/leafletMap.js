const coordonnees_paris = [48.8566, 2.3522]
// coordonnes par defaut sur la carte

const map = L.map('map').setView([coordonnees_paris[0],coordonnees_paris[1]], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


function setLocation(latitude, longitude, defaultMarker=false) {
    map.setView([latitude, longitude], 15);

    if (defaultMarker) {
        L.marker([latitude, longitude]).addTo(map).bindPopup("Vous êtes ici !").openPopup();
    }
}

function setGeolocation () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                sessionStorage.setItem('client_coordinates', [lat, lon]);
                map.setView([lat, lon], 15);

                L.marker([lat, lon]).addTo(map).bindPopup("Vous êtes ici !").openPopup();
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
let client_lsearch = sessionStorage.getItem('client_last_search');

console.log(client_coords);
console.log(client_lsearch);

if (client_lsearch) {
    // TODO remettre le focus de la carte sur la derniere recherche de l'utilisateur si elle existe
}
else if (client_coords) {
    client_coords = JSON.parse('[' + client_coords + ']');
    setLocation(client_coords[0], client_coords[1], defaultMarker=true);
}

const geolocateMebtn = document.getElementById('geolocateMe');
if (geolocateMebtn) {
    geolocateMebtn.addEventListener('click', () => {
    setGeolocation();
});
}