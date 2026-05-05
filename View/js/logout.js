document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.logout-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var href = this.href;

            var overlay = document.createElement('div');
            overlay.id = 'logout-overlay';
            overlay.innerHTML = `
                <div class="logout-modal">
                    <p>Are you sure you want to log out?</p>
                    <div class="logout-modal-btns">
                        <a href="${href}" class="btn-sure">Sure</a>
                        <button onclick="document.getElementById('logout-overlay').remove()" class="btn-cancel">Cancel</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
        });
    });
});