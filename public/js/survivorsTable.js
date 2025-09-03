let survivors = [];
let currentSortField = localStorage.getItem('survivorsSortField') || null;
let currentSortDirection = localStorage.getItem('survivorsSortDirection') || 'asc';
let globalSearchTerm = '';

document.addEventListener('DOMContentLoaded', function() {
    // Restore filter fields from localStorage
    const savedFields = JSON.parse(localStorage.getItem('survivorsFilterFields') || '[]');
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
        localStorage.setItem('survivorsFilterFields', JSON.stringify(checkedFields));
        this.textContent = 'Saved!';
        setTimeout(() => { this.textContent = 'Save'; }, 1000);
        document.getElementById('filter-dropdown').classList.remove('active');
    });

    // Initialize survivors before sorting or rendering
    survivors = JSON.parse(document.getElementById('survivors-data').textContent);

    // Initial table render with sort
    if (currentSortField) {
        applySavedSortToTable(survivors, currentSortField, currentSortDirection, renderTable);
    } else {
        renderTable();
    }
});

document.querySelectorAll('.options-icon').forEach(icon => {
    icon.addEventListener('click', function () {
        const dropdown = this.querySelector('.dropdown-menu');
        const isActive = dropdown.classList.contains('active');
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        if (!isActive) {
            dropdown.classList.add('active');
        }
    });
});

document.addEventListener('click', function (event) {
    if (!event.target.closest('.options-icon')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
    }
});

document.getElementById('search-button').addEventListener('click', function () {
    const searchInput = document.getElementById('search-input').value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', searchInput);
    window.location.href = url.toString();
});

document.getElementById('search-input').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        const searchInput = document.getElementById('search-input').value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput);
        window.location.href = url.toString();
    }
});

// --- Dynamic Table Rendering ---
function renderTable(useCheckboxes = false) {
    const fields = JSON.parse(document.getElementById('fields-data').textContent);

    // Add a mapping for field labels
    const fieldLabels = {
        fname: 'Name',
        lname: 'Name',
        name: 'Name',
        primary_phone: 'Phone',
        secondary_phone: 'Phone',
        phone: 'Phone',
        fema_id: 'FEMA-ID',
        hh_size: 'HH Size',
        own_rent: 'Own/Rent',
        caseworker_id: 'Caseworker'
    };

    let checkedFields;
    const savedFields = JSON.parse(localStorage.getItem('survivorsFilterFields') || '[]');
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
    let renderedName = false, renderedPhone = false;
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
    survivors.filter(survivor => {
        if (!globalSearchTerm) return true;
        return Object.values(survivor).some(val =>
            (val ?? '').toString().toLowerCase().includes(globalSearchTerm)
        );
    }).forEach(survivor => {
        const tr = document.createElement('tr');
        let renderedNameCell = false, renderedPhoneCell = false;
        checkedFields.forEach(field => {
            if ((field === 'fname' || field === 'lname' || field === 'name') && !renderedNameCell) {
                const td = document.createElement('td');
                td.textContent = (survivor.fname || '') + ' ' + (survivor.lname || '');
                tr.appendChild(td);
                renderedNameCell = true;
            } else if ((field === 'primary_phone' || field === 'secondary_phone' || field === 'phone') && !renderedPhoneCell) {
                const td = document.createElement('td');
                td.innerHTML = (survivor.primary_phone || '') +
                    (survivor.secondary_phone ? '<br>' + survivor.secondary_phone : '');
                tr.appendChild(td);
                renderedPhoneCell = true;
            } else if (field === 'caseworker_id') {
                const td = document.createElement('td');
                td.textContent = survivor.caseworker_id || '';
                tr.appendChild(td);
            } else if (field === 'fema_id') {
                const td = document.createElement('td');
                td.textContent = survivor.fema_id || '';
                tr.appendChild(td);
            } else if (field === 'hh_size') {
                const td = document.createElement('td');
                td.innerHTML = survivor.hh_size !== null && survivor.hh_size !== undefined
                    ? `<span class="hh-size">${survivor.hh_size}</span>` : '';
                tr.appendChild(td);
            } else if (field === 'own_rent') {
                const td = document.createElement('td');
                td.textContent = survivor.own_rent == 0 ? 'Own' : 'Rent';
                tr.appendChild(td);
            } else if (!['fname', 'lname', 'primary_phone', 'secondary_phone', 'name', 'phone'].includes(field)) {
                const td = document.createElement('td');
                td.textContent = survivor[field] !== undefined ? survivor[field] : '';
                tr.appendChild(td);
            }
        });
        // Options column
        const tdOptions = document.createElement('td');
        tdOptions.className = 'options-icon';
        tdOptions.style.position = 'relative';
        tdOptions.innerHTML = `⋮
            <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                <a href="/admin/survivors/view/${survivor.id}">View</a>
                <a href="/admin/survivors/edit/${survivor.id}">Edit</a>
                <form action="/admin/survivors/delete/${survivor.id}" method="POST" style="margin: 0;">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                </form>
            </div>`;
        tr.appendChild(tdOptions);
        body.appendChild(tr);
    });

    // --- Re-bind filter and options events after header/body re-render ---
    setTimeout(() => {
        const filterBtn = document.getElementById('filter-button');
        if (filterBtn && !filterBtn._bound) {
            filterBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                const dropdown = document.getElementById('filter-dropdown');
                dropdown.classList.toggle('active');
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) menu.classList.remove('active');
                });
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
document.getElementById('save-filter-fields').addEventListener('click', function() {
    const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox'))
        .filter(cb => cb.checked)
        .map(cb => cb.getAttribute('data-field'));
    localStorage.setItem('survivorsFilterFields', JSON.stringify(checkedFields));
    this.textContent = 'Saved!';
    setTimeout(() => { this.textContent = 'Save'; }, 1000);
    document.getElementById('filter-dropdown').classList.remove('active');
    renderTable(); // Re-render table with saved fields
});

// --- Dropdown Handling ---
document.getElementById('filter-dropdown').addEventListener('click', function(event) {
    event.stopPropagation();
});
document.addEventListener('click', function (event) {
    if (!event.target.closest('#filter-button')) {
        document.getElementById('filter-dropdown').classList.remove('active');
    }
    if (!event.target.closest('.options-icon')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
    }
});

// --- Sorting Logic ---
function sortTableByField(field) {
    if (currentSortField === field) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortField = field;
        currentSortDirection = 'asc';
    }

    // Save sort status immediately when sorting
    localStorage.setItem('survivorsSortField', currentSortField);
    localStorage.setItem('survivorsSortDirection', currentSortDirection);

    sortDataArray(survivors, field, currentSortDirection);
    renderTable();
}