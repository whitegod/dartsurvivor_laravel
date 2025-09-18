let survivors = [];
let currentSortField = localStorage.getItem('survivorsSortField') || null;
let currentSortDirection = localStorage.getItem('survivorsSortDirection') || 'asc';
let globalSearchTerm = '';

function renderTable(useCheckboxes = false) {
    try{ console.time && console.time('survivors:renderTable'); }catch(e){}
    const fieldLabels = {
        fname: 'Name', lname: 'Name', name: 'Name',
        primary_phone: 'Phone', secondary_phone: 'Phone', phone: 'Phone',
        fema_id: 'FEMA-ID', hh_size: 'HH Size', own_rent: 'Own/Rent',
        caseworker_id: 'Caseworker',
        fdec: 'FDEC', fdec_id: 'FDEC'
    };

    const savedFields = JSON.parse(localStorage.getItem('survivorsFilterFields') || '[]');
    const checkedFields = !useCheckboxes && savedFields.length
        ? savedFields
        : Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);

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
    thOptions.innerHTML = `<button id="filter-button" style="background:none;border:none;cursor:pointer;padding:0;vertical-align:middle;"><i class='fa fa-filter'></i></button>`;
    headerRow.appendChild(thOptions);

    // Body
    const body = document.getElementById('dynamic-table-body');
    body.innerHTML = '';
    const frag = document.createDocumentFragment();

    const searchTerm = (globalSearchTerm || '').toLowerCase();
    const filtered = survivors.filter(survivor => {
        if (!searchTerm) return true;
        return Object.values(survivor).some(val => {
            if (val === null || val === undefined) return false;
            if (Array.isArray(val)) val = val.join(' ');
            return String(val).toLowerCase().includes(searchTerm);
        });
    });

    filtered.forEach(survivor => {
        const tr = document.createElement('tr');
        let renderedNameCell = false, renderedPhoneCell = false;

        checkedFields.forEach(field => {
            if ((field === 'fname' || field === 'lname' || field === 'name') && !renderedNameCell) {
                const td = document.createElement('td');
                td.textContent = `${survivor.fname || ''} ${survivor.lname || ''}`.trim();
                tr.appendChild(td);
                renderedNameCell = true;
            } else if ((field === 'primary_phone' || field === 'secondary_phone' || field === 'phone') && !renderedPhoneCell) {
                const td = document.createElement('td');
                td.innerHTML = (survivor.primary_phone || '') + (survivor.secondary_phone ? `<br>${survivor.secondary_phone}` : '');
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
                td.innerHTML = survivor.hh_size != null ? `<span class="hh-size">${survivor.hh_size}</span>` : '';
                tr.appendChild(td);
            } else if (field === 'own_rent') {
                const td = document.createElement('td');
                td.textContent = survivor.own_rent == 0 ? 'Own' : 'Rent';
                tr.appendChild(td);
            } else if (field === 'fdec' || field === 'fdec_id') {
                const td = document.createElement('td');
                let display = '';

                if (Array.isArray(survivor.fdec_numbers) && survivor.fdec_numbers.length) {
                    display = survivor.fdec_numbers.join(', ');
                } else {
                    const raw = survivor.fdec || survivor.fdec_id || '';
                    let ids = [];
                    try {
                        ids = typeof raw === 'string' ? JSON.parse(raw) : raw;
                        if (!Array.isArray(ids)) ids = [ids];
                    } catch (e) {
                        ids = String(raw).split(',').map(s => s.trim()).filter(Boolean);
                    }
                    if (window.fdecMap && typeof window.fdecMap === 'object') {
                        display = ids.map(id => (window.fdecMap[String(id)] || String(id))).join(', ');
                    } else {
                        display = ids.map(String).join(', ');
                    }
                }

                td.textContent = display || '';
                tr.appendChild(td);
            } else {
                if (!['fname', 'lname', 'primary_phone', 'secondary_phone', 'name', 'phone'].includes(field)) {
                    const td = document.createElement('td');
                    td.textContent = survivor[field] !== undefined ? survivor[field] : '';
                    tr.appendChild(td);
                }
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
                <form action="/admin/survivors/delete/${survivor.id}" method="POST" style="margin:0;">
                    <input type="hidden" name="_token" value="${window.csrfToken || ''}">
                    <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                </form>
            </div>`;
        tr.appendChild(tdOptions);

        frag.appendChild(tr);
    });

    // Append all rows in one operation to minimize reflows
    // For large tables, appending all at once can block the UI. Append in batches to keep UI responsive.
    (function appendInBatches(container, fragment, batchSize){
        try{ if(typeof requestAnimationFrame === 'undefined'){ container.appendChild(fragment); return; } }catch(e){ container.appendChild(fragment); return; }
        // Convert fragment children to an array for batching
        var nodes = Array.from(fragment.childNodes || []);
        var idx = 0;
        function step(){
            var end = Math.min(idx + batchSize, nodes.length);
            for(; idx < end; idx++){ container.appendChild(nodes[idx]); }
            if(idx < nodes.length) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    })(body, frag, 200);

    // Bind UI behaviors
    setTimeout(() => {
        const filterBtn = document.getElementById('filter-button');
        const filterDropdown = document.getElementById('filter-dropdown');
        if (filterBtn && filterDropdown && !filterBtn._bound) {
            filterBtn.addEventListener('click', e => { e.stopPropagation(); filterDropdown.classList.toggle('active'); });
            filterBtn._bound = true;
        }

        document.querySelectorAll('.options-icon').forEach(icon => {
            if (!icon._bound) {
                icon.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const dropdown = this.querySelector('.dropdown-menu');
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                    dropdown.classList.toggle('active');
                });
                icon._bound = true;
            }
        });
    }, 0);
    try{ console.timeEnd && console.timeEnd('survivors:renderTable'); }catch(e){}
}

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

document.addEventListener('DOMContentLoaded', function () {
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

    const survivorsDataEl = document.getElementById('survivors-data');
    survivors = survivorsDataEl ? JSON.parse(survivorsDataEl.textContent || '[]') : [];

    if (currentSortField) {
        applySavedSortToTable(survivors, currentSortField, currentSortDirection, renderTable);
    } else {
        renderTable();
    }

    document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
        cb.addEventListener('change', () => renderTable(true));
    });

    const filterDropdown = document.getElementById('filter-dropdown');
    if (filterDropdown) {
        filterDropdown.addEventListener('click', e => e.stopPropagation());
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('#filter-button') && filterDropdown) filterDropdown.classList.remove('active');
        if (!event.target.closest('.options-icon')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        }
    });

    const searchBtn = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput.value);
            window.location.href = url.toString();
        });
        let survivorsSearchDebounce = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(survivorsSearchDebounce);
            survivorsSearchDebounce = setTimeout(() => {
                globalSearchTerm = this.value.trim().toLowerCase();
                renderTable();
            }, 200);
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