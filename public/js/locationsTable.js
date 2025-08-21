document.addEventListener('DOMContentLoaded', function () {
    const locations = JSON.parse(document.getElementById('locations-data').textContent);
    const fields = JSON.parse(document.getElementById('fields-data').textContent);

    const filterDropdown = document.getElementById('filter-dropdown');
    const headerRow = document.getElementById('dynamic-table-header');
    const body = document.getElementById('dynamic-table-body');

    // --- Restore checkbox state from localStorage ---
    const savedFields = JSON.parse(localStorage.getItem('locationsFilterFields') || '[]');
    if (savedFields.length) {
        document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
            cb.checked = savedFields.includes(cb.getAttribute('data-field'));
        });
    }

    // --- Get checked fields ---
    function getCheckedFields() {
        return Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);
    }

    // --- Render table (header + body) ---
    function renderTable(useCheckboxes = false) {
        let checkedFields;
        const savedFields = JSON.parse(localStorage.getItem('locationsFilterFields') || '[]');
        if (!useCheckboxes && savedFields.length) {
            checkedFields = savedFields;
            document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
                cb.checked = savedFields.includes(cb.getAttribute('data-field'));
            });
        } else {
            checkedFields = getCheckedFields();
        }

        // Header
        headerRow.innerHTML = '';
        checkedFields.forEach(field => {
            const th = document.createElement('th');
            th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            headerRow.appendChild(th);
        });
        // Filter button
        const thOptions = document.createElement('th');
        thOptions.style.position = 'relative';
        thOptions.innerHTML = `<button id="filter-button" style="background: none; border: none; cursor: pointer; padding: 0; vertical-align: middle;">
            <i class='fa fa-filter'></i>
        </button>`;
        headerRow.appendChild(thOptions);

        // Body
        body.innerHTML = '';
        locations.forEach(location => {
            const tr = document.createElement('tr');
            checkedFields.forEach(field => {
                const td = document.createElement('td');
                td.textContent = location[field] !== undefined ? location[field] : '';
                tr.appendChild(td);
            });
            // Options column
            const tdOptions = document.createElement('td');
            tdOptions.className = 'options-icon';
            tdOptions.style.position = 'relative';
            tdOptions.innerHTML = `⋮
                <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                    <a href="/admin/locations/view/${location.id}?type=${location.type}">View</a>
                    <a href="/admin/locations/edit/${location.id}?type=${location.type}">Edit</a>

                    <form action="/admin/locations/delete/${location.id}?type=${location.type}" method="POST" style="margin: 0;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                    </form>
                </div>`;
            tr.appendChild(tdOptions);
            body.appendChild(tr);
        });

        // Re-bind filter button and options events
        setTimeout(() => {
            const filterBtn = document.getElementById('filter-button');
            if (filterBtn && !filterBtn._bound) {
                filterBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    filterDropdown.classList.toggle('active');
                });
                filterBtn._bound = true;
            }
            document.querySelectorAll('.options-icon').forEach(icon => {
                if (!icon._bound) {
                    icon.addEventListener('click', function (event) {
                        event.stopPropagation();
                        const dropdown = this.querySelector('.dropdown-menu');
                        const isActive = dropdown.classList.contains('active');
                        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                        if (!isActive) dropdown.classList.add('active');
                    });
                    icon._bound = true;
                }
            });
        }, 0);
    }

    // --- Checkbox and Save Button Logic ---
    document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            renderTable(true); // Use current checkbox states, not localStorage
        });
    });
    const saveBtn = document.getElementById('save-filter-fields');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox'))
                .filter(cb => cb.checked)
                .map(cb => cb.getAttribute('data-field'));
            localStorage.setItem('locationsFilterFields', JSON.stringify(checkedFields));
            this.textContent = 'Saved!';
            setTimeout(() => { this.textContent = 'Save'; }, 1000);
            filterDropdown.classList.remove('active');
            renderTable(); // Re-render table with saved fields
        });
    }

    // --- Dropdown Handling ---
    filterDropdown.addEventListener('click', function(event) {
        event.stopPropagation();
    });
    document.addEventListener('click', function (event) {
        if (!event.target.closest('#filter-button')) {
            filterDropdown.classList.remove('active');
        }
        if (!event.target.closest('.options-icon')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        }
    });

    // --- Initial render ---
    renderTable(locations);
});