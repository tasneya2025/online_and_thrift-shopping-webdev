function toggleBell(e) {
    e.stopPropagation();
    document.getElementById('notifDropdown').classList.toggle('open');
}

document.addEventListener('click', function (e) {
    var wrap = document.getElementById('bellWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('open');
    }
});

var dropMarkAll = document.getElementById('dropMarkAll');
if (dropMarkAll) {
    dropMarkAll.addEventListener('click', function () {
        fetch('../../Controller/updateSettingsController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'mark_all_read=1'
        }).then(function () {
            document.querySelectorAll('.notif-drop-item.unread').forEach(function (el) {
                el.classList.remove('unread');
                el.classList.add('read');
                el.querySelector('.notif-drop-dot').style.background = 'transparent';
            });
            var badge = document.getElementById('bellBadge');
            if (badge) badge.classList.add('hidden');
            dropMarkAll.style.display = 'none';
        });
    });
}