// VIN auto-suggestion for multiple VIN fields

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

document.addEventListener('DOMContentLoaded', function() {
    // Initial setup
    setupAllVinAutocompletes();

    // Also re-setup after "Add More" is clicked
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
});

// // Hotel name auto-suggestion
// document.addEventListener('DOMContentLoaded', function() {
//     const hotelInput = document.querySelector('input[name="hotel_name"]');
//     const suggestionsBox = document.getElementById('hotel-suggestions');
//     if (!hotelInput || !suggestionsBox) return;

//     hotelInput.addEventListener('input', function() {
//         let query = this.value;
//         if (query.length < 1) {
//             suggestionsBox.style.display = 'none';
//             suggestionsBox.innerHTML = '';
//             return;
//         }
//         fetch('/admin/hotels/autocomplete?query=' + encodeURIComponent(query))
//             .then(response => response.json())
//             .then(data => {
//                 suggestionsBox.innerHTML = '';
//                 if (data.length > 0) {
//                     data.forEach(function(item) {
//                         const hotelName = item.name || item.hotel_name;
//                         const div = document.createElement('div');
//                         div.textContent = hotelName;
//                         div.style.padding = '8px 12px';
//                         div.style.cursor = 'pointer';
//                         div.addEventListener('mousedown', function() {
//                             hotelInput.value = hotelName;
//                             suggestionsBox.style.display = 'none';
//                             populateUnits(); // update units when park is chosen
//                         });
//                         suggestionsBox.appendChild(div);
//                     });
//                     suggestionsBox.style.display = 'block';
//                 } else {
//                     suggestionsBox.style.display = 'none';
//                 }
//             });
//     });

//     // Hide suggestions when clicking outside
//     document.addEventListener('click', function(e) {
//         if (!hotelInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
//             suggestionsBox.style.display = 'none';
//         }
//     });
// });

// Room number auto-suggestion
// document.addEventListener('DOMContentLoaded', function() {
//     const hotelInput = document.querySelector('input[name="hotel_name"]');
//     const roomSelect = document.getElementById('hotel_room_select');
//     if (!hotelInput || !roomSelect) return;

//     const initialSelectedRoom = window.initialSelectedRoom || '';
//     let isFirstLoad = true;

//     function populateRooms() {
//         const hotelName = hotelInput.value;
//         const selectedRoom = isFirstLoad ? initialSelectedRoom : '';
//         roomSelect.innerHTML = ''; // No placeholder

//         if (!hotelName) return;

//         fetch('/admin/rooms/autocomplete?hotel=' + encodeURIComponent(hotelName) + '&query=')
//             .then(response => response.json())
//             .then(data => {
//                 let hasSelected = false;
//                 data.forEach(function(item) {
//                     const roomNum = item.room_num || item.room_number;
//                     const option = document.createElement('option');
//                     option.value = roomNum;
//                     option.textContent = roomNum;
//                     if (selectedRoom && roomNum == selectedRoom) {
//                         option.selected = true;
//                         hasSelected = true;
//                     }
//                     roomSelect.appendChild(option);
//                 });
//                 // If assigned room is not in the list and it's the first load, add it as selected
//                 if (isFirstLoad && selectedRoom && !hasSelected) {
//                     const option = document.createElement('option');
//                     option.value = selectedRoom;
//                     option.textContent = selectedRoom + ' (assigned)';
//                     option.selected = true;
//                     option.style.backgroundColor = '#007bff';
//                     option.style.color = '#fff';
//                     roomSelect.insertBefore(option, roomSelect.firstChild);
//                 }
//                 // If no room is selected after hotel change, select the first available room
//                 if (!hasSelected && roomSelect.options.length > 0 && !isFirstLoad) {
//                     roomSelect.options[0].selected = true;
//                 }
//                 isFirstLoad = false;
//             });
//     }

//     hotelInput.addEventListener('change', function() {
//         populateRooms();
//     });
//     hotelInput.addEventListener('blur', function() {
//         setTimeout(populateRooms, 200);
//     });

//     if (hotelInput.value) {
//         populateRooms();
//     }
// });

// State Park name auto-suggestion and unit_name population
document.addEventListener('DOMContentLoaded', function() {
    const stateparkInput = document.querySelector('input[name="statepark_name"]');
    const suggestionsBox = document.getElementById('statepark-suggestions');
    const unitSelect = document.getElementById('unit_name_select');
    const initialSelectedUnit = window.initialSelectedUnit || '';
    let isFirstLoadUnit = true;
    let unitsLoading = false;

    function populateUnits() {
        if (unitsLoading) return;
        unitsLoading = true;

        const parkName = stateparkInput.value;
        const selectedUnit = isFirstLoadUnit ? initialSelectedUnit : '';
        unitSelect.innerHTML = '';

        if (!parkName) {
            unitsLoading = false;
            return;
        }

        fetch('/admin/units/autocomplete?statepark=' + encodeURIComponent(parkName))
            .then(response => response.json())
            .then(data => {
                let hasSelected = false;
                let foundAssigned = false;
                const seen = new Set();
                data.forEach(function(item) {
                    const unitName = item.unit_name;
                    if (seen.has(unitName)) return;
                    seen.add(unitName);
                    const option = document.createElement('option');
                    option.value = unitName;
                    option.textContent = unitName;
                    if (selectedUnit && unitName.trim().toLowerCase() == selectedUnit.trim().toLowerCase()) {
                        option.selected = true;
                        hasSelected = true;
                        foundAssigned = true;
                    }
                    unitSelect.appendChild(option);
                });
                if (isFirstLoadUnit && selectedUnit && !foundAssigned && !seen.has(selectedUnit)) {
                    const option = document.createElement('option');
                    option.value = selectedUnit;
                    option.textContent = selectedUnit + ' (assigned)';
                    option.selected = true;
                    option.style.backgroundColor = '#007bff';
                    option.style.color = '#fff';
                    unitSelect.insertBefore(option, unitSelect.firstChild);
                }
                if (!hasSelected && unitSelect.options.length > 0 && !isFirstLoadUnit) {
                    unitSelect.options[0].selected = true;
                }
                isFirstLoadUnit = false;
                unitsLoading = false;
            })
            .catch(() => { unitsLoading = false; });
    }

    if (!stateparkInput || !suggestionsBox) return;

    stateparkInput.addEventListener('input', function() {
        let query = this.value;
        if (query.length < 1) {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
            return;
        }
        fetch('/admin/stateparks/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(function(item) {
                        const parkName = item.name || item.statepark_name;
                        const div = document.createElement('div');
                        div.textContent = parkName;
                        div.style.padding = '8px 12px';
                        div.style.cursor = 'pointer';
                        div.addEventListener('mousedown', function() {
                            stateparkInput.value = parkName;
                            suggestionsBox.style.display = 'none';
                            populateUnits(); // update units when park is chosen
                        });
                        suggestionsBox.appendChild(div);
                    });
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.style.display = 'none';
                }
            });
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!stateparkInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });

    stateparkInput.addEventListener('change', function() {
        populateUnits();
    });

    if (stateparkInput.value) {
        populateUnits();
    }
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

document.addEventListener('DOMContentLoaded', function() {
    setupAllHotelAutocompletes();

    var addHotelBtn = document.getElementById('add-hotel-btn');
    if (addHotelBtn) {
        addHotelBtn.addEventListener('click', function () {
            setTimeout(setupAllHotelAutocompletes, 0);
        });
    }
});

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

document.addEventListener('DOMContentLoaded', function() {
    setupAllRoomAutocompletes();

    var addHotelBtn = document.getElementById('add-hotel-btn');
    if (addHotelBtn) {
        addHotelBtn.addEventListener('click', function () {
            setTimeout(setupAllRoomAutocompletes, 0);
        });
    }
});