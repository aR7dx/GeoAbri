const search_menu = document.getElementById('search-menu');
const equipement_menu = document.getElementById('equipement-menu');

function afficherEquipement(equipement) {
    if (!equipement || equipement === null || equipement === '' || equipement === 0) return;

    if (!equipement_menu.classList.contains('show-menu')) {

        search_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('d-none');
        equipement_menu.classList.add('d-flex');
        equipement_menu.classList.add('show-menu');
        updateEquipementView(equipement);
    }
    else if (equipement_menu.classList.contains('show-menu')) {
        
        equipement_menu.classList.remove('show-menu');
        
        setTimeout(() => {
            equipement_menu.classList.add('show-menu');
            updateEquipementView(equipement);
        }, 100);
    }
}

function updateEquipementView(equipement) {
    const equipement_menu = document.getElementById('equipement-menu');

    // elements
    const equipement_name = document.getElementById('span-equipement-name');
    const equipement_website_container = document.getElementById('equipement-website-container');
    const equipement_website = document.getElementById('span-equipement-website');

    if (equipement_menu.classList.contains('show-menu')) {
        equipement_name.textContent = equipement.name;

        // Les equipements n'ont pas forcement d'url enregistrées
        if (equipement_website && equipement.website !== null) {
            equipement_website_container.classList.remove('d-none');
            equipement_website.href = equipement.website;
            equipement_website.textContent = equipement.website;
        }
        else if (equipement_website) equipement_website_container.classList.add('d-none');
    }
}


const back_button = document.getElementById('back-button');
if (back_button !== null) {
    back_button.addEventListener('click', () => {
        equipement_menu.classList.add('hidden-menu');
        equipement_menu.classList.remove('show-menu');

        const url = new URL(window.location.href)
        url.searchParams.delete('id');
        window.history.pushState({ path: url.href }, '', url.href);
                        
        search_menu.classList.remove('hidden-menu');
        search_menu.classList.remove('d-none');
        search_menu.classList.add('d-flex');
    });
}
