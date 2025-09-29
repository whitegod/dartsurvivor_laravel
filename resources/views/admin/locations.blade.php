@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="Locations">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search">
                    <button id="search-button" class="filter-icon">
                        <i class="fa fa-filter"></i>
                    </button>
                </div>
                <a href="{{ route('admin.locations.edit', ['id' => 'new']) }}" class="add-new-button">Add New</a>
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
                <!-- Filter Dropdown -->
                @php
                    // Ensure fdec_id and contact_name are available as selectable fields for the table
                    if (!in_array('fdec_id', $fields)) {
                        $fields[] = 'fdec_id';
                    }
                    if (!in_array('contact_name', $fields)) {
                        $fields[] = 'contact_name';
                    }
                    $defaultChecked = $fields;
                @endphp
                <div id="filter-dropdown" class="dropdown-menu"
                    style="top: 40px; right: 0; left: auto; min-width: 160px; max-height: 300px; overflow-y: auto; position: absolute; z-index: 102;">
                    @foreach($fields as $field)
                    <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                        <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;" {{ in_array($field, $defaultChecked) ? 'checked' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                    </label>
                    @endforeach
                    <div class="filter-save-sticky">
                        <button id="save-filter-fields" class="add-new-button" style="width: 100%;">Save</button>
                    </div>
                </div>
                <!-- Hidden JSON data for table and fields -->
                <script type="application/json" id="locations-data">
                    {!! json_encode($locations) !!}
                </script>
                <script type="application/json" id="fields-data">
                    {!! json_encode($fields) !!}
                </script>
                <script type="application/json" id="fdec-list-data">
                    @php
                        $__fdec_data = [];
                        foreach (($fdecList ?? []) as $__f) {
                            $__fdec_data[] = ['id' => $__f->id, 'label' => ($__f->fdec_no ?? $__f->name ?? $__f->id)];
                        }
                    @endphp
                    {!! json_encode($__fdec_data) !!}
                </script>
            </div>
        </div>
    </div>
</section>
<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/tableSort.js') }}"></script>
<script src="{{ asset('js/locationsTable.js') }}"></script>
@endsection