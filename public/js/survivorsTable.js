let survivors = [];
let globalSearchTerm = '';
let currentSortField = localStorage.getItem('survivorsSortField') || null;
let currentSortDirection = localStorage.getItem('survivorsSortDirection') || 'asc';

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
}

document.addEventListener('DOMContentLoaded', function() {
    const survivorsDataEl = document.getElementById('survivors-data');
    survivors = survivorsDataEl ? JSON.parse(survivorsDataEl.textContent || '[]') : [];

    const savedFields = JSON.parse(localStorage.getItem('survivorsFilterFields') || '[]');
    if (savedFields.length) {
        document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
            cb.checked = savedFields.includes(cb.getAttribute('data-field'));
        });
    }

    const saveBtn = document.getElementById('save-filter-fields');
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {
            const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox'))
                .filter(cb => cb.checked)
                .map(cb => cb.getAttribute('data-field'));
            localStorage.setItem('survivorsFilterFields', JSON.stringify(checkedFields));
            this.textContent = 'Saved!';
            setTimeout(() => { this.textContent = 'Save'; }, 1000);
            const filterDropdown = document.getElementById('filter-dropdown');
            if (filterDropdown) filterDropdown.classList.remove('active');
            renderTable();
        });
    }

    if (currentSortField) {
        applySavedSortToTable(survivors, currentSortField, currentSortDirection, renderTable);
    } else {
        renderTable();
    }

    document.querySelectorAll('.filter-field-checkbox').forEach(cb => cb.addEventListener('change', () => renderTable(true)));

    const filterDropdown = document.getElementById('filter-dropdown');
    if (filterDropdown) filterDropdown.addEventListener('click', e => e.stopPropagation());

    document.addEventListener('click', function (event) {
        if (!event.target.closest('#filter-button') && filterDropdown) filterDropdown.classList.remove('active');
        if (!event.target.closest('.options-icon')) closeAllDropdowns();
    });

    const searchBtn = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput.value);
            window.location.href = url.toString();
        });
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchInput.value);
                window.location.href = url.toString();
            }
        });
    }
});

function renderTable(useCheckboxes = false) {
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const fieldLabels = {
        name: 'Name',
        fname: 'Name',
        lname: 'Name',
        email: 'Email',
        phone: 'Phone',
        city: 'City',
        state: 'State',
        fdec: 'FDEC'
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
    survivors.filter(s => {
        if (!globalSearchTerm) return true;
        return Object.values(s).some(val => (val ?? '').toString().toLowerCase().includes(globalSearchTerm));
    }).forEach(s => {
        const tr = document.createElement('tr');
        checkedFields.forEach(field => {
            const td = document.createElement('td');
            if (field === 'name') td.textContent = s.name;
            else if (field === 'fdec') td.textContent = s.fdec !== undefined && s.fdec !== null ? s.fdec : (s.fdec_id ? (Array.isArray(s.fdec_id) ? s.fdec_id.join(', ') : s.fdec_id) : '');
            else td.textContent = s[field] !== undefined ? s[field] : '';
            tr.appendChild(td);
        });
        const tdOptions = document.createElement('td');
        tdOptions.className = 'options-icon';
        tdOptions.style.position = 'relative';
            // Build options differently for archived vs non-archived rows
            if (s.archived && (s.archived === 1 || s.archived === '1' || s.archived === true)) {
                tdOptions.innerHTML = `⋮
                <div class="dropdown-menu" style="right:0; left:auto; min-width:160px; position:absolute;">
                    <button class="btn-unarchive" data-id="${s.id}">Move to Inbox</button>
                   
                    <form action="/admin/survivors/delete/${s.id}" method="POST" style="margin: 0;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                    </form>
                </div>`;
            } else {
                tdOptions.innerHTML = `⋮
                <div class="dropdown-menu" style="right:0; left:auto; min-width:160px; position:absolute;">
                    <a href="/admin/survivors/view/${s.id}">View</a>
                    <a href="/admin/survivors/edit/${s.id}">Edit</a>
                    <button class="btn-archive" data-id="${s.id}">Archive</button>

                    <form action="/admin/survivors/delete/${s.id}" method="POST" style="margin: 0;">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                    </form>
                </div>`;
            }
        tr.appendChild(tdOptions);
        body.appendChild(tr);
    });

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
                    closeAllDropdowns();
                    if (!isActive) dropdown.classList.add('active');
                });
                icon._bound = true;
            }
            // Bind archive/unarchive buttons inside each options-icon
            const archiveBtn = icon.querySelector('.btn-archive');
            if (archiveBtn && !archiveBtn._bound) {
                archiveBtn.addEventListener('click', function (ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    const id = this.getAttribute('data-id');
                    if (!confirm('Move this survivor to archive? This will free any assigned TTUs/rooms.')) return;
                    fetch(`/admin/survivors/archive/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        body: JSON.stringify({})
                    }).then(r => {
                        if (r.ok) return r.json().catch(() => ({}));
                        throw new Error('Request failed');
                    }).then(j => {
                        // remove from local survivors array and re-render
                        survivors = survivors.filter(it => String(it.id) !== String(id));
                        renderTable();
                    }).catch(err => {
                        alert('Failed to archive survivor.');
                        console.error(err);
                    });
                });
                archiveBtn._bound = true;
            }

            const unarchiveBtn = icon.querySelector('.btn-unarchive');
            if (unarchiveBtn && !unarchiveBtn._bound) {
                unarchiveBtn.addEventListener('click', function (ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    const id = this.getAttribute('data-id');
                    if (!confirm('Move this survivor back to inbox?')) return;
                    fetch(`/admin/survivors/unarchive/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        body: JSON.stringify({})
                    }).then(r => {
                        if (r.ok) return r.json().catch(() => ({}));
                        throw new Error('Request failed');
                    }).then(j => {
                        // remove from local survivors array and re-render (since we're likely viewing archived list)
                        survivors = survivors.filter(it => String(it.id) !== String(id));
                        renderTable();
                    }).catch(err => {
                        alert('Failed to move survivor to inbox.');
                        console.error(err);
                    });
                });
                unarchiveBtn._bound = true;
            }
        });
    }, 0);
}

document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        renderTable(true);
    });
});

function sortTableByField(field) {
    if (currentSortField === field) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortField = field;
        currentSortDirection = 'asc';
    }
    localStorage.setItem('survivorsSortField', currentSortField);
    localStorage.setItem('survivorsSortDirection', currentSortDirection);
    sortDataArray(survivors, field, currentSortDirection);
    renderTable();
}

if (currentSortField) {
    applySavedSortToTable(survivors, currentSortField, currentSortDirection, renderTable);
} else {
    renderTable();
}