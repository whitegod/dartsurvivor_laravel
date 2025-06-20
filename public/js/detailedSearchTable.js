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
    // Add filter row logic (already present)
    const addFilterBtn = document.getElementById('add-filter');
    if (addFilterBtn) {
        addFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const allFilters = document.querySelectorAll('[id^="sub-filter_"]');
            const nextIndex = allFilters.length;
            const subFilter = document.getElementById('sub-filter_0');
            const clone = subFilter.cloneNode(true);
            clone.id = 'sub-filter_' + nextIndex;
            clone.classList.add('sub-filter-clone');
            // Clear select and input
            const select = clone.querySelector('select[name="filter_field[]"]');
            if (select) select.value = '';
            const input = clone.querySelector('input[name="filter_value[]"]');
            if (input) input.value = '';
            // Enable remove button
            const removeBtn = clone.querySelector('.remove-sub-filter');
            if (removeBtn) removeBtn.disabled = false;
            // Insert after last filter row
            const lastSubFilter = allFilters[allFilters.length - 1];
            lastSubFilter.after(clone);
            attachRemoveHandlers();
        });
    }

    // Attach remove handler to all remove buttons
    function attachRemoveHandlers() {
        document.querySelectorAll('.remove-sub-filter').forEach(function(btn) {
            btn.onclick = function() {
                // Only remove if more than one filter row remains
                const allRows = document.querySelectorAll('[id^="sub-filter_"]');
                if (allRows.length > 1) {
                    btn.closest('.form-row').remove();
                }
            };
        });
    }

    // Initial attach for existing rows
    attachRemoveHandlers();
});


