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
        var headerContainer = header ? (header.closest && header.closest('.select-form') || document.querySelector('.select-form')) : document.querySelector('.select-form');
        // Add loading class to prevent native select flash until Select2 initializes
        var loadingTimeoutHandle = null;
        try{ if(headerContainer) headerContainer.classList.add('select-form--loading'); }catch(e){}


        // Init header from URL or stored (multi-select aware)
        try{
            if(header){
                var u = new URL(window.location.href);
                var p = u.searchParams.get('fdec_id');
                var s = readStored();

                function toArray(raw){
                    if(!raw && raw !== 0) return [];
                    if(Array.isArray(raw)) return raw.map(String);
                    try{ var j = JSON.parse(raw); if(Array.isArray(j)) return j.map(String); }catch(e){}
                    return String(raw).split(',').map(function(x){return x.trim();}).filter(Boolean);
                }

                var headerVals = [];
                if(p) headerVals = toArray(p);
                else if(!p && s) headerVals = toArray(s);

                // If the server preselected options via the blade (selected attributes), keep them; otherwise apply headerVals after init
                header._initialHeaderVals = headerVals; // stash for later Select2 init

                header.addEventListener('change', function(){
                    try{
                        var selected = Array.from(header.selectedOptions || []).map(function(o){ return o.value; }).filter(Boolean);
                        if(selected.length) {
                            var csv = selected.join(',');
                            localStorage.setItem(STORAGE_KEY, csv);
                            try{ localStorage.removeItem(LEGACY_KEY);}catch(e){}
                        } else {
                            clearStored();
                        }
                    }catch(e){}

                    try{
                        var nu = new URL(window.location.href);
                        var selected = Array.from(header.selectedOptions || []).map(function(o){ return o.value; }).filter(Boolean);
                        if(selected.length) nu.searchParams.set('fdec_id', selected.join(',')); else nu.searchParams.delete('fdec_id');
                        window.location.href = nu.toString();
                    }catch(e){
                        // fallback: no navigation
                    }
                });
            }
        }catch(e){}

        // Select2 init and edit pre-populate (checkbox-style dropdown)
        function ensureSelect2(cb, attempts){
            attempts = typeof attempts === 'number' ? attempts : 12;
            if (window.jQuery && $.fn && $.fn.select2) {
                // initialize edit select if present
                if (editSelect && $(editSelect).length) {
                    var $s = $(editSelect);
                    if (!$s.data('select2')) {
                        $s.select2({
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
                }

                // initialize header select if present
                if (header && $(header).length) {
                    var $h = $(header);
                    if (!$h.data('select2')) {
                        $h.select2({
                            placeholder: 'All FDEC',
                            allowClear: true,
                            width: 'style',
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
                    // Add Save button inside the Select2 dropdown when it opens
                    try{
                        $(header).on('select2:open', function(){
                            // ensure dropdown exists
                            var $dropdown = $('.select2-container--open .select2-dropdown');
                            if(!$dropdown || !$dropdown.length) return;
                            // avoid duplicate footer
                            if($dropdown.find('.select2-footer').length) return;
                            var $footer = $('<div class="select2-footer" style="padding:8px; text-align:right;"><button type="button" class="btn btn-xs btn-primary select2-footer-save">Save</button></div>');
                            $dropdown.append($footer);

                            $footer.on('click', '.select2-footer-save', function(){
                                try{
                                    var selected = Array.from(header.selectedOptions || []).map(function(o){ return o.value; }).filter(Boolean);
                                    if(selected.length){
                                        var csv = selected.join(',');
                                        localStorage.setItem(STORAGE_KEY, csv);
                                        try{ localStorage.removeItem(LEGACY_KEY);}catch(e){}
                                    } else {
                                        clearStored();
                                    }
                                }catch(e){}

                                // Trigger filtering: update URL param and navigate (mirrors change handler)
                                try{
                                    var nu = new URL(window.location.href);
                                    var selected = Array.from(header.selectedOptions || []).map(function(o){ return o.value; }).filter(Boolean);
                                    if(selected.length) nu.searchParams.set('fdec_id', selected.join(',')); else nu.searchParams.delete('fdec_id');
                                    // close dropdown before navigating to give Select2 a chance to tidy up
                                    try{ $(header).select2('close'); }catch(e){}
                                    window.location.href = nu.toString();
                                }catch(e){}
                            });
                        });
                        // remove footer when closed to avoid stale DOM
                        $(header).on('select2:close', function(){
                            try{ $('.select2-container .select2-footer').remove(); }catch(e){}
                        });
                    }catch(e){}
                    // ensure header reflects any preselected native value or stashed values
                    try{
                        var hv = header.value;
                        var st = header._initialHeaderVals;
                        if(Array.isArray(st) && st.length){ $h.val(st).trigger('change'); }
                        else if(hv){ $h.val(hv).trigger('change'); }
                        // remove loading class when header has been initialized
                        try{ if(headerContainer) headerContainer.classList.remove('select-form--loading'); }catch(e){}
                        try{ if(loadingTimeoutHandle) clearTimeout(loadingTimeoutHandle); }catch(e){}
                    }catch(e){}
                }



                if (typeof cb === 'function') cb();
                return;
            }
            if (attempts > 0) setTimeout(function() { ensureSelect2(cb, attempts - 1); }, 60);
            else if (typeof cb === 'function') cb();
        }

        // If some pages replace or lazily render the header after DOMContentLoaded,
        // observe the document for additions of the header element and re-run initialization.
        try{
            var observed = false;
            var observer = new MutationObserver(function(mutations){
                mutations.forEach(function(m){
                    if(m.addedNodes && m.addedNodes.length){
                        m.addedNodes.forEach(function(n){
                            if(n.nodeType === 1){
                                if(n.id === 'fdec-filter' || n.querySelector && n.querySelector('#fdec-filter')){
                                    // update reference and initialize
                                    header = document.getElementById('fdec-filter');
                                    observed = true;
                                    ensureSelect2();
                                }
                            }
                        });
                    }
                    // also handle replacedNodes via mutation.removedNodes + addedNodes sequences
                });
            });
            observer.observe(document.documentElement || document.body, { childList: true, subtree: true });

            // As a safety net, attempt a single re-init after a short delay in case header was created asynchronously
            setTimeout(function(){ if(!observed){ header = document.getElementById('fdec-filter'); if(header) ensureSelect2(); } }, 400);
            // Stop observing after a short period to avoid overhead
            setTimeout(function(){ try{ observer.disconnect(); }catch(e){} }, 2000);
        }catch(e){}

        // Final safety: reveal header after 700ms even if Select2 hasn't completed, to avoid permanent hidden state
        try{ loadingTimeoutHandle = setTimeout(function(){ try{ if(headerContainer) headerContainer.classList.remove('select-form--loading'); }catch(e){} }, 700); }catch(e){}

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

