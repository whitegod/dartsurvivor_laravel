document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('search-input');
    var searchButton = document.getElementById('search-button');

    function filterRows() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('#caseworkers-table tbody tr');
        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    // Trigger filter on button click
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        filterRows();
    });

    // Trigger filter on Enter key in input
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterRows();
        }
    });

    // Edit button click
    document.querySelectorAll('.edit-caseworker-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('addCaseworkerModalLabel').textContent = 'Edit Caseworker';
            document.getElementById('fname').value = this.getAttribute('data-fname');
            document.getElementById('lname').value = this.getAttribute('data-lname');
            document.getElementById('caseworker-id').value = this.getAttribute('data-id');
            document.getElementById('save-caseworker-btn').style.display = 'none';
            document.getElementById('update-caseworker-btn').style.display = '';
            // Change form action to update route
            document.getElementById('caseworker-form').action = '/admin/caseworkers/' + this.getAttribute('data-id');
            // Add method spoofing for PUT
            if (!document.getElementById('caseworker-form').querySelector('input[name="_method"]')) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('caseworker-form').appendChild(methodInput);
            } else {
                document.getElementById('caseworker-form').querySelector('input[name="_method"]').value = 'PUT';
            }
        });
    });

    // Reset modal on close
    $('#addCaseworkerModal').on('hidden.bs.modal', function () {
        document.getElementById('addCaseworkerModalLabel').textContent = 'Add New Caseworker';
        document.getElementById('fname').value = '';
        document.getElementById('lname').value = '';
        document.getElementById('caseworker-id').value = '';
        document.getElementById('save-caseworker-btn').style.display = '';
        document.getElementById('update-caseworker-btn').style.display = 'none';
        document.getElementById('caseworker-form').action = "{{ route('admin.caseworkers.store') }}";
        // Remove method spoofing for PUT
        let methodInput = document.getElementById('caseworker-form').querySelector('input[name="_method"]');
        if (methodInput) methodInput.value = 'POST';
    });
});