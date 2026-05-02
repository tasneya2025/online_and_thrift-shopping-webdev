document.getElementById('productSearch').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase().trim();
    const cards  = document.querySelectorAll('.product-card');

    cards.forEach(function (card) {
        const name   = card.dataset.name   || '';
        const seller = card.dataset.seller || '';
        const cat    = card.dataset.cat    || '';
        const match  = name.includes(filter) || seller.includes(filter) || cat.includes(filter);
        card.style.display = match ? '' : 'none';
    });
});