document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('location');
    const locationType = document.getElementById('location_type');
    const locationSuggestionsBox = document.getElementById('location-suggestions');
    const privatesiteSection = document.getElementById('privatesite-section');
    const unitNumGroup = document.getElementById('unit_num-group');

    let currentType = locationType.value;

    locationType.addEventListener('change', function() {
        currentType = this.value;
        locationInput.value = '';
        locationSuggestionsBox.style.display = 'none';

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
            locationSuggestionsBox.style.display = 'none';
            return;
        }
        fetch(`/admin/location-suggestions?type=${encodeURIComponent(currentType)}&query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                locationSuggestionsBox.innerHTML = '';
                if (data.length === 0) {
                    locationSuggestionsBox.style.display = 'none';
                    return;
                }
                data.forEach(name => {
                    const div = document.createElement('div');
                    div.textContent = name;
                    div.className = 'autocomplete-suggestion';
                    div.onclick = function() {
                        locationInput.value = name;
                        locationSuggestionsBox.style.display = 'none';
                    };
                    locationSuggestionsBox.appendChild(div);
                });
                locationSuggestionsBox.style.display = 'block';
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
        if (!locationSuggestionsBox.contains(e.target) && e.target !== locationInput) {
            locationSuggestionsBox.style.display = 'none';
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


    const nameInput = document.getElementById('survivor_name');
    const nameSuggestionsBox = document.getElementById('survivor-name-suggestions');
    const femaInput = document.getElementById('fema_id');
    const survivorIdInput = document.getElementById('survivor_id');

    if (nameInput && nameSuggestionsBox) {
        nameInput.addEventListener('input', function() {
            const query = this.value.trim();
            nameSuggestionsBox.innerHTML = '';
            if (!query) {
                nameSuggestionsBox.style.display = 'none';
                return;
            }
            fetch('/admin/survivors/fema-suggestions?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        nameSuggestionsBox.innerHTML = '';
                        data.forEach(function(item) {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-suggestion';
                            div.textContent = (item.name ? item.name : '');
                            div.addEventListener('mousedown', function() {
                                nameInput.value = item.name;
                                if (femaInput && item.fema_id) femaInput.value = item.fema_id;
                                if (survivorIdInput && item.id) survivorIdInput.value = item.id;
                                nameSuggestionsBox.style.display = 'none';
                            });
                            nameSuggestionsBox.appendChild(div);
                        });
                        nameSuggestionsBox.style.display = 'block';
                    } else {
                        nameSuggestionsBox.style.display = 'none';
                    }
                });
        });
        document.addEventListener('click', function(e) {
            if (!nameInput.contains(e.target) && !nameSuggestionsBox.contains(e.target)) {
                nameSuggestionsBox.style.display = 'none';
            }
        });
    }

    if (femaInput && document.getElementById('fema-id-suggestions')) {
        const femaSuggestionsBox = document.getElementById('fema-id-suggestions');
        femaInput.addEventListener('input', function() {
            const query = this.value.trim();
            femaSuggestionsBox.innerHTML = '';
            if (!query) {
                femaSuggestionsBox.style.display = 'none';
                return;
            }
            fetch('/admin/survivors/fema-suggestions?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        femaSuggestionsBox.innerHTML = '';
                        data.forEach(function(item) {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-suggestion';
                            div.textContent = item.fema_id;
                            div.addEventListener('mousedown', function() {
                                femaInput.value = item.fema_id;
                                if (nameInput && item.name) nameInput.value = item.name;
                                if (survivorIdInput && item.id) survivorIdInput.value = item.id;
                                femaSuggestionsBox.style.display = 'none';
                            });
                            femaSuggestionsBox.appendChild(div);
                        });
                        femaSuggestionsBox.style.display = 'block';
                    } else {
                        femaSuggestionsBox.style.display = 'none';
                    }
                });
        });
        document.addEventListener('click', function(e) {
            if (!femaInput.contains(e.target) && !femaSuggestionsBox.contains(e.target)) {
                femaSuggestionsBox.style.display = 'none';
            }
        });
    }

    var soldSwitch = document.querySelector('input[type="checkbox"][name="is_sold_at_auction"]');
    var auctionSection = document.getElementById('sold-at-auction-section');
    var donatedSwitch = document.querySelector('input[type="checkbox"][name="is_being_donated"]');
    var donatedSection = document.getElementById('donation-section');

    function toggleAuctionSection() {
        if (soldSwitch && auctionSection) {
            auctionSection.style.display = soldSwitch.checked ? '' : 'none';
        }
    }

    function toggleDonatedSection() {
        if (donatedSwitch && donatedSection) {
            donatedSection.style.display = donatedSwitch.checked ? '' : 'none';
        }
    }

    if (soldSwitch && auctionSection) {
        soldSwitch.addEventListener('change', toggleAuctionSection);
        toggleAuctionSection();
    }

    if (donatedSwitch && donatedSection) {
        donatedSwitch.addEventListener('change', toggleDonatedSection);
        toggleDonatedSection();
    }
});

