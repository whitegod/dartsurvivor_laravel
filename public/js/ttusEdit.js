document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('location');
    const locationType = document.getElementById('location_type');
    const suggestionsBox = document.getElementById('location-suggestions');
    const privatesiteSection = document.getElementById('privatesite-section');

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

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== locationInput) {
            suggestionsBox.style.display = 'none';
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

