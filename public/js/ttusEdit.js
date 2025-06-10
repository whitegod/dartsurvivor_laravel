document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('location');
    const suggestionBox = document.getElementById('location-suggestions');
    let activeIndex = -1;
    let suggestions = [];

    locationInput.addEventListener('input', function() {
        const query = this.value;
        if (query.length < 1) {
            suggestionBox.style.display = 'none';
            return;
        }
        fetch('/admin/locations/autocomplete?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestions = data;
                suggestionBox.innerHTML = '';
                if (data.length === 0) {
                    suggestionBox.style.display = 'none';
                    return;
                }
                data.forEach((item, idx) => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.textContent = item.location;
                    div.addEventListener('mousedown', function(e) {
                        locationInput.value = item.location;
                        suggestionBox.style.display = 'none';
                    });
                    suggestionBox.appendChild(div);
                });
                suggestionBox.style.display = 'block';
                activeIndex = -1;
            });
    });

    locationInput.addEventListener('keydown', function(e) {
        const items = suggestionBox.querySelectorAll('.autocomplete-suggestion');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            updateActive();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            updateActive();
        } else if (e.key === 'Enter') {
            if (activeIndex >= 0 && activeIndex < items.length) {
                e.preventDefault();
                items[activeIndex].dispatchEvent(new Event('mousedown'));
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (!locationInput.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = 'none';
        }
    });

    function updateActive() {
        const items = suggestionBox.querySelectorAll('.autocomplete-suggestion');
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === activeIndex);
        });
        if (activeIndex >= 0 && activeIndex < items.length) {
            items[activeIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    // Donation section show/hide logic
    function toggleDonationSection() {
        var checkbox = document.querySelector('input[name="is_being_donated"]');
        var section = document.getElementById('donation-section');
        if (checkbox && section) {
            if (checkbox.checked) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
                // Clear donation section fields
                document.querySelector('select[name="recipient_type"]').selectedIndex = 0;
                document.getElementById('donation_agency').value = '';
                document.getElementById('donation_category').value = '';
            }
        }
    }
    // Initial check
    toggleDonationSection();
    // Listen for changes
    var donateCheckbox = document.querySelector('input[name="is_being_donated"]');
    if (donateCheckbox) {
        donateCheckbox.addEventListener('change', toggleDonationSection);
    }

    // Sold at auction section show/hide logic
    function toggleSoldAtAuctionSection() {
        var checkbox = document.querySelector('input[name="is_sold_at_auction"]');
        var section = document.getElementById('sold-at-auction-section');
        if (checkbox && section) {
            if (checkbox.checked) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
                // Clear sold-at-auction section fields
                document.getElementById('sold_at_auction_price').value = '';
                document.getElementById('recipient').value = '';
            }
        }
    }
    // Initial check
    toggleSoldAtAuctionSection();
    // Listen for changes
    var soldAuctionCheckbox = document.querySelector('input[name="is_sold_at_auction"]');
    if (soldAuctionCheckbox) {
        soldAuctionCheckbox.addEventListener('change', toggleSoldAtAuctionSection);
    }
    //Private Site handle
    const privatesiteSwitch = document.getElementById('privatesite-switch');
    const privatesiteSection = document.getElementById('privatesite-section');
    if (privatesiteSwitch && privatesiteSection) {
        privatesiteSwitch.addEventListener('change', function() {
            privatesiteSection.style.display = this.checked ? 'flex' : 'none';
        });
    }
});

function updateStatusWithColor() {
    var select = document.getElementById('status');
    var color = select.options[select.selectedIndex].getAttribute('data-color');
    var text = select.options[select.selectedIndex].textContent.replace(/^.\s*/, ''); // Remove emoji
    select.value = text + ' (' + color + ')';
}