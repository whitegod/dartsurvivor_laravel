@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="Survivors">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search" value="{{ request('search') }}">
                    <button id="search-button" class="filter-icon"><i class="fa fa-filter"></i></button>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <select id="archive-select" style="padding:8px 10px; border-radius:4px;">
                        <option value="0" {{ request()->query('archived') === '1' ? '' : 'selected' }}>Inbox</option>
                        <option value="1" {{ request()->query('archived') === '1' ? 'selected' : '' }}>Archived</option>
                    </select>
                    <a href="{{ route('admin.survivors.edit', ['id' => 'new']) }}" class="add-new-button">Add New</a>
                </div>
            </div>

            <div style="position: relative;">
                <table style="width:100%; margin-bottom: 50px; min-width: 900px;">
                    <thead>
                        <tr id="dynamic-table-header">
                            <!-- Dynamic header will be rendered here -->
                        </tr>
                    </thead>
                    <tbody id="dynamic-table-body">
                        <!-- Dynamic body will be rendered here -->
                    </tbody>
                </table>
                <div id="filter-dropdown" class="dropdown-menu"
                    style="top: 40px; right: 0; left: auto; min-width: 160px; max-height: 300px; overflow-y: auto; position: absolute; z-index: 102;">
                    @php
                        $defaultChecked = ['name', 'fema_id', 'phone', 'hh_size', 'own_rent'];
                        $shownLabels = [];
                    @endphp
                    @foreach($fields as $field)
                        @php
                            $label = match($field) {
                                'fname', 'lname', 'name' => 'Name',
                                'primary_phone', 'secondary_phone', 'phone' => 'Phone',
                                'fema_id' => 'FEMA-ID',
                                'fdec_id' => 'FDEC',
                                'hh_size' => 'HH Size',
                                'own_rent' => 'Own/Rent',
                                'caseworker_id' => 'Caseworker',
                                default => ucfirst(str_replace('_', ' ', $field)),
                            };
                            if(in_array($label, $shownLabels)) continue;
                            $shownLabels[] = $label;
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;" {{ in_array($field, $defaultChecked) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                    <div class="filter-save-sticky">
                        <button id="save-filter-fields" class="add-new-button" style="width: 100%;">Save</button>
                    </div>
                </div>
                <!-- Hidden JSON data for survivors and fields -->
                <script type="application/json" id="survivors-data">
                    {!! json_encode($survivors) !!}
                </script>
                <script type="application/json" id="fields-data">
                    {!! json_encode($fields) !!}
                </script>
            </div>
        </div>
    </div>
</section>

<script>
    window.csrfToken = "@csrf";
</script>
<script>
    // Archive selector behaviour: reload page with archived query param
    (function(){
        var archiveSelect = document.getElementById('archive-select');
        if (!archiveSelect) return;
        archiveSelect.addEventListener('change', function(){
            var val = this.value;
            var url = new URL(window.location.href);
            if (val === '1') url.searchParams.set('archived', '1');
            else url.searchParams.delete('archived');
            // keep existing search param if present
            window.location.href = url.toString();
        });
    })();
</script>
<script src="{{ asset('js/tableSort.js') }}"></script>
<script src="{{ asset('js/survivorsTable.js') }}"></script>
@endsection
