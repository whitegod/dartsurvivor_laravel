document.addEventListener('DOMContentLoaded', function() {
    // Open modal on "Add New" click
    document.querySelectorAll('.add-new-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHotel = window.locationType === "Hotel";
            document.getElementById('modalTitle').textContent = isHotel ? "Add New Room" : "Add New Lodge Unit";
            document.getElementById('modalLabelNumber').textContent = isHotel ? "Room #" : "Unit #";
            document.getElementById('roomUnitModal').style.display = 'block';
        });
    });

    // Close modal
    document.querySelectorAll('.close-modal, #cancelModalBtn').forEach(el => {
        el.addEventListener('click', function() {
            document.getElementById('roomUnitModal').style.display = 'none';
            document.getElementById('roomUnitForm').reset();
        });
    });

    // Optional: Close modal when clicking outside content
    document.getElementById('roomUnitModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            document.getElementById('roomUnitForm').reset();
        }
    });
});