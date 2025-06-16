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

function renderDetailedSearchTable(columns, data) {
    const thead = document.getElementById('dynamic-table-header');
    const tbody = document.getElementById('dynamic-table-body');
    thead.innerHTML = '';
    tbody.innerHTML = '';

    // Render header
    const trHead = document.createElement('tr');
    columns.forEach(col => {
        const th = document.createElement('th');
        th.textContent = col.label || col;
        trHead.appendChild(th);
    });
    // Add filter column
    const filterTh = document.createElement('th');
    filterTh.innerHTML = `
        <span class="filter-icon" style="cursor:pointer;" onclick="toggleGlobalFilter(this)">
            <i class="fa fa-filter"></i>
        </span>
        <div class="filter-dropdown" style="display:none; position:absolute; z-index:10; background:#fff; border:1px solid #ddd; padding:8px;">
            <input type="text" class="table-input" placeholder="Global Filter" oninput="applyGlobalFilter(this.value)">
        </div>
    `;
    filterTh.style.position = 'relative';
    trHead.appendChild(filterTh);
    thead.appendChild(trHead);

    // Render body
    data.forEach(row => {
        const tr = document.createElement('tr');
        columns.forEach(col => {
            const key = col.key || col;
            const td = document.createElement('td');
            if (key === 'phone' && row[key]) {
                td.innerHTML = row[key].replace(/\n/g, '<br>');
            } else {
                td.textContent = row[key] ?? '';
            }
            tr.appendChild(td);
        });
        tr.appendChild(document.createElement('td')); // filter column cell
        tbody.appendChild(tr);
    });
}

// Convert columns to array of {key, label}
let columnsArr = [];
if (Array.isArray(detailedSearchColumns)) {
    columnsArr = detailedSearchColumns.map(col => typeof col === 'string'
        ? {key: col, label: col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
        : col
    );
} else {
    columnsArr = Object.entries(detailedSearchColumns).map(([key, label]) => ({key, label}));
}

renderDetailedSearchTable(columnsArr, detailedSearchData);

function toggleGlobalFilter(icon) {
    const dropdown = icon.parentElement.querySelector('.filter-dropdown');
    document.querySelectorAll('.filter-dropdown').forEach(el => { if (el !== dropdown) el.style.display = 'none'; });
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
function applyGlobalFilter(value) {
    document.querySelectorAll('#detailed-search-tbody tr').forEach(row => {
        row.style.display = [...row.children].slice(0, -1).some(td =>
            td.textContent.toLowerCase().includes(value.toLowerCase())
        ) ? '' : 'none';
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.filter-dropdown') && !e.target.closest('.filter-icon')) {
        document.querySelectorAll('.filter-dropdown').forEach(el => el.style.display = 'none');
    }
});

function renderColumnVisibilityFilter(allColumns, visibleColumns) {
    return `
        <span class="filter-icon" style="cursor:pointer;" onclick="toggleColumnFilter(this)">
            <i class="fa fa-filter"></i>
        </span>
        <div class="filter-dropdown" id="column-visibility-dropdown" style="display:none; position:absolute; z-index:10; background:#fff; border:1px solid #ddd; padding:8px; max-height:200px; overflow-y:auto;">
            ${allColumns.map(col => `
                <div>
                    <input type="checkbox" id="col-filter-${col.key}" value="${col.key}" ${visibleColumns.includes(col.key) ? 'checked' : ''} onchange="toggleColumn('${col.key}')">
                    <label for="col-filter-${col.key}">${col.label}</label>
                </div>
            `).join('')}
        </div>
    `;
}