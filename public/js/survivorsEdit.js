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

            // Clear input/select values in the clone
            Array.from(clone.querySelectorAll('input, select')).forEach(function (input) {
                if (input.type === 'text' || input.type === 'date') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
            });

            // Remove any existing suggestion box in the clone
            var vinSuggestions = clone.querySelector('.vin-suggestions');
            if (vinSuggestions) vinSuggestions.innerHTML = '';

            // Remove autocompleteBound so it can be re-bound
            var vinInput = clone.querySelector('input.vin-autocomplete');
            if (vinInput) delete vinInput.dataset.autocompleteBound;

            rowsContainer.appendChild(clone);

            // Re-bind auto-suggestion for all VIN fields (including the new one)
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

            // Clear input values in the clone
            Array.from(clone.querySelectorAll('input')).forEach(function (input) {
                input.value = '';
                if (input.name === "hotel_name[]") {
                    delete input.dataset.autocompleteBound;
                }
                if (input.name === "hotel_room[]") {
                    delete input.dataset.roomAutocompleteBound;
                }
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

            // Clear input/select values in the clone
            Array.from(clone.querySelectorAll('input, select')).forEach(function (input) {
                if (input.type === 'text' || input.type === 'date') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
                if (input.name === "statepark_name[]") {
                    delete input.dataset.stateparkAutocompleteBound;
                }
                if (input.classList.contains('unit-name-autocomplete')) {
                    delete input.dataset.unitAutocompleteBound;
                }
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

    // var addHotelBtn = document.getElementById('add-hotel-btn');
    // if (addHotelBtn) {
    //     addHotelBtn.addEventListener('click', function () {
    //         setTimeout(setupAllHotelAutocompletes, 0);
    //     });
    // }
});

function updateHouseholdSize() {
    const hhSizeInput = document.getElementById('hh_size');
    if (!hhSizeInput) return;
    let total = 0;
    const fields = [
        'group0_2', 'group3_6', 'group7_12', 'group13_17',
        'group18_21', 'group22_65', 'group65plus'
    ];
    fields.forEach(function(id) {
        const el = document.getElementById(id);
        const val = parseInt(el && el.value ? el.value : 0, 10);
        total += isNaN(val) ? 0 : val;
    });
    hhSizeInput.value = total;
}

document.addEventListener('DOMContentLoaded', function() {
    [
        'group0_2', 'group3_6', 'group7_12', 'group13_17',
        'group18_21', 'group22_65', 'group65plus'
    ].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateHouseholdSize);
        }
    });
    updateHouseholdSize();
});

// Disable LO Date if LO is NO
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

var lo = document.getElementById('lo');
if (lo) {
    lo.addEventListener('change', toggleLODate);
    toggleLODate();
}

document.addEventListener('DOMContentLoaded', function() {
    function toggleRows() {
        const checked = Array.from(document.querySelectorAll('input[name="location_type[]"]:checked')).map(cb => cb.value);
        document.getElementById('ttu-row').classList.toggle('hidden', !checked.includes('TTU'));
        document.getElementById('hotel-row').classList.toggle('hidden', !checked.includes('Hotel'));
        document.getElementById('statepark-row').classList.toggle('hidden', !checked.includes('State Park'));
    }
    document.querySelectorAll('input[name="location_type[]"]').forEach(cb => {
        cb.addEventListener('change', toggleRows);
    });
    toggleRows(); // Initial state
});

