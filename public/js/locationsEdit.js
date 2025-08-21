document.addEventListener('DOMContentLoaded', function() {
    // Open modal on "Add New" click
    document.querySelectorAll('.add-new-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHotel = window.locationType === "hotel";
            document.getElementById('modalTitle').textContent = isHotel ? "Add New Room" : "Add New Lodge Unit";
            document.getElementById('modalLabelNumber').textContent = isHotel ? "Room #" : "Unit #";
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