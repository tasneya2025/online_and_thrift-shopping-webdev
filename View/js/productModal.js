(function () {

    var overlay = document.getElementById('productModal');
    if (!overlay) { console.error('productModal: #productModal overlay not found'); return; }

    window.openModal = function (id) {
        var list = window.PRODUCTS || [];
        var p = null;
        for (var i = 0; i < list.length; i++) {
            if (list[i].id == id) { p = list[i]; break; }
        }
        if (!p) { console.warn('openModal: no product with id=' + id); return; }

        document.getElementById('modalBody').innerHTML = buildHTML(p);
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    window.closeModal = function () {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) window.closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.closeModal();
    });

    function buildHTML(p) {
        var isThrift = (p.item_condition || '').toLowerCase() === 'thrift';
        var inStock  = parseInt(p.stock) > 0;

        var sizeTags = '';
        if (p.sizes && p.sizes.trim() !== '') {
            p.sizes.split(',').forEach(function (s) {
                sizeTags += '<span class="size-tag">' + esc(s.trim()) + '</span>';
            });
        } else {
            sizeTags = '<span class="no-info">Not specified</span>';
        }

        var colorTags = '';
        if (p.colors && p.colors.trim() !== '') {
            p.colors.split(',').forEach(function (c) {
                colorTags += '<span class="color-tag">' + esc(c.trim()) + '</span>';
            });
        } else {
            colorTags = '<span class="no-info">Not specified</span>';
        }

        var thriftBlock = '';
        if (isThrift) {
            thriftBlock += '<div class="modal-thrift-section">';
            thriftBlock += '<strong><i class="fa-solid fa-recycle"></i> Thrift Details</strong>';
            if (p.used_duration)     thriftBlock += '<p><b>Duration Used:</b> ' + esc(p.used_duration) + '</p>';
            if (p.condition_details) thriftBlock += '<p><b>Condition:</b> '      + esc(p.condition_details) + '</p>';
            if (p.defects)           thriftBlock += '<p><b>Known Defects:</b> '  + esc(p.defects) + '</p>';
            thriftBlock += '</div>';
        }

        var descBlock = (p.description && p.description.trim() !== '')
            ? '<div class="modal-description">' + esc(p.description) + '</div>'
            : '';

        var actionBlock = '';
        if (inStock) {
            actionBlock += '<a href="../../Controller/addToCartController.php?id=' + p.id + '&redirect=cart" class="modal-btn-buy">';
            actionBlock += '<i class="fa-solid fa-bolt"></i> Buy Now</a>';
            actionBlock += '<a href="../../Controller/addToCartController.php?id=' + p.id + '" class="modal-btn-cart" title="Add to Cart">';
            actionBlock += '<i class="fa-solid fa-cart-plus"></i></a>';
        } else {
            actionBlock = '<button class="modal-btn-buy modal-btn-disabled" disabled>Unavailable</button>';
        }

        var badgeClass = isThrift ? 'badge-thrift-item'  : 'badge-new-item';
        var badgeIcon  = isThrift ? 'fa-recycle'          : 'fa-tag';
        var badgeText  = isThrift ? 'Thrift'              : 'Brand New';
        var stockClass = inStock  ? 'in-stock'            : 'out-stock';
        var stockIcon  = inStock  ? 'fa-circle-check'     : 'fa-circle-xmark';
        var stockText  = inStock  ? p.stock + ' in stock' : 'Out of stock';

        var h = '';

        h += '<div class="modal-image-col">';
        h +=   '<img src="../uploads/' + esc(p.image_path) + '" alt="' + esc(p.name) + '">';
        h +=   '<span class="modal-condition-badge ' + badgeClass + '">';
        h +=     '<i class="fa-solid ' + badgeIcon + '"></i> ' + badgeText;
        h +=   '</span>';
        h += '</div>';

        h += '<div class="modal-info-col">';
        h +=   '<h2 class="modal-product-name">'  + esc(p.name)   + '</h2>';
        h +=   '<p class="modal-seller">by <span>' + esc(p.seller) + '</span></p>';
        h +=   '<div class="modal-price">$' + parseFloat(p.price).toFixed(2) + '</div>';
        h +=   '<hr class="modal-divider">';

        h +=   '<div class="modal-info-grid">';
        h +=     '<div class="info-chip"><div class="chip-label">Category</div><div class="chip-value">' + (esc(p.category)     || '—') + '</div></div>';
        h +=     '<div class="info-chip"><div class="chip-label">Type</div><div class="chip-value">'     + (esc(p.product_type) || '—') + '</div></div>';
        h +=     '<div class="info-chip"><div class="chip-label">Fabric</div><div class="chip-value">'   + (esc(p.fabric)       || '—') + '</div></div>';
        h +=     '<div class="info-chip"><div class="chip-label">Stock</div><div class="chip-value">'    + p.stock + ' units</div></div>';
        h +=   '</div>';

        h +=   '<div class="modal-tags-section">';
        h +=     '<div class="modal-tags-label">Available Sizes</div>';
        h +=     '<div class="modal-tags">' + sizeTags + '</div>';
        h +=   '</div>';

        h +=   '<div class="modal-tags-section">';
        h +=     '<div class="modal-tags-label">Available Colors</div>';
        h +=     '<div class="modal-tags">' + colorTags + '</div>';
        h +=   '</div>';

        h +=   descBlock;
        h +=   thriftBlock;

        h +=   '<div class="modal-stock ' + stockClass + '">';
        h +=     '<i class="fa-solid ' + stockIcon + '"></i> ' + stockText;
        h +=   '</div>';

        h +=   '<div class="modal-actions">' + actionBlock + '</div>';
        h += '</div>';

        return h;
    }

    function esc(str) {
        return String(str || '')
            .replace(/&/g,  '&amp;')
            .replace(/</g,  '&lt;')
            .replace(/>/g,  '&gt;')
            .replace(/"/g,  '&quot;')
            .replace(/'/g,  '&#39;');
    }

}());