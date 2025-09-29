(function(){
    // Canonical storage key and legacy fallback
    var STORAGE_KEY = 'fdecFilter';
    var LEGACY_KEY = 'fdec-filter';

    function readStored(){
        try{ var v = localStorage.getItem(STORAGE_KEY); if(!v) v = localStorage.getItem(LEGACY_KEY); if(v === ''||v==='null'||v==='undefined') return null; return v; }catch(e){return null}
    }
    function clearStored(){ try{ localStorage.removeItem(STORAGE_KEY); }catch(e){} try{ localStorage.removeItem(LEGACY_KEY);}catch(e){} }

    // Early redirect to avoid unfiltered flash
    try{
        var storedEarly = readStored();
        var path = window.location.pathname || '';
        var isListing = path.indexOf('/admin/survivors')!==-1 || path.indexOf('/admin/ttus')!==-1 || path.indexOf('/admin/locations')!==-1;
        var urlEarly = new URL(window.location.href);
        var hasParam = !!urlEarly.searchParams.get('fdec_id');
        if(isListing && !hasParam && storedEarly){ try{ document.documentElement.style.visibility='hidden'; }catch(e){} urlEarly.searchParams.set('fdec_id', storedEarly); window.location.replace(urlEarly.toString()); return; }
    }catch(e){}

    document.addEventListener('DOMContentLoaded', function(){
        var header = document.getElementById('fdec-filter');
        var editSelect = document.getElementById('fdec_id') || document.getElementById('fdec');

        // Init header from URL or stored
        try{
            if(header){
                var u = new URL(window.location.href);
                var p = u.searchParams.get('fdec_id');
                var s = readStored();
                if(p && header.querySelector('option[value="'+p+'"]')) header.value = p;
                else if(!p && s && header.querySelector('option[value="'+s+'"]')) header.value = s;

                header.addEventListener('change', function(){
                    try{ if(header.value){ localStorage.setItem(STORAGE_KEY, header.value); try{ localStorage.removeItem(LEGACY_KEY);}catch(e){} } else { clearStored(); } }catch(e){}
                    var nu = new URL(window.location.href);
                    if(header.value) nu.searchParams.set('fdec_id', header.value); else nu.searchParams.delete('fdec_id');
                    window.location.href = nu.toString();
                });
            }
        }catch(e){}

        // Select2 init and edit pre-populate (checkbox-style dropdown)
        function ensureSelect2(cb, attempts){
            attempts = typeof attempts === 'number' ? attempts : 12;
            if (window.jQuery && $.fn && $.fn.select2 && editSelect && $(editSelect).length) {
                var $s = $(editSelect);
                if (!$s.data('select2')) {
                    // render checkbox in dropdown and keep it open for multi-select
                    $s.select2({
                        placeholder: 'Select FDEC(s)',
                        allowClear: true,
                        width: '100%',
                        closeOnSelect: false,
                        templateResult: function(item) {
                            if (!item.id) return item.text;
                            var checked = item.selected ? 'checked' : '';
                            return '<label style="display:flex;align-items:center;gap:.5rem"><input type="checkbox" ' + checked + ' /> <span>' + item.text + '</span></label>';
                        },
                        templateSelection: function(item) { return item.text; },
                        escapeMarkup: function(m) { return m; }
                    });
                }
                if (window.survivorReadonly) $s.prop('disabled', true);
                if (typeof cb === 'function') cb();
                return;
            }
            if (attempts > 0) setTimeout(function() { ensureSelect2(cb, attempts - 1); }, 60);
            else if (typeof cb === 'function') cb();
        }

        function applyEditValues(vals){ if(!editSelect) return; if(window.jQuery && $.fn && $.fn.select2 && $(editSelect).length){ ensureSelect2(function(){ $(editSelect).val(Array.isArray(vals)?vals:(vals? [vals]:[])).trigger('change'); }); } else { var opts = Array.from(editSelect.options||[]); var v = Array.isArray(vals)?vals.map(String):(vals? [String(vals)]:[]); opts.forEach(function(o){ o.selected = v.indexOf(o.value)!==-1; }); try{ editSelect.dispatchEvent(new Event('change',{bubbles:true})); }catch(e){} } }

        try{
            // Determine any preselected values coming from server-side globals or data attributes
            var raw = window.survivorFdecId || (editSelect? (editSelect.getAttribute('data-selected')||'') : '');
            var vals = [];
            if(raw){
                if(Array.isArray(raw)) vals = raw.map(String);
                else if(typeof raw === 'string'){
                    try{ var parsed = JSON.parse(raw); if(Array.isArray(parsed)) vals = parsed.map(String); else vals = raw.split(',').map(function(s){return s.trim();}).filter(Boolean); }catch(e){ vals = raw.split(',').map(function(s){return s.trim();}).filter(Boolean); }
                } else {
                    vals = [String(raw)];
                }
            }

            // If there are no server-provided values (create/add-new mode), try to auto-populate
            // from the header stored filter or the URL `fdec_id` parameter so new records inherit the current filter.
            if((!vals || vals.length === 0) && editSelect){
                try{
                    var fromUrl = (new URL(window.location.href)).searchParams.get('fdec_id');
                    var fromStored = readStored();
                    var candidate = fromUrl || fromStored || '';
                    if(candidate){
                        // candidate may be comma-separated; normalize to array
                        var candVals = [];
                        try{ var p = JSON.parse(candidate); if(Array.isArray(p)) candVals = p.map(String); else candVals = String(candidate).split(',').map(function(s){return s.trim();}).filter(Boolean); }catch(e){ candVals = String(candidate).split(',').map(function(s){return s.trim();}).filter(Boolean); }
                        if(candVals.length) vals = candVals;
                    }
                }catch(e){}
            }

            if(vals && vals.length) {
                // Apply immediately (covers case where Select2 isn't loaded yet)
                applyEditValues(vals);
                // Also re-apply after Select2 finishes initializing to ensure the widget reflects the values
                if(editSelect) ensureSelect2(function(){ applyEditValues(vals); },6);
            } else {
                // No values to apply now; still ensure Select2 initializes so the control is interactive
                if(editSelect) ensureSelect2(function(){},6);
            }
        }catch(e){}

        // restore any hidden visibility from early redirect
        try{ document.documentElement.style.visibility = ''; }catch(e){}
    });
})();

