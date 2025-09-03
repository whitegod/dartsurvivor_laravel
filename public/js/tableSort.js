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