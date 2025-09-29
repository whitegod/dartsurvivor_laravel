let locations = [];
let currentSortField = localStorage.getItem('locationsSortField') || null;
let currentSortDirection = localStorage.getItem('locationsSortDirection') || 'asc';
let globalSearchTerm = '';

function renderTable(useCheckboxes = false) {
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const fieldLabels = {
        name: 'Location Name',
        address: 'Address',
        city: 'City',
        state: 'State',
        zip: 'Zip',
        type: 'Type',
        contact_name: 'Contact Name',
        fdec_id: 'FDEC'
        // Add other field labels as needed
    };

    // Build FDEC mapping (id -> label) if provided by server
    let fdecMap = {};
    try {
        const fdecListEl = document.getElementById('fdec-list-data');
        if (fdecListEl) {
            const fdecList = JSON.parse(fdecListEl.textContent || '[]');
            fdecList.forEach(function(f){ fdecMap[String(f.id)] = f.label; });
        }
    } catch (e) { fdecMap = {}; }

    let checkedFields;
    const savedFields = JSON.parse(localStorage.getItem('locationsFilterFields') || '[]');
    if (!useCheckboxes && savedFields.length) {
        checkedFields = savedFields;
        document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
            cb.checked = savedFields.includes(cb.getAttribute('data-field'));
        });
    } else {
        checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);
    }

    // Header
    const headerRow = document.getElementById('dynamic-table-header');
    headerRow.innerHTML = '';
    renderSortableTableHeader({
        checkedFields,
        fieldLabels,
        currentSortField,
        currentSortDirection,
        sortCallback: sortTableByField,
        headerRow
    });

    const thOptions = document.createElement('th');
    thOptions.style.position = 'relative';
    thOptions.innerHTML = `<button id="filter-button" style="background: none; border: none; cursor: pointer; padding: 0; vertical-align: middle;">
            <i class='fa fa-filter'></i>
        </button>`;
    headerRow.appendChild(thOptions);

    // Body
    const body = document.getElementById('dynamic-table-body');
    body.innerHTML = '';
    locations.filter(location => {
        if (!globalSearchTerm) return true;
        return Object.values(location).some(val =>
            (val ?? '').toString().toLowerCase().includes(globalSearchTerm)
        );
    }).forEach(location => {
        const tr = document.createElement('tr');
        checkedFields.forEach(field => {
            const td = document.createElement('td');
            // Special handling for fdec_id: may be stored as JSON array or CSV or single id
            if (field === 'fdec_id') {
                let raw = location[field];
                let ids = [];
                if (!raw || raw === null) ids = [];
                else if (Array.isArray(raw)) ids = raw.map(String);
                else if (typeof raw === 'string') {
                    // try JSON parse, otherwise split by comma
                    try { const parsed = JSON.parse(raw); if (Array.isArray(parsed)) ids = parsed.map(String); else ids = String(raw).split(',').map(s=>s.trim()).filter(Boolean); } catch(e) { ids = String(raw).split(',').map(s=>s.trim()).filter(Boolean); }
                } else {
                    ids = [String(raw)];
                }
                const labels = ids.map(id => fdecMap[String(id)] || id).filter(Boolean);
                td.textContent = labels.join(', ');
            } else {
                td.textContent = location[field] !== undefined ? location[field] : '';
            }
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
        const filterDropdown = document.getElementById('filter-dropdown'); // <-- Add this line
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

function sortTableByField(field) {
    if (currentSortField === field) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortField = field;
        currentSortDirection = 'asc';
    }

    // Save sort status immediately when sorting
    localStorage.setItem('locationsSortField', currentSortField);
    localStorage.setItem('locationsSortDirection', currentSortDirection);

    sortDataArray(locations, field, currentSortDirection);
    renderTable();
}

document.addEventListener('DOMContentLoaded', function () {
    // Restore filter fields from localStorage
    const savedFields = JSON.parse(localStorage.getItem('locationsFilterFields') || '[]');
    if (savedFields.length) {
        document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
            cb.checked = savedFields.includes(cb.getAttribute('data-field'));
        });
    }

    // Save filter fields when clicking Save
    document.getElementById('save-filter-fields').addEventListener('click', function() {
        const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox'))
            .filter(cb => cb.checked)
            .map(cb => cb.getAttribute('data-field'));
        localStorage.setItem('locationsFilterFields', JSON.stringify(checkedFields));
        this.textContent = 'Saved!';
        setTimeout(() => { this.textContent = 'Save'; }, 1000);
        document.getElementById('filter-dropdown').classList.remove('active');
    });

    // Initialize locations before sorting or rendering
    locations = JSON.parse(document.getElementById('locations-data').textContent);

    // Initial table render with sort
    if (currentSortField) {
        applySavedSortToTable(locations, currentSortField, currentSortDirection, renderTable);
    } else {
        renderTable();
    }

    // --- Checkbox Logic ---
    document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            renderTable(true);
        });
    });
});

document.addEventListener('click', function(event) {
    const filterDropdown = document.getElementById('filter-dropdown');
    if (filterDropdown && !event.target.closest('#filter-button') && !event.target.closest('#filter-dropdown')) {
        filterDropdown.classList.remove('active');
    }
});
