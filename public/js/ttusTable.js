
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
    performSearch();
});

document.getElementById('search-input').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        performSearch();
    }
});

function performSearch() {
    const searchInput = document.getElementById('search-input').value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', searchInput);
    window.location.href = url.toString();
}

function openModal() {
    document.getElementById('addNewModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('addNewModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.addEventListener('click', function (event) {
    const modal = document.getElementById('addNewModal');
    if (event.target === modal) {
        closeModal();
    }
});

function renderTable() {
    const ttus = JSON.parse(document.getElementById('ttus-data').textContent);
    const fields = JSON.parse(document.getElementById('fields-data').textContent);
    const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);

    // Render header
    const headerRow = document.getElementById('dynamic-table-header');
    headerRow.innerHTML = '';
    checkedFields.forEach(field => {
        const th = document.createElement('th');
        // Map DB field names to display names for the table header
        if (field === 'vin') {
            th.textContent = 'VIN - Last 7';
        } else if (field === 'unit' || field === 'loc_id') {
            th.textContent = 'Unit';
        } else if (field === 'status') {
            th.textContent = 'Status (Color Code)';
        } else if (field === 'total_beds') {
            th.textContent = 'Total Beds';
        } else {
            th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
        headerRow.appendChild(th);
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
    ttus.forEach(ttu => {
        const tr = document.createElement('tr');
        checkedFields.forEach(field => {
            const td = document.createElement('td');
            if (field === 'vin') {
                td.textContent = ttu.vin ? ttu.vin.slice(-7) : '';
            } else if (field === 'unit' || field === 'loc_id') {
                // Show "Lot {unit/loc_id}" if present
                td.textContent = ttu.unit !== undefined && ttu.unit !== null
                    ? 'Lot ' + ttu.unit
                    : (ttu.loc_id !== undefined && ttu.loc_id !== null ? 'Lot ' + ttu.loc_id : '');
            } else if (field === 'status') {
                // Extract color code and status text from status string, e.g. "Demobilized (#ffd700)"
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
                    <a href="/admin/ttus/edit/${ttu.id}">Edit</a>
                    <form action="/admin/ttus/delete/${ttu.id}" method="POST" style="margin: 0;">
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

function showStatusMemo(el, text) {
    let memo = document.createElement('div');
    memo.id = 'status-memo-alert';
    memo.textContent = text;
    memo.style.position = 'fixed';
    memo.style.background = '#333';
    memo.style.color = '#fff';
    memo.style.padding = '6px 14px';
    memo.style.borderRadius = '6px';
    memo.style.fontSize = '14px';
    memo.style.zIndex = 9999;
    memo.style.pointerEvents = 'none';
    memo.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
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
    // Remove mousemove listener from all color blocks
    document.removeEventListener('mousemove', window._moveMemo);
}