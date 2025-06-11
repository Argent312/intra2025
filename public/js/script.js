document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('inputBusqueda');
    const cards = document.querySelectorAll('#searchResultsContainer .user-card');

    input.addEventListener('input', function () {
        const value = this.value.toLowerCase();

        cards.forEach(card => {
            const data = card.getAttribute('data-search');
            if (data.includes(value)) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
});
