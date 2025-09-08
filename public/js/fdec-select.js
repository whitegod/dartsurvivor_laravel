document.addEventListener('DOMContentLoaded', function() {
    var headerFdec = document.getElementById('fdec-filter');
    var editFdec = document.getElementById('fdec_id') || document.getElementById('fdec');
    var rawSurvivor = window.survivorFdecId;
    var stored = localStorage.getItem('fdecFilter');

    function normalizeToArray(raw) {
        if (raw === undefined || raw === null || raw === '') return [];
        if (Array.isArray(raw)) return raw.map(String);
        if (typeof raw === 'string') {
            try { var parsed = JSON.parse(raw); if (Array.isArray(parsed)) return parsed.map(String); } catch (e) {}
            return raw.split(',').map(function(s){ return s.trim(); }).filter(Boolean);
        }
        return [String(raw)];
    }

    function setNativeValues(selectEl, values) {
        if (!selectEl) return;
        values = Array.isArray(values) ? values.map(String) : (values ? [String(values)] : []);
        Array.prototype.forEach.call(selectEl.options, function(opt) {
            opt.selected = values.indexOf(opt.value) !== -1;
        });
        try { selectEl.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
    }

    // Initialize Select2 once when available, then call callback
    function ensureSelect2Init(callback, attemptsLeft) {
        attemptsLeft = typeof attemptsLeft === 'number' ? attemptsLeft : 20;
        if (window.jQuery && $.fn && $.fn.select2 && $('#fdec_id').length) {
            var $sel = $('#fdec_id');
            if (!$sel.data('select2')) {
                $sel.select2({ placeholder: "Select FDEC(s)", allowClear: true, width: '100%' });
            }
            if (window.survivorReadonly) $sel.prop('disabled', true);
            if (typeof callback === 'function') callback();
            return;
        }
        if (attemptsLeft > 0) {
            setTimeout(function(){ ensureSelect2Init(callback, attemptsLeft - 1); }, 50);
        } else {
            // fallback: call callback so values can be applied natively
            if (typeof callback === 'function') callback();
        }
    }

    function applyValues(values) {
        if (!editFdec) return;
        if (window.jQuery && $.fn && $.fn.select2 && $('#fdec_id').length) {
            ensureSelect2Init(function(){
                $('#fdec_id').val(Array.isArray(values) ? values : (values ? [values] : [])).trigger('change');
            });
        } else {
            setNativeValues(editFdec, values);
        }
    }

    var survivorFdecIds = normalizeToArray(rawSurvivor);

    // populate edit select from DB values (if present)
    if (survivorFdecIds.length) {
        applyValues(survivorFdecIds);
    } else {
        // still ensure Select2 styling if plugin present
        if (editFdec && window.jQuery && $.fn && $.fn.select2) {
            ensureSelect2Init(function(){}, 10);
        }
    }

    if (!headerFdec) return;

    // Only set header from URL param; keep header stable otherwise
    var url = new URL(window.location.href);
    var urlParam = url.searchParams.get('fdec_id');
    if (urlParam && headerFdec.querySelector('option[value="' + urlParam + '"]')) {
        headerFdec.value = urlParam;
    }

    // If on survivors list and URL lacks fdec_id but localStorage has one, apply it once
    var path = window.location.pathname || '';
    var isSurvivorsList = path.indexOf('/admin/survivors') !== -1;
    if (isSurvivorsList && !urlParam && stored && stored !== "" && stored !== "null" && stored !== "undefined") {
        url.searchParams.set('fdec_id', stored);
        window.location.replace(url.toString());
        return;
    }

    // If DB had no values, use header to populate edit select (but don't overwrite stored)
    if (!survivorFdecIds.length && editFdec && headerFdec.value) {
        applyValues([headerFdec.value]);
    }

    headerFdec.addEventListener('change', function () {
        localStorage.setItem('fdecFilter', headerFdec.value || '');
        if (!survivorFdecIds.length && editFdec) {
            applyValues(headerFdec.value ? [headerFdec.value] : []);
        }
        var u = new URL(window.location.href);
        if (headerFdec.value) u.searchParams.set('fdec_id', headerFdec.value);
        else u.searchParams.delete('fdec_id');
        window.location.href = u.toString();
    });
});

// restore single explicit Select2 init (keeps styling stable)
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