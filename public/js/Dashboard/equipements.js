document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#equipements-table tbody");
    const pagination = document.querySelector("#equipements-pagination");
    const searchInput = document.querySelector("#search-input");

    let currentPage = 1;

    function loadEquipements(page=1, search="") {
        fetch(`/api/map/suggestions?page=${page}&q=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                currentPage = data.page;
                tableBody.innerHTML = "";
                pagination.innerHTML = "";
                
                if (data.suggestions.length > 0) {
                    data.suggestions.forEach(eq => {
                        const tr = document.createElement("tr");

                        tr.innerHTML = `
                            <td>${eq.id}</td>
                            <td>${eq.name}</td>
                            <td>${eq.type}</td>
                            <td>${eq.commune}</td>
                            <td>${eq.owner}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="#" class="btn btn-sm btn-danger">Supprimer</a>
                            </td>
                        `;

                        tableBody.appendChild(tr);
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="6" class="text-center">Aucun résultat</td></tr>`;
                    return;
                }
                

                const nb_result_span = document.getElementById('nb-equipements-results');
                nb_result_span.innerText = `Affichage de ${(currentPage - 1) * data.count} à ${(currentPage - 1) * data.count + (data.count - 1)} sur ${data.total_count} résultats`;

                // pagination
                const totalPages = parseInt(data.total_count / data.count);

                if (totalPages <= 1) return;

                const maxButtons = 5; // le nombre maximal de bouton affiche à la fois
                let start = parseInt(Math.max(1, currentPage - 2));
                let end = parseInt(start + maxButtons - 1);

                if (end > totalPages) {
                    end = parseInt(totalPages);
                    start = parseInt(Math.max(1, end - maxButtons + 1));
                }

                // Bouton Precedent
                if (currentPage > 1) {
                    addPageButton("«", currentPage - 1);
                }

                // Pages
                for (let i = start; i <= end; i++) {
                    addPageButton(i, i, i === currentPage);
                }

                // Bouton suivant
                if (currentPage < totalPages) {
                    addPageButton("»", currentPage + 1);
                }
            });
    }
    
    function addPageButton(label, page, active=false) {
        const li = document.createElement("li");
        li.className = "page-link" + (active ? " active" : "");
        li.style.cursor = 'pointer';

        li.innerText = label;

        li.addEventListener("click", e => {
            e.preventDefault();
            loadEquipements(page, searchInput.value);
        });

        pagination.appendChild(li);
    }

    let debounceTime;
    searchInput.addEventListener("input", () => {
        clearTimeout(debounceTime);

        debounceTime = setTimeout(() => {
            loadEquipements(1, searchInput.value);
        }, 250);
    });

    loadEquipements();
});