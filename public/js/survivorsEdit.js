function toggleTTURow() {
    const ttuRow = document.getElementById('ttu-row');
    const hotelRow = document.getElementById('hotel-row');
    const stateparkRow = document.getElementById('statepark-row');
    const ttuRadio = document.querySelector('input[name="location_type"][value="TTU"]');
    const hotelRadio = document.querySelector('input[name="location_type"][value="Hotel"]');
    const stateparkRadio = document.querySelector('input[name="location_type"][value="State Park"]');
    if (ttuRadio && ttuRadio.checked) {
        ttuRow.style.display = '';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = 'none';
    } else if (hotelRadio && hotelRadio.checked) {
        ttuRow.style.display = 'none';
        hotelRow.style.display = '';
        stateparkRow.style.display = 'none';
    } else if (stateparkRadio && stateparkRadio.checked) {
        ttuRow.style.display = 'none';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = '';
    } else {
        ttuRow.style.display = 'none';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleTTURow();
    document.querySelectorAll('input[name="location_type"]').forEach(function(radio) {
        radio.addEventListener('change', toggleTTURow);
    });
});

// Simple AJAX autocomplete for VIN field
document.addEventListener('DOMContentLoaded', function() {
    const vinInput = document.getElementById('vin-autocomplete');
    const suggestionsBox = document.getElementById('vin-suggestions');

    if (vinInput) {
        vinInput.addEventListener('input', function() {
            const query = this.value;
            if (query.length < 2) {
                suggestionsBox.innerHTML = '';
                return;
            }
            fetch('/admin/ttus/vin-autocomplete?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length > 0) {
                        // Create a styled dropdown (dropbox)
                        const list = document.createElement('ul');
                        list.style.position = 'absolute';
                        list.style.background = '#fff';
                        list.style.border = '1px solid #ccc';
                        list.style.borderRadius = '4px';
                        list.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                        list.style.margin = '0';
                        list.style.padding = '0';
                        list.style.listStyle = 'none'; // Remove disc
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
                                // Fetch TTU details for this VIN
                                fetch('/admin/ttus/vin-details?vin=' + encodeURIComponent(item.vin))
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data) {
                                            vinInput.value = data.vin || '';
                                            document.getElementById('lo').value = (data.lo == 0 ? '0' : '1');
                                            document.getElementById('lo_date').value = data.lo_date || '';
                                            document.getElementById('est_lo_date').value = data.est_lo_date || '';
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
});

// Hotel name auto-suggestion
document.addEventListener('DOMContentLoaded', function() {
    const hotelInput = document.querySelector('input[name="hotel_name"]');
    const suggestionsBox = document.getElementById('hotel-suggestions');
    if (!hotelInput || !suggestionsBox) return;

    hotelInput.addEventListener('input', function() {
        let query = this.value;
        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
            return;
        }
        fetch('/admin/hotels/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(function(item) {
                        const hotelName = item.name || item.hotel_name;
                        const div = document.createElement('div');
                        div.textContent = hotelName;
                        div.style.padding = '8px 12px';
                        div.style.cursor = 'pointer';
                        div.addEventListener('mousedown', function() {
                            hotelInput.value = hotelName;
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
        if (!hotelInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
});

// Room number auto-suggestion
document.addEventListener('DOMContentLoaded', function() {
    const hotelInput = document.querySelector('input[name="hotel_name"]');
    const roomSelect = document.getElementById('hotel_room_select');
    if (!hotelInput || !roomSelect) return;

    const initialSelectedRoom = window.initialSelectedRoom || '';
    let isFirstLoad = true;

    function populateRooms() {
        const hotelName = hotelInput.value;
        const selectedRoom = isFirstLoad ? initialSelectedRoom : '';
        roomSelect.innerHTML = ''; // No placeholder

        if (!hotelName) return;

        fetch('/admin/rooms/autocomplete?hotel=' + encodeURIComponent(hotelName) + '&query=')
            .then(response => response.json())
            .then(data => {
                let hasSelected = false;
                data.forEach(function(item) {
                    const roomNum = item.room_num || item.room_number;
                    const option = document.createElement('option');
                    option.value = roomNum;
                    option.textContent = roomNum;
                    if (selectedRoom && roomNum == selectedRoom) {
                        option.selected = true;
                        hasSelected = true;
                    }
                    roomSelect.appendChild(option);
                });
                // If assigned room is not in the list and it's the first load, add it as selected
                if (isFirstLoad && selectedRoom && !hasSelected) {
                    const option = document.createElement('option');
                    option.value = selectedRoom;
                    option.textContent = selectedRoom + ' (assigned)';
                    option.selected = true;
                    option.style.backgroundColor = '#007bff';
                    option.style.color = '#fff';
                    roomSelect.insertBefore(option, roomSelect.firstChild);
                }
                // If no room is selected after hotel change, select the first available room
                if (!hasSelected && roomSelect.options.length > 0 && !isFirstLoad) {
                    roomSelect.options[0].selected = true;
                }
                isFirstLoad = false;
            });
    }

    hotelInput.addEventListener('change', function() {
        populateRooms();
    });
    hotelInput.addEventListener('blur', function() {
        setTimeout(populateRooms, 200);
    });

    if (hotelInput.value) {
        populateRooms();
    }
});

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
        if (query.length < 2) {
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