document.addEventListener('DOMContentLoaded', function() {
    // Open modal on "Add New" click
    document.querySelectorAll('.add-new-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHotel = window.locationType === "hotel";
            document.getElementById('modalTitle').textContent = isHotel ? "Add New Room" : "Add New Lodge Unit";
            document.getElementById('modalLabelNumber').textContent = isHotel ? "Room #" : "Unit #";
            var li = document.getElementById('modalLiDate');
            var lo = document.getElementById('modalLoDate');
            if (li) li.value = '';
            if (lo) lo.value = '';
            document.getElementById('roomUnitModal').style.display = 'block';
        });
    });

    // Edit button functionality
    document.querySelectorAll('.edit-unit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Set modal title
            document.getElementById('modalTitle').textContent = btn.dataset.type === 'hotel' ? 'Edit Room' : 'Edit Lodge Unit';

            // Fill form fields
            document.getElementById('modalNumber').value = btn.dataset.unit || '';
            var rateInput = document.getElementById('modalDailyRate');
            if (rateInput) rateInput.value = (btn.dataset.dailyRate || '');
            var li = document.getElementById('modalLiDate');
            var lo = document.getElementById('modalLoDate');
            if (li) li.value = (btn.dataset.liDate || '');
            if (lo) lo.value = (btn.dataset.loDate || '');
            if (btn.dataset.type === 'statepark') {
                document.getElementById('modalUnitType').value = btn.dataset.unitType || '';
            }

            // Set form action to update route (you need to implement these routes)
            let form = document.getElementById('roomUnitForm');
            if (btn.dataset.type === 'hotel') {
                form.action = '/admin/rooms/update/' + btn.dataset.id;
            } else {
                form.action = '/admin/lodge_units/update/' + btn.dataset.id;
            }

            // Show modal
            document.getElementById('roomUnitModal').style.display = 'block';
        });
    });

    var modal = document.getElementById('roomUnitModal');
    if (!modal) return;

    var modalContent = modal.querySelector('.modal-content');
    var closeBtn = modal.querySelector('.close-modal');
    var cancelBtn = document.getElementById('cancelModalBtn');

    // Prevent modal from closing when clicking inside the modal content
    if (modalContent) {
        modalContent.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }

    // Close modal when clicking the close button
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Close modal when clicking the cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Close modal when clicking outside the modal content (on the modal background)
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var typeSelect = document.getElementById('type');
    var privatesiteSection = document.getElementById('privatesite-section');

    function togglePrivatesiteSection() {
        if (typeSelect && privatesiteSection) {
            if (typeSelect.value === 'privatesite') {
                privatesiteSection.style.display = '';
            } else {
                privatesiteSection.style.display = 'none';
            }
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', togglePrivatesiteSection);
        // Initial check on page load
        togglePrivatesiteSection();
    }
});

// Archive / Unarchive handlers and archived-selector wiring
document.addEventListener('DOMContentLoaded', function() {
    // Bind archive/unarchive buttons
    function bindArchiveButtons() {
        document.querySelectorAll('.btn-archive').forEach(btn => {
            if (btn._bound) return;
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (!confirm('Are you sure you want to archive this record?')) return;
                // determine path based on page selector existence
                const isRoom = this.closest('table') && document.getElementById('addRoomButton');
                const url = isRoom ? '/admin/rooms/archive/' + id : '/admin/lodge_units/archive/' + id;
                fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(r => r.json()).then(json => {
                    if (json && json.success) {
                        // remove row from DOM
                        const tr = btn.closest('tr');
                        if (tr) tr.remove();
                    } else {
                        alert('Archive failed');
                    }
                }).catch(() => alert('Archive failed'));
            });
            btn._bound = true;
        });

        document.querySelectorAll('.btn-unarchive').forEach(btn => {
            if (btn._bound) return;
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (!confirm('Move this record back to inbox?')) return;
                const isRoom = this.closest('table') && document.getElementById('addRoomButton');
                const url = isRoom ? '/admin/rooms/unarchive/' + id : '/admin/lodge_units/unarchive/' + id;
                fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(r => r.json()).then(json => {
                    if (json && json.success) {
                        const tr = btn.closest('tr');
                        if (tr) tr.remove();
                    } else {
                        alert('Move to inbox failed');
                    }
                }).catch(() => alert('Move to inbox failed'));
            });
            btn._bound = true;
        });
    }

    bindArchiveButtons();

    // When modal or any dynamic changes occur, rebind
    document.addEventListener('click', function() { setTimeout(bindArchiveButtons, 50); });

    // archived selector — reloads page preserving other query params
    ['archived-selector-rooms', 'archived-selector-units'].forEach(id => {
        const sel = document.getElementById(id);
        if (!sel) return;
        sel.addEventListener('change', function() {
            const val = this.value;
            const url = new URL(window.location.href);
            if (val === '1') url.searchParams.set('archived', '1'); else url.searchParams.delete('archived');
            window.location.href = url.toString();
        });
    });
});