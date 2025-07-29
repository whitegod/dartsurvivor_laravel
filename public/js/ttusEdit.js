document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('location');
    const locationType = document.getElementById('location_type');
    const suggestionsBox = document.getElementById('location-suggestions');
    const privatesiteSection = document.getElementById('privatesite-section');
    const unitNumGroup = document.getElementById('unit_num-group');

    let currentType = locationType.value;

    locationType.addEventListener('change', function() {
        currentType = this.value;
        locationInput.value = '';
        suggestionsBox.style.display = 'none';

        // Show privatesite section if "Private Site" is selected
        if (this.value === 'Private Site' || this.selectedIndex === 3) {
            privatesiteSection.style.display = 'flex';
            locationInput.disabled = true;
        } else {
            privatesiteSection.style.display = 'none';
            locationInput.disabled = false;
        }

        // Toggle unit number visibility
        if (this.value === 'statepark') {
            unitNumGroup.style.display = '';
        } else {
            unitNumGroup.style.display = 'none';
        }
    });

    locationInput.addEventListener('input', function() {
        const query = this.value;
        if (!currentType || !query) {
            suggestionsBox.style.display = 'none';
            return;
        }
        fetch(`/admin/location-suggestions?type=${encodeURIComponent(currentType)}&query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length === 0) {
                    suggestionsBox.style.display = 'none';
                    return;
                }
                data.forEach(name => {
                    const div = document.createElement('div');
                    div.textContent = name;
                    div.className = 'autocomplete-suggestion';
                    div.onclick = function() {
                        locationInput.value = name;
                        suggestionsBox.style.display = 'none';
                    };
                    suggestionsBox.appendChild(div);
                });
                suggestionsBox.style.display = 'block';
            });
    });

    const unitNumInput = document.getElementById('unit_num');
    const unitNumSuggestions = document.getElementById('unit-num-suggestions');

    unitNumInput.addEventListener('input', function() {
        // Only fetch if statepark is selected and input is not empty
        if (locationType.value !== 'statepark' || !locationInput.value || !unitNumInput.value) {
            unitNumSuggestions.style.display = 'none';
            return;
        }
        fetch(`/admin/unit-suggestions?statepark=${encodeURIComponent(locationInput.value)}&query=${encodeURIComponent(unitNumInput.value)}`)
            .then(response => response.json())
            .then(data => {
                unitNumSuggestions.innerHTML = '';
                if (data.length === 0) {
                    unitNumSuggestions.style.display = 'none';
                    return;
                }
                data.forEach(unit => {
                    const div = document.createElement('div');
                    div.textContent = unit;
                    div.className = 'autocomplete-suggestion';
                    div.onclick = function() {
                        unitNumInput.value = unit;
                        unitNumSuggestions.style.display = 'none';
                    };
                    unitNumSuggestions.appendChild(div);
                });
                unitNumSuggestions.style.display = 'block';
            });
    });

    const purchaseOriginInput = document.getElementById('purchase_origin');
    const purchaseOriginSuggestions = document.getElementById('purchase-origin-suggestions');

    purchaseOriginInput.addEventListener('input', function() {
        const query = this.value;
        if (!query) {
            purchaseOriginSuggestions.style.display = 'none';
            return;
        }
        fetch(`/admin/vendor-suggestions?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                purchaseOriginSuggestions.innerHTML = '';
                if (data.length === 0) {
                    purchaseOriginSuggestions.style.display = 'none';
                    return;
                }
                data.forEach(vendor => {
                    const div = document.createElement('div');
                    div.textContent = vendor;
                    div.className = 'autocomplete-suggestion';
                    div.onclick = function() {
                        purchaseOriginInput.value = vendor;
                        purchaseOriginSuggestions.style.display = 'none';
                    };
                    purchaseOriginSuggestions.appendChild(div);
                });
                purchaseOriginSuggestions.style.display = 'block';
            });
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== locationInput) {
            suggestionsBox.style.display = 'none';
        }
        if (!unitNumSuggestions.contains(e.target) && e.target !== unitNumInput) {
            unitNumSuggestions.style.display = 'none';
        }
        if (!purchaseOriginSuggestions.contains(e.target) && e.target !== purchaseOriginInput) {
            purchaseOriginSuggestions.style.display = 'none';
        }
    });

    // Optionally, trigger on page load if needed
    if (locationType.value === 'Private Site' || locationType.selectedIndex === 3) {
        privatesiteSection.style.display = 'flex';
        locationInput.disabled = true;
    }

    // Disable LO Date if LO is NO (for TTU edit page)
    function toggleLODate() {
        var lo = document.getElementById('lo');
        var loDate = document.getElementById('lo_date');
        if (lo && loDate) {
            // Accept both string and numeric values for compatibility
            if (lo.value === 'NO' || lo.value === '0') {
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
});

function updateStatusWithColor() {
    var select = document.getElementById('status');
    var color = select.options[select.selectedIndex].getAttribute('data-color');
    var text = select.options[select.selectedIndex].textContent.replace(/^.\s*/, ''); // Remove emoji
    select.value = text + ' (' + color + ')';
}

