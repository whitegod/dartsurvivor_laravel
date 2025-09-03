@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">  
@endsection

@section('content')
<section id="TTU">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search">
                    <button id="search-button" class="filter-icon"><i class="fa fa-filter"></i></button>
                </div>
                <a href="{{ route('admin.ttus.edit') }}" class="add-new-button btn btn-primary">Add New</a>
            </div>

            <div style="position: relative;">
                <!-- Filter Dropdown -->
                <div id="filter-dropdown" class="dropdown-menu"
                    style="top: 40px; right: 0; left: auto; min-width: 160px; max-height: 300px; overflow-y: auto; position: absolute; z-index: 102;">
                    @php
                        $defaultChecked = ['vin', 'location', 'address', 'unit', 'status', 'total_beds'];
                    @endphp
                    @foreach($fields as $field)
                        @php
                            $label = match($field) {
                                'vin' => 'VIN - Last 7',
                                'survivor_id' => 'Survivor',
                                'location' => 'Location',
                                'address' => 'Address',
                                'unit', 'loc_id' => 'Unit',
                                'status' => 'Status (Color Code)',
                                'total_beds' => 'Total Beds',
                                default => ucfirst(str_replace('_', ' ', $field)),
                            };
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;"
                                {{ in_array($field, $defaultChecked) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                    <div class="filter-save-sticky">
                        <button id="save-filter-fields" class="add-new-button" style="width: 100%;">Save</button>
                    </div>
                </div>
                <table style="width:100%; margin-bottom: 50px;">
                    <thead>
                        <tr id="dynamic-table-header">
                            <!-- Dynamic header will be rendered here -->
                        </tr>
                    </thead>
                    <tbody id="dynamic-table-body">
                        <!-- Dynamic body will be rendered here -->
                    </tbody>
                </table>
                <script type="application/json" id="ttus-data">
                    {!! json_encode($ttus) !!}
                </script>
                <script type="application/json" id="fields-data">
                    {!! json_encode($fields) !!}
                </script>
            </div>
        </div>
    </div>
</section>

<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/tableSort.js') }}"></script>
<script src="{{ asset('js/ttusTable.js') }}"></script>
@endsection
