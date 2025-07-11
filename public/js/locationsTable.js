document.addEventListener('DOMContentLoaded', function () {
    const locations = JSON.parse(document.getElementById('locations-data').textContent);
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const filterDropdown = document.getElementById('filter-dropdown');
    const headerRow = document.getElementById('dynamic-table-header');
    const body = document.getElementById('dynamic-table-body');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const addNewBtn = document.querySelector('.add-new-button');

    // --- Render filter checkboxes dynamically ---
    filterDropdown.innerHTML = '';
    fields.forEach(field => {
        const label = document.createElement('label');
        label.style.display = 'flex';
        label.style.alignItems = 'center';
        label.style.padding = '8px 15px';
        label.style.cursor = 'pointer';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'filter-field-checkbox';
        checkbox.dataset.field = field;
        checkbox.checked = true;
        checkbox.style.marginRight = '8px';

        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(
            field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
        ));
        filterDropdown.appendChild(label);
    });

    // --- Filter dropdown toggle ---
    document.addEventListener('click', function (event) {
        const filterBtn = document.getElementById('filter-button');
        if (filterBtn && filterBtn.contains(event.target)) {
            filterDropdown.classList.toggle('active');
            const rect = filterBtn.getBoundingClientRect();
            filterDropdown.style.top = (rect.bottom + window.scrollY) + 'px';
            filterDropdown.style.right = (window.innerWidth - rect.right) + 'px';
        } else if (!event.target.closest('#filter-dropdown')) {
            filterDropdown.classList.remove('active');
        }
    });

    // --- Get checked fields ---
    function getCheckedFields() {
        return Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);
    }

    // --- Render table (header + body) ---
    function renderTable(data) {
        const checkedFields = getCheckedFields();

        // Render header
        headerRow.innerHTML = '';
        checkedFields.forEach(field => {
            const th = document.createElement('th');
            th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            headerRow.appendChild(th);
        });
        // Add filter icon column
        const thOptions = document.createElement('th');
        thOptions.style.position = 'relative';
        thOptions.innerHTML = `<button id="filter-button" type="button" style="background: none; border: none; cursor: pointer; padding: 0; margin: 0;">
            <i class="fa fa-filter"></i>
        </button>`;
        headerRow.appendChild(thOptions);

        // Attach dropdown toggle event after rendering header
        const filterBtn = document.getElementById('filter-button');
        if (filterBtn) {
            filterBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                filterDropdown.classList.toggle('active');
                const rect = filterBtn.getBoundingClientRect();
            });
        }

        // Render body
        body.innerHTML = '';
        data.forEach(location => {
            const tr = document.createElement('tr');
            checkedFields.forEach(field => {
                const td = document.createElement('td');                
                td.textContent = location[field] ?? '';
                tr.appendChild(td);
            });
            // Options column with dropdown
            const tdOptions = document.createElement('td');
            tdOptions.className = 'options-icon';
            tdOptions.style.position = 'relative';
            tdOptions.innerHTML = `
                <span style="cursor:pointer;font-size:22px;color:#888;">&#8942;</span>
                <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                    <a href="/admin/locations/view?type=${encodeURIComponent(location.type)}&id=${location.id}">View</a>
                    <a href="/admin/locations/edit?type=${encodeURIComponent(location.type)}&id=${location.id}">Edit</a>
                    <form action="/admin/locations/delete/${location.id}?type=${encodeURIComponent(location.type)}" method="POST" style="margin: 0;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record and all related rooms/units?');">Delete</button>
                    </form>
                </div>
            `;
            tr.appendChild(tdOptions);
            body.appendChild(tr);
        });

        // Dropdown logic for options
        document.querySelectorAll('.options-icon').forEach(icon => {
            icon.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdown = this.querySelector('.dropdown-menu');
                const isActive = dropdown.classList.contains('active');
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                if (!isActive) {
                    dropdown.classList.add('active');
                }
            });
        });
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.options-icon')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        }
    });

    // --- Filtering logic for search ---
    function getFilteredLocations() {
        const query = (searchInput.value || '').trim().toLowerCase();
        const checkedFields = getCheckedFields();
        if (!query) return locations;
        return locations.filter(location =>
            checkedFields.some(field =>
                (location[field] + '').toLowerCase().includes(query)
            )
        );
    }

    // --- Event: filter checkboxes ---
    filterDropdown.querySelectorAll('.filter-field-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            renderTable(getFilteredLocations());
        });
    });

    // --- Event: search button click ---
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            renderTable(getFilteredLocations());
        });
    }

    // --- Event: Enter key in search input ---
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                renderTable(getFilteredLocations());
            }
        });
    }

    // --- "Add New" button ---
    if (addNewBtn) {
        addNewBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = '/admin/locations/edit';
        });
    }

    // --- Initial render ---
    renderTable(locations);
});