function setupVinAutocomplete(vinInput) {
    // Create or find the suggestions box next to this input
    let suggestionsBox = vinInput.parentNode.parentNode.querySelector('.vin-suggestions');
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.className = 'vin-suggestions';
        suggestionsBox.style.position = 'relative';
        suggestionsBox.style.zIndex = 10;
        vinInput.parentNode.parentNode.appendChild(suggestionsBox);
    }

    vinInput.addEventListener('input', function() {
        const query = this.value;
        if (query.length < 1) {
            suggestionsBox.innerHTML = '';
            return;
        }
        fetch('/admin/ttus/vin-autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    const list = document.createElement('ul');
                    list.style.position = 'absolute';
                    list.style.background = '#fff';
                    list.style.border = '1px solid #ccc';
                    list.style.borderRadius = '4px';
                    list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    list.style.margin = '0';
                    list.style.padding = '0';
                    list.style.listStyle = 'none';
                    list.style.maxHeight = '180px';
                    list.style.overflowY = 'auto';
                    list.style.minWidth = vinInput.offsetWidth + 'px';

                    data.forEach(function(item) {
                        const li = document.createElement('li');
                        li.textContent = item.vin;
                        li.style.padding = '8px 12px';
                        li.style.cursor = 'pointer';
                        li.style.transition = 'background 0.2s';
                        li.addEventListener('mouseover', function() {
                            li.style.background = '#f0f4ff';
                        });
                        li.addEventListener('mouseout', function() {
                            li.style.background = '';
                        });
                        li.addEventListener('mousedown', function() {
                            vinInput.value = item.vin;
                            suggestionsBox.innerHTML = '';
                            // Fetch TTU details for this VIN and fill only the fields in the same row
                            fetch('/admin/ttus/vin-details?vin=' + encodeURIComponent(item.vin))
                                .then(response => response.json())
                                .then(data => {
                                    if (data) {
                                        vinInput.value = data.vin || '';
                                        // Find the closest .form-row
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
                        list.appendChild(li);
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!vinInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
        }
    });
}

function setupAllVinAutocompletes() {
    document.querySelectorAll('input.vin-autocomplete').forEach(function(input) {
        // Prevent double-binding
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
        suggestionsBox.style.position = 'relative';
        suggestionsBox.style.zIndex = 10;
        input.parentNode.appendChild(suggestionsBox);
    }

    input.addEventListener('input', function() {
        let query = this.value;
        if (query.length < 1) {
            suggestionsBox.innerHTML = '';
            return;
        }
        fetch('/admin/hotels/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    const list = document.createElement('ul');
                    list.style.position = 'absolute';
                    list.style.background = '#fff';
                    list.style.border = '1px solid #ccc';
                    list.style.borderRadius = '4px';
                    list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    list.style.margin = '0';
                    list.style.padding = '0';
                    list.style.listStyle = 'none';
                    list.style.maxHeight = '180px';
                    list.style.overflowY = 'auto';
                    list.style.minWidth = input.offsetWidth + 'px';

                    data.forEach(function(item) {
                        const li = document.createElement('li');
                        const hotelName = item.name || item.hotel_name;
                        li.textContent = hotelName;
                        li.style.padding = '8px 12px';
                        li.style.cursor = 'pointer';
                        li.style.transition = 'background 0.2s';
                        li.addEventListener('mouseover', function() {
                            li.style.background = '#f0f4ff';
                        });
                        li.addEventListener('mouseout', function() {
                            li.style.background = '';
                        });
                        li.addEventListener('mousedown', function() {
                            input.value = hotelName;
                            suggestionsBox.innerHTML = '';
                        });
                        list.appendChild(li);
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
        }
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
    // Find the corresponding hotel name input in the same row
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
                if (data.length > 0) {
                    const list = document.createElement('ul');
                    list.style.position = 'absolute';
                    list.style.background = '#fff';
                    list.style.border = '1px solid #ccc';
                    list.style.borderRadius = '4px';
                    list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    list.style.margin = '0';
                    list.style.padding = '0';
                    list.style.listStyle = 'none';
                    list.style.maxHeight = '180px';
                    list.style.overflowY = 'auto';
                    list.style.minWidth = roomInput.offsetWidth + 'px';

                    data.forEach(function(item) {
                        const li = document.createElement('li');
                        li.textContent = item.room_num;
                        li.style.padding = '8px 12px';
                        li.style.cursor = 'pointer';
                        li.style.transition = 'background 0.2s';
                        li.addEventListener('mouseover', function() {
                            li.style.background = '#f0f4ff';
                        });
                        li.addEventListener('mouseout', function() {
                            li.style.background = '';
                        });
                        li.addEventListener('mousedown', function() {
                            roomInput.value = item.room_num;
                            suggestionsBox.innerHTML = '';
                        });
                        list.appendChild(li);
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!roomInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
        }
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
        suggestionsBox.style.position = 'absolute';
        suggestionsBox.style.top = '100%';
        suggestionsBox.style.left = '0';
        suggestionsBox.style.zIndex = '1000';
        suggestionsBox.style.backgroundColor = '#fff';
        suggestionsBox.style.border = '1px solid #ddd';
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
                    const list = document.createElement('ul');
                    list.style.position = 'absolute';
                    list.style.background = '#fff';
                    list.style.border = '1px solid #ccc';
                    list.style.borderRadius = '4px';
                    list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    list.style.margin = '0';
                    list.style.padding = '0';
                    list.style.listStyle = 'none';
                    list.style.maxHeight = '180px';
                    list.style.overflowY = 'auto';
                    list.style.minWidth = input.offsetWidth + 'px';

                    data.forEach(function(item) {
                        const li = document.createElement('li');
                        li.textContent = item.name || item.statepark_name;
                        li.style.padding = '8px 12px';
                        li.style.cursor = 'pointer';
                        li.style.transition = 'background 0.2s';
                        li.addEventListener('mouseover', function() {
                            li.style.background = '#f0f4ff';
                        });
                        li.addEventListener('mouseout', function() {
                            li.style.background = '';
                        });
                        li.addEventListener('mousedown', function() {
                            input.value = li.textContent;
                            suggestionsBox.innerHTML = '';
                        });
                        list.appendChild(li);
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
        }
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
                if (data.length > 0) {
                    const list = document.createElement('ul');
                    list.style.position = 'absolute';
                    list.style.background = '#fff';
                    list.style.border = '1px solid #ccc';
                    list.style.borderRadius = '4px';
                    list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    list.style.margin = '0';
                    list.style.padding = '0';
                    list.style.listStyle = 'none';
                    list.style.maxHeight = '180px';
                    list.style.overflowY = 'auto';
                    list.style.minWidth = unitInput.offsetWidth + 'px';

                    data.forEach(function(item) {
                        const li = document.createElement('li');
                        li.textContent = item.unit_name;
                        li.style.padding = '8px 12px';
                        li.style.cursor = 'pointer';
                        li.style.transition = 'background 0.2s';
                        li.addEventListener('mouseover', function() {
                            li.style.background = '#f0f4ff';
                        });
                        li.addEventListener('mouseout', function() {
                            li.style.background = '';
                        });
                        li.addEventListener('mousedown', function() {
                            unitInput.value = item.unit_name;
                            suggestionsBox.innerHTML = '';
                        });
                        list.appendChild(li);
                    });
                    suggestionsBox.appendChild(list);
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!unitInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
        }
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