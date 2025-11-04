const coordonnees_caen = [49.149006383463586, -0.3527819024020505]
// coordonnes par defaut sur la carte

const map = L.map('map').setView([coordonnees_caen[0],coordonnees_caen[1]], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);