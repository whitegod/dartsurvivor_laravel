document.addEventListener('DOMContentLoaded', function() {
    var headerFdec = document.getElementById('fdec-filter');
    var editFdec = document.getElementById('fdec_id') || document.getElementById('fdec');
    var survivorFdecId = window.survivorFdecId || "";
    var stored = localStorage.getItem('fdecFilter');

    if (headerFdec) {
        // Set header filter: prefer survivor's FDEC id, then localStorage, then default
        if (survivorFdecId && headerFdec.querySelector('option[value="' + survivorFdecId + '"]')) {
            headerFdec.value = survivorFdecId;
        } else if (stored && headerFdec.querySelector('option[value="' + stored + '"]')) {
            headerFdec.value = stored;
        } else {
            headerFdec.value = "";
        }

        // Auto-filter on first visit if localStorage is set and not already in URL
        var url = new URL(window.location.href);
        var urlHasFdecId = url.searchParams.has('fdec_id');
        if (!urlHasFdecId && stored && stored !== "" && stored !== "null" && stored !== "undefined") {
            url.searchParams.set('fdec_id', stored);
            window.location.replace(url.toString());
            return; // Prevent further JS execution on reload
        }

        // Sync edit select on load
        if (editFdec) {
            if (survivorFdecId) {
                editFdec.value = survivorFdecId;
            } else {
                editFdec.value = headerFdec.value;
            }
        }

        // On header filter change, update localStorage and edit select (if survivorFdecId is not set)
        headerFdec.addEventListener('change', function() {
            localStorage.setItem('fdecFilter', headerFdec.value);
            if (editFdec && !survivorFdecId) {
                editFdec.value = headerFdec.value;
            }
            // Filtering logic
            var selected = headerFdec.value;
            var url = new URL(window.location.href);
            if (selected) {
                url.searchParams.set('fdec_id', selected);
            } else {
                url.searchParams.delete('fdec_id');
            }
            window.location.href = url.toString();
        });
    }

    if (editFdec && headerFdec && headerFdec.value) {
        for (var i = 0; i < editFdec.options.length; i++) {
            editFdec.options[i].selected = (editFdec.options[i].value === headerFdec.value);
        }
    }
});

if (window.jQuery && $('#fdec_id').length) {
    $('#fdec_id').select2({
        placeholder: "Select FDEC(s)",
        allowClear: true,
        width: '100%'
    });
    if (window.survivorReadonly) {
        $('#fdec_id').prop('disabled', true);
    }
}