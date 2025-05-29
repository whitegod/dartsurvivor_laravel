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
function renderTable() {
    const survivors = JSON.parse(document.getElementById('survivors-data').textContent);
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);

    // Render header
    const headerRow = document.getElementById('dynamic-table-header');
    headerRow.innerHTML = '';
    checkedFields.forEach(field => {
        const th = document.createElement('th');
        // Merge fname and lname into "Name" column
        if (field === 'fname' || field === 'lname') {
            if (!checkedFields.includes('name')) {
                th.textContent = 'Name';
                headerRow.appendChild(th);
            }
        } else if (field === 'name') {
            th.textContent = 'Name';
            headerRow.appendChild(th);
        } else if (field === 'primary_phone' || field === 'secondary_phone') {
            if (!checkedFields.includes('phone')) {
                th.textContent = 'Phone';
                headerRow.appendChild(th);
            }
        } else if (field === 'phone') {
            th.textContent = 'Phone';
            headerRow.appendChild(th);
        } else if (field === 'fema_id') {
            th.textContent = 'FEMA-ID';
            headerRow.appendChild(th);
        } else if (field === 'hh_size') {
            th.textContent = 'HH Size';
            headerRow.appendChild(th);
        } else if (field === 'own_rent') {
            th.textContent = 'Own/Rent';
            headerRow.appendChild(th);
        } else {
            th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            headerRow.appendChild(th);
        }
    });
    // Add options/filter column
    const thOptions = document.createElement('th');
    thOptions.style.position = 'relative';
    thOptions.innerHTML = `<button id="filter-button" style="background: none; border: none; cursor: pointer; padding: 0; vertical-align: middle;">
        <i class='fa fa-filter'></i>
    </button>`;
    headerRow.appendChild(thOptions);

    // Render body
    const body = document.getElementById('dynamic-table-body');
    body.innerHTML = '';
    survivors.forEach(survivor => {
        const tr = document.createElement('tr');
        let skipName = false;
        let skipPhone = false;
        checkedFields.forEach(field => {
            // Merge fname and lname into "Name" column
            if ((field === 'fname' || field === 'lname') && !skipName) {
                const td = document.createElement('td');
                td.textContent = (survivor.fname || '') + ' ' + (survivor.lname || '');
                tr.appendChild(td);
                skipName = true;
            } else if (field === 'name') {
                const td = document.createElement('td');
                td.textContent = (survivor.fname || '') + ' ' + (survivor.lname || '');
                tr.appendChild(td);
            } else if ((field === 'primary_phone' || field === 'secondary_phone') && !skipPhone) {
                const td = document.createElement('td');
                td.innerHTML = (survivor.primary_phone || '') +
                    (survivor.secondary_phone ? '<br>' + survivor.secondary_phone : '');
                tr.appendChild(td);
                skipPhone = true;
            } else if (field === 'phone') {
                const td = document.createElement('td');
                td.innerHTML = (survivor.primary_phone || '') +
                    (survivor.secondary_phone ? '<br>' + survivor.secondary_phone : '');
                tr.appendChild(td);
            } else if (field === 'hh_size') {
                const td = document.createElement('td');
                if (survivor['hh_size'] === null || survivor['hh_size'] === undefined) {
                    td.innerHTML = '';
                } else {
                    td.innerHTML = `<span class="hh-size">${survivor['hh_size']}</span>`;
                }
                tr.appendChild(td);
            } else if (field === 'own_rent') {
                const td = document.createElement('td');
                td.textContent = survivor['own_rent'] == 0 ? 'Own' : 'Rent';
                tr.appendChild(td);
            } else if (field !== 'fname' && field !== 'lname' && field !== 'primary_phone' && field !== 'secondary_phone') {
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
                <a href="/admin/survivors/edit/${survivor.id}">Edit</a>
                <form action="/admin/survivors/delete/${survivor.id}" method="POST" style="margin: 0;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                </form>
            </div>`;
        tr.appendChild(tdOptions);
        body.appendChild(tr);
    });

    // Re-bind filter button event after header re-render
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

        // Re-bind 3-dots options-icon click events after table re-render
        document.querySelectorAll('.options-icon').forEach(icon => {
            if (!icon._bound) {
                icon.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const dropdown = this.querySelector('.dropdown-menu');
                    const isActive = dropdown.classList.contains('active');
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                    if (!isActive) {
                        dropdown.classList.add('active');
                    }
                });
                icon._bound = true;
            }
        });
    }, 0);
}

// Initial render
renderTable();

// Update table on checkbox change
document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
    cb.addEventListener('change', renderTable);
});

// Hide all dropdown menus when clicking outside
document.addEventListener('click', function (event) {
    if (!event.target.closest('#filter-button')) {
        document.getElementById('filter-dropdown').classList.remove('active');
    }
    // Hide all row options dropdowns if clicking outside .options-icon
    if (!event.target.closest('.options-icon')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
    }
});