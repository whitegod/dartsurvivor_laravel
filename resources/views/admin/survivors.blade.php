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
                <a href="{{ route('admin.survivors.edit', ['id' => 'new']) }}" class="add-new-button">Add New</a>
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
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/tableSort.js') }}"></script>
<script src="{{ asset('js/survivorsTable.js') }}"></script>
@endsection
