document.addEventListener('DOMContentLoaded', function() {
    // Setup all autocompletes
    setupAllVinAutocompletes();
    setupAllHotelAutocompletes();
    setupAllRoomAutocompletes();
    setupAllStateparkAutocompletes();
    setupAllUnitAutocompletes();

    // TTU Add More
    var addTTUBtn = document.getElementById('add-ttu-btn');
    if (addTTUBtn) {
        addTTUBtn.addEventListener('click', function () {
            var rowsContainer = document.getElementById('ttu-form-rows');
            var formRows = rowsContainer.getElementsByClassName('form-row');
            if (!formRows.length) return;
            var lastRow = formRows[formRows.length - 1];
            var clone = lastRow.cloneNode(true);

            Array.from(clone.querySelectorAll('input, select')).forEach(function (input) {
                if (input.type === 'text' || input.type === 'date') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
                if (input.classList.contains('vin-autocomplete')) delete input.dataset.autocompleteBound;
            });
            var vinSuggestions = clone.querySelector('.vin-suggestions');
            if (vinSuggestions) vinSuggestions.innerHTML = '';
            rowsContainer.appendChild(clone);
            setTimeout(setupAllVinAutocompletes, 0);
        });
    }

    // Hotel Add More
    var addHotelBtn = document.getElementById('add-hotel-btn');
    if (addHotelBtn) {
        addHotelBtn.addEventListener('click', function () {
            var rowsContainer = document.getElementById('hotel-form-rows');
            var hotelRows = rowsContainer.getElementsByClassName('hotel-row');
            if (!hotelRows.length) return;
            var lastRow = hotelRows[hotelRows.length - 1];
            var clone = lastRow.cloneNode(true);

            Array.from(clone.querySelectorAll('input')).forEach(function (input) {
                input.value = '';
                if (input.name === "hotel_name[]") delete input.dataset.autocompleteBound;
                if (input.name === "hotel_room[]") delete input.dataset.roomAutocompleteBound;
            });
            rowsContainer.appendChild(clone);
            setTimeout(function() {
                setupAllHotelAutocompletes();
                setupAllRoomAutocompletes();
            }, 0);
        });
    }

    // State Park Add More
    var addStateparkBtn = document.getElementById('add-statepark-btn');
    if (addStateparkBtn) {
        addStateparkBtn.addEventListener('click', function () {
            var rowsContainer = document.getElementById('statepark-row');
            var stateparkRows = rowsContainer.getElementsByClassName('statepark-row');
            if (!stateparkRows.length) return;
            var lastRow = stateparkRows[stateparkRows.length - 1];
            var clone = lastRow.cloneNode(true);

            Array.from(clone.querySelectorAll('input, select')).forEach(function (input) {
                if (input.type === 'text' || input.type === 'date') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
                if (input.name === "statepark_name[]") delete input.dataset.stateparkAutocompleteBound;
                if (input.classList.contains('unit-name-autocomplete')) delete input.dataset.unitAutocompleteBound;
            });
            var stateparkSuggestions = clone.querySelector('.statepark-suggestions');
            if (stateparkSuggestions) stateparkSuggestions.innerHTML = '';
            rowsContainer.insertBefore(clone, addStateparkBtn);
            setTimeout(function() {
                setupAllStateparkAutocompletes();
                setupAllUnitAutocompletes();
            }, 0);
        });
    }

    // Household size calculation
    [
        'group0_2', 'group3_6', 'group7_12', 'group13_17',
        'group18_21', 'group22_65', 'group65plus'
    ].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', updateHouseholdSize);
    });
    updateHouseholdSize();

    // Location type toggling
    function toggleRows() {
        const checked = Array.from(document.querySelectorAll('input[name="location_type[]"]:checked')).map(cb => cb.value);
        document.getElementById('ttu-row').classList.toggle('hidden', !checked.includes('TTU'));
        document.getElementById('hotel-row').classList.toggle('hidden', !checked.includes('Hotel'));
        document.getElementById('statepark-row').classList.toggle('hidden', !checked.includes('State Park'));
    }
    document.querySelectorAll('input[name="location_type[]"]').forEach(cb => {
        cb.addEventListener('change', toggleRows);
    });
    toggleRows();

    // LO Date toggle
    var lo = document.getElementById('lo');
    if (lo) {
        lo.addEventListener('change', toggleLODate);
        toggleLODate();
    }
});

// --- Utility Functions ---

function updateHouseholdSize() {
    const hhSizeInput = document.getElementById('hh_size');
    if (!hhSizeInput) return;
    let total = 0;
    [
        'group0_2', 'group3_6', 'group7_12', 'group13_17',
        'group18_21', 'group22_65', 'group65plus'
    ].forEach(function(id) {
        const el = document.getElementById(id);
        const val = parseInt(el && el.value ? el.value : 0, 10);
        total += isNaN(val) ? 0 : val;
    });
    hhSizeInput.value = total;
}

function toggleLODate() {
    var lo = document.getElementById('lo');
    var loDate = document.getElementById('lo_date');
    if (lo && loDate) {
        if (lo.value === '0') {
            loDate.disabled = true;
            loDate.value = '';
        } else {
            loDate.disabled = false;
        }
    }
}

// --- Autocomplete Functions ---

function setupVinAutocomplete(vinInput) {
    let suggestionsBox = vinInput.parentNode.parentNode.querySelector('.vin-suggestions');
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.className = 'vin-suggestions';
        vinInput.parentNode.parentNode.appendChild(suggestionsBox);
    }
    vinInput.addEventListener('input', function() {
        const query = this.value;
        suggestionsBox.innerHTML = '';
        if (query.length < 1) return;
        fetch('/admin/ttus/vin-autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const list = createSuggestionList(data, 'vin', vinInput, suggestionsBox, function(item) {
                        vinInput.value = item.vin;
                        suggestionsBox.innerHTML = '';
                        fetch('/admin/ttus/vin-details?vin=' + encodeURIComponent(item.vin))
                            .then(response => response.json())
                            .then(data => {
                                if (data) {
                                    vinInput.value = data.vin || '';
                                    const formRow = vinInput.closest('.form-row');
                                    if (formRow) {
                                        const loSelect = formRow.querySelector('select[name="lo[]"]');
                                        const loDateInput = formRow.querySelector('input[name="lo_date[]"]');
                                        const estLoDateInput = formRow.querySelector('input[name="est_lo_date[]"]');
                                        if (loSelect) loSelect.value = (data.lo == 0 ? '0' : '1');
                                        if (loDateInput) loDateInput.value = data.lo_date || '';
                                        if (estLoDateInput) estLoDateInput.value = data.est_lo_date || '';
                                    }
                                }
                            });
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });
    document.addEventListener('click', function(e) {
        if (!vinInput.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.innerHTML = '';
    });
}

function setupAllVinAutocompletes() {
    document.querySelectorAll('input.vin-autocomplete').forEach(function(input) {
        if (!input.dataset.autocompleteBound) {
            setupVinAutocomplete(input);
            input.dataset.autocompleteBound = "1";
        }
    });
}

function setupHotelAutocomplete(input) {
    let suggestionsBox = input.parentNode.querySelector('.hotel-suggestions');
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.className = 'hotel-suggestions';
        input.parentNode.appendChild(suggestionsBox);
    }
    input.addEventListener('input', function() {
        let query = this.value;
        suggestionsBox.innerHTML = '';
        if (query.length < 1) return;
        fetch('/admin/hotels/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const list = createSuggestionList(data, 'name', input, suggestionsBox, function(item) {
                        input.value = item.name || item.hotel_name;
                        suggestionsBox.innerHTML = '';
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.innerHTML = '';
    });
}

function setupAllHotelAutocompletes() {
    document.querySelectorAll('input[name="hotel_name[]"]').forEach(function(input) {
        if (!input.dataset.autocompleteBound) {
            setupHotelAutocomplete(input);
            input.dataset.autocompleteBound = "1";
        }
    });
}

function setupRoomAutocomplete(roomInput) {
    const formRow = roomInput.closest('.form-row');
    const hotelInput = formRow ? formRow.querySelector('input[name="hotel_name[]"]') : null;
    let suggestionsBox = formRow ? formRow.querySelector('.room-suggestions') : null;
    if (!hotelInput || !suggestionsBox) return;
    roomInput.addEventListener('input', function() {
        const hotelName = hotelInput.value;
        const query = roomInput.value;
        suggestionsBox.innerHTML = '';
        if (!hotelName || !query) return;
        fetch('/admin/rooms/autocomplete?hotel=' + encodeURIComponent(hotelName) + '&query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                const filtered = data.filter(item =>
                    item.room_num && item.room_num.toString().toLowerCase().includes(query.toLowerCase())
                );
                if (filtered.length > 0) {
                    const list = createSuggestionList(filtered, 'room_num', roomInput, suggestionsBox, function(item) {
                        roomInput.value = item.room_num;
                        suggestionsBox.innerHTML = '';
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });
    document.addEventListener('click', function(e) {
        if (!roomInput.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.innerHTML = '';
    });
}

function setupAllRoomAutocompletes() {
    document.querySelectorAll('input[name="hotel_room[]"]').forEach(function(input) {
        if (!input.dataset.roomAutocompleteBound) {
            setupRoomAutocomplete(input);
            input.dataset.roomAutocompleteBound = "1";
        }
    });
}

function setupStateparkAutocomplete(input) {
    let suggestionsBox = input.parentNode.querySelector('.statepark-suggestions');
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.className = 'statepark-suggestions';
        input.parentNode.appendChild(suggestionsBox);
    }
    input.addEventListener('input', function() {
        const query = this.value;
        suggestionsBox.innerHTML = '';
        if (query.length < 1) return;
        fetch('/admin/stateparks/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const list = createSuggestionList(data, 'name', input, suggestionsBox, function(item) {
                        input.value = item.name || item.statepark_name;
                        suggestionsBox.innerHTML = '';
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.innerHTML = '';
    });
}

function setupAllStateparkAutocompletes() {
    document.querySelectorAll('input[name="statepark_name[]"]').forEach(function(input) {
        if (!input.dataset.stateparkAutocompleteBound) {
            setupStateparkAutocomplete(input);
            input.dataset.stateparkAutocompleteBound = "1";
        }
    });
}

function setupUnitAutocomplete(unitInput) {
    const formRow = unitInput.closest('.form-row');
    const stateparkInput = formRow ? formRow.querySelector('input[name="statepark_name[]"]') : null;
    let suggestionsBox = formRow ? formRow.querySelector('.unit-suggestions') : null;
    if (!stateparkInput || !suggestionsBox) return;
    unitInput.addEventListener('input', function() {
        const stateparkName = stateparkInput.value;
        const query = unitInput.value;
        suggestionsBox.innerHTML = '';
        if (!stateparkName || !query) return;
        fetch('/admin/units/autocomplete?statepark=' + encodeURIComponent(stateparkName) + '&query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                const filtered = data.filter(item =>
                    item.unit_name && item.unit_name.toString().toLowerCase().includes(query.toLowerCase())
                );
                if (filtered.length > 0) {
                    const list = createSuggestionList(filtered, 'unit_name', unitInput, suggestionsBox, function(item) {
                        unitInput.value = item.unit_name;
                        suggestionsBox.innerHTML = '';
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });
    document.addEventListener('click', function(e) {
        if (!unitInput.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.innerHTML = '';
    });
}

function setupAllUnitAutocompletes() {
    document.querySelectorAll('input.unit-name-autocomplete').forEach(function(input) {
        if (!input.dataset.unitAutocompleteBound) {
            setupUnitAutocomplete(input);
            input.dataset.unitAutocompleteBound = "1";
        }
    });
}

// --- Helper for suggestion dropdowns ---
function createSuggestionList(data, key, input, suggestionsBox, onSelect) {
    const list = document.createElement('ul');
    list.className = 'suggestion-dropdown';

    data.forEach(function(item) {
        const li = document.createElement('li');
        li.textContent = item[key] || item.name || item.statepark_name || item.hotel_name || item.vin || item.room_num || item.unit_name;
        li.addEventListener('mouseover', function() { li.classList.add('active'); });
        li.addEventListener('mouseout', function() { li.classList.remove('active'); });
        li.addEventListener('mousedown', function() { onSelect(item); });
        list.appendChild(li);
    });
    return list;
}