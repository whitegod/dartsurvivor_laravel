function renderDetailedSearchTable(columnsArr, tableData) {
    const thead = document.getElementById('dynamic-table-header');
    const tbody = document.getElementById('dynamic-table-body');
    thead.innerHTML = '';
    tbody.innerHTML = '';

    // Render header
    const trHead = document.createElement('tr');
    columnsArr.forEach(col => {
        const th = document.createElement('th');
        th.textContent = col.label;
        trHead.appendChild(th);
    });
    thead.appendChild(trHead);

    // Render body
    tableData.forEach((row, idx) => {
        const tr = document.createElement('tr');
        columnsArr.forEach(col => {
            const td = document.createElement('td');
            if (col.key === 'index') {
                td.textContent = idx + 1;
            } else if (col.key === 'phone' && row[col.key]) {
                td.innerHTML = String(row[col.key]).replace(/\n/g, '<br>');
            } else {
                td.textContent = row[col.key] ?? '';
            }
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });
}

// Convert columns to array of {key, label}
let columnsArr = [];
if (Array.isArray(detailedSearchColumns)) {
    columnsArr = detailedSearchColumns.map(col =>
        typeof col === 'string'
            ? { key: col, label: col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }
            : col
    );
} else {
    columnsArr = Object.entries(detailedSearchColumns).map(([key, label]) => ({ key, label }));
}

// Initial render
document.addEventListener('DOMContentLoaded', function() {
    renderDetailedSearchTable(columnsArr, detailedSearchData);
});


