let ttus = [];
let currentSortField = localStorage.getItem('ttusSortField') || null;
let currentSortDirection = localStorage.getItem('ttusSortDirection') || 'asc';
let globalSearchTerm = '';

document.addEventListener('DOMContentLoaded', function() {
    // Restore filter fields from localStorage
    const savedFields = JSON.parse(localStorage.getItem('ttusFilterFields') || '[]');
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
        localStorage.setItem('ttusFilterFields', JSON.stringify(checkedFields));
        this.textContent = 'Saved!';
        setTimeout(() => { this.textContent = 'Save'; }, 1000);
        document.getElementById('filter-dropdown').classList.remove('active');
    });

    // Initialize ttus before sorting or rendering
    ttus = JSON.parse(document.getElementById('ttus-data').textContent);

    // Initial table render with sort
    if (currentSortField) {
        applySavedSortToTable(ttus, currentSortField, currentSortDirection, renderTable);
    } else {
        renderTable();
    }
});

// --- Dropdown and Modal Handling ---
function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
}
document.querySelectorAll('.options-icon').forEach(icon => {
    icon.addEventListener('click', function (e) {
        e.stopPropagation();
        const dropdown = this.querySelector('.dropdown-menu');
        const isActive = dropdown.classList.contains('active');
        closeAllDropdowns();
        if (!isActive) dropdown.classList.add('active');
    });
});
document.addEventListener('click', function (event) {
    if (!event.target.closest('.options-icon')) closeAllDropdowns();
    if (!event.target.closest('#filter-button')) document.getElementById('filter-dropdown').classList.remove('active');
});
document.getElementById('filter-dropdown').addEventListener('click', function(event) {
    event.stopPropagation();
});
function openModal() { document.getElementById('addNewModal').style.display = 'flex'; }
function closeModal() { document.getElementById('addNewModal').style.display = 'none'; }
window.addEventListener('click', function (event) {
    const modal = document.getElementById('addNewModal');
    if (event.target === modal) closeModal();
});

// --- Search Handling ---
document.getElementById('search-button').addEventListener('click', function (event) {
    event.preventDefault();
    globalSearchTerm = document.getElementById('search-input').value.trim().toLowerCase();
    renderTable();
});
document.getElementById('search-input').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        globalSearchTerm = this.value.trim().toLowerCase();
        renderTable();
    }
});

// --- Table Rendering ---
function renderTable(useCheckboxes = false) {
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const fieldLabels = {
        vin: 'VIN - Last 7',
        survivor_id: 'Survivor',
        unit: 'Unit',
        loc_id: 'Unit',
        status: 'Status (Color Code)',
        total_beds: 'Total Beds'
    };

    let checkedFields;
    const savedFields = JSON.parse(localStorage.getItem('ttusFilterFields') || '[]');
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
    checkedFields.forEach(field => {
        const th = document.createElement('th');
        th.style.position = 'relative';
        th.style.cursor = 'pointer';
        th.dataset.field = field;

        // Label
        const labelSpan = document.createElement('span');
        labelSpan.textContent = fieldLabels[field] || field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        // Arrow
        const arrowSpan = document.createElement('span');
        arrowSpan.className = 'sort-arrow';
        if (currentSortField === field) {
            arrowSpan.innerHTML = currentSortDirection === 'asc' ? '&#9650;' : '&#9660;';
            arrowSpan.style.opacity = '1';
        } else {
            arrowSpan.innerHTML = '&#9650;';
            arrowSpan.style.opacity = '0.3';
        }
        arrowSpan.style.position = 'absolute';
        arrowSpan.style.right = '8px';
        arrowSpan.style.top = '50%';
        arrowSpan.style.transform = 'translateY(-50%)';

        th.appendChild(labelSpan);
        th.appendChild(arrowSpan);

        th.addEventListener('click', function() {
            sortTableByField(field);
        });

        headerRow.appendChild(th);
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
    ttus.filter(ttu => {
        if (!globalSearchTerm) return true;
        return Object.values(ttu).some(val =>
            (val ?? '').toString().toLowerCase().includes(globalSearchTerm)
        );
    }).forEach(ttu => {
        const tr = document.createElement('tr');
        checkedFields.forEach(field => {
            const td = document.createElement('td');
            if (field === 'vin') {
                td.textContent = ttu.vin ? ttu.vin.slice(-7) : '';
            } else if (field === 'unit' || field === 'loc_id') {
                td.textContent = ttu.unit !== undefined && ttu.unit !== null
                    ? 'Lot ' + ttu.unit
                    : (ttu.loc_id !== undefined && ttu.loc_id !== null ? 'Lot ' + ttu.loc_id : '');
            } else if (field === 'status') {
                let colorMatch = ttu.status && ttu.status.match(/\(#([0-9a-fA-F]{6})\)/);
                let color = colorMatch ? `#${colorMatch[1]}` : '#ccc';
                let statusText = ttu.status ? ttu.status.replace(/\s*\(#([0-9a-fA-F]{6})\)\s*$/, '') : '';
                td.innerHTML = `<span 
                        style="display:inline-block;width:18px;height:18px;border-radius:4px;background:${color};cursor:pointer;" 
                        onmouseenter="showStatusMemo(this, '${statusText.replace(/'/g, "\\'")}')" 
                        onmouseleave="hideStatusMemo()"
                    ></span>`;
            } else if (field === 'total_beds') {
                td.innerHTML = `<span class="total-beds">${ttu.total_beds !== undefined ? ttu.total_beds : ''}</span>`;
            } else {
                td.textContent = ttu[field] !== undefined ? ttu[field] : '';
            }
            tr.appendChild(td);
        });
        // Options column
        const tdOptions = document.createElement('td');
        tdOptions.className = 'options-icon';
        tdOptions.style.position = 'relative';
        tdOptions.innerHTML = `⋮
            <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                <a href="/admin/ttus/view/${ttu.id}">View</a>
                <a href="/admin/ttus/edit/${ttu.id}">Edit</a>
                <form action="/admin/ttus/delete/${ttu.id}" method="POST" style="margin: 0;">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <button class="btn-delete" type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                </form>
            </div>`;
        tr.appendChild(tdOptions);
        body.appendChild(tr);
    });

    // Re-bind filter and options events after header/body re-render
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
        });
    }, 0);
}

// --- Checkbox Logic ---
document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        renderTable(true);
    });
});

// --- Status Memo Tooltip ---
function showStatusMemo(el, text) {
    let memo = document.createElement('div');
    memo.id = 'status-memo-alert';
    memo.textContent = text;
    document.body.appendChild(memo);
    function move(e) {
        memo.style.left = (e.clientX + 12) + 'px';
        memo.style.top = (e.clientY + 12) + 'px';
    }
    el._moveMemo = move;
    document.addEventListener('mousemove', move);
}
function hideStatusMemo() {
    let memo = document.getElementById('status-memo-alert');
    if (memo) memo.remove();
    document.removeEventListener('mousemove', window._moveMemo);
}

// --- Sorting Logic ---
function sortTableByField(field) {
    if (currentSortField === field) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortField = field;
        currentSortDirection = 'asc';
    }

    // Save sort status immediately when sorting
    localStorage.setItem('ttusSortField', currentSortField);
    localStorage.setItem('ttusSortDirection', currentSortDirection);

    sortDataArray(ttus, field, currentSortDirection);
    renderTable();
}

// --- Initial Table Render ---
if (currentSortField) {
    applySavedSortToTable(ttus, currentSortField, currentSortDirection, renderTable);
} else {
    renderTable();
}