@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link rel="stylesheet" href="{{ asset('css/survivors.css') }}">
@endsection

@section('content')
<section id="Survivors">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search" value="{{ request('search') }}">
                    <button id="search-button" class="filter-icon">🔍</button>
                </div>
                <a href="{{ route('admin.survivors.edit', ['id' => 'new']) }}" class="add-new-button">Add New</a>
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
                <div id="filter-dropdown" class="dropdown-menu"
                    style="top: 40px; right: 0; left: auto; min-width: 160px; max-height: 300px; overflow-y: auto; position: absolute; z-index: 102;">
                    @foreach($fields as $field)
                        @php
                            // Merge fname and lname as a single "name" checkbox
                            if ($field === 'fname') {
                                $defaultChecked = true;
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="name" style="margin-right: 8px;" checked>
                            Name
                        </label>
                        @php
                                continue;
                            }
                            // Merge primary_phone and secondary_phone as a single "phone" checkbox
                            if ($field === 'primary_phone') {
                                $defaultChecked = true;
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="phone" style="margin-right: 8px;" checked>
                            Phone
                        </label>
                        @php
                                continue;
                            }
                            if ($field === 'secondary_phone' || $field === 'lname') continue;
                            $defaultChecked = in_array($field, ['fema_id', 'address', 'hh_size', 'li_date']);
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;" {{ $defaultChecked ? 'checked' : '' }}>
                            @if($field === 'fema_id')
                                FEMA-ID
                            @elseif($field === 'hh_size')
                                HH Size
                            @elseif($field === 'own_rent')
                                Own/Rent
                            @else
                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                            @endif
                        </label>
                    @endforeach
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
<script src="{{ asset('js/survivorsTable.js') }}"></script>
@endsection
