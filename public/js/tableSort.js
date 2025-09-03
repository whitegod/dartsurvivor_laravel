// Sort an array of objects by field and direction
function sortDataArray(dataArray, field, direction = 'asc') {
    return dataArray.sort((a, b) => {
        let valA = a[field] ?? '';
        let valB = b[field] ?? '';
        if (typeof valA === 'string') valA = valA.toLowerCase();
        if (typeof valB === 'string') valB = valB.toLowerCase();

        if (valA < valB) return direction === 'asc' ? -1 : 1;
        if (valA > valB) return direction === 'asc' ? 1 : -1;
        return 0;
    });
}

// Apply saved sort status to a data array and render callback
function applySavedSortToTable(dataArray, field, direction, renderCallback) {
    if (field) {
        sortDataArray(dataArray, field, direction);
    }
    renderCallback();
}

function renderSortableTableHeader({
    checkedFields,
    fieldLabels,
    currentSortField,
    currentSortDirection,
    sortCallback,
    headerRow
}) {
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
            sortCallback(field);
        });

        headerRow.appendChild(th);
    });
}