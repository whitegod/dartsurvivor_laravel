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
                    <button id="search-filter-btn" class="filter-icon">
                        <i class="fa fa-filter"></i>
                    </button>
                </div>
                <a href="{{ route('admin.locations.edit', ['id' => 'new']) }}" class="add-new-button">Add New</a>
            </div>

            <div style="position: relative;">
                <table style="width:100%; margin-top: 0; min-width: 900px;">
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
                <div id="filter-dropdown" class="dropdown-menu"
                    style="top: 40px; right: 0; left: auto; min-width: 160px; max-height: 300px; overflow-y: auto; position: absolute; z-index: 102;">
                    <!-- Filter checkboxes go here -->
                    @foreach($fields as $field)
                    <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                        <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;" checked>
                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                    </label>
                    @endforeach
                </div>
                <!-- Hidden JSON data for table and fields -->
                <script type="application/json" id="locations-data">
                    {!! json_encode($locations) !!}
                </script>
                <script type="application/json" id="fields-data">
                    {!! json_encode($fields) !!}
                </script>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/locationsTable.js') }}"></script>
@endsection