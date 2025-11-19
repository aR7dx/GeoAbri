const input = document.getElementById('search-input');

input.addEventListener('input', () => {
    const query = input.value.trim();
    if (!query) {
        return;
    }

    fetch(`/map?q=${encodeURIComponent(query)}`)
        .then(r => r.text())
        .then(html => suggestions.innerHTML = html);
});