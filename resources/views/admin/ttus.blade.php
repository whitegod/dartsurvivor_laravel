@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            max-width: 500px;
        }

        .search-container input[type="text"] {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            flex: 1;
        }

        .filter-icon, .add-new-button {
            display: inline-block;
            padding: 8px;
            border-color: #007bff;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .add-new-button {
            background-color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white;
            position: relative;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f5f5f5;
        }

        .status {
            display: inline-block;
            padding: 5px 0;
            width: 80px; /* Equal width for all badges */
            text-align: center;
            border-radius: 5px;
            color: white;
        }

        .status.ready {
            background-color: #00c851;
        }

        .status.occupied {
            background-color: #ff4444;
        }

        .status.pending {
            background-color: #ffbb33;
        }

        .total-beds {
            display: inline-block;
            width: 40px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .options-icon {
            cursor: pointer;
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 100;
            min-width: 120px; /* Adjust width for alignment */
            padding: 5px 0; /* Add padding for spacing */
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            text-decoration: none;
            color: black;
            display: block;
            padding: 8px 15px; /* Add padding for consistent spacing */
            text-align: left; /* Align text to the left */
            width: 100%; /* Ensure full width for alignment */
            border: none; /* Remove button border */
            background: none; /* Remove button background */
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f0f0f0; /* Add hover effect */
            color: black;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
            }

            .search-container input[type="text"] {
                flex: none;
                width: 100%;
            }
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close-modal {
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .modal-body .form-group {
            margin-bottom: 15px;
        }

        .modal-body .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .modal-body .form-group input,
        .modal-body .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-body .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }

        .modal-body .btn:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search">
                    <button id="search-button" class="filter-icon">🔍</button>
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
                            // Map DB field names to display names for the filter
                            $label = match($field) {
                                'vin' => 'VIN - Last 7',
                                'location' => 'Location',
                                'address' => 'Address',
                                // Map 'unit' to 'Unit' if present, else fallback to 'loc_id'
                                'unit', 'loc_id' => 'Unit',
                                'status' => 'Status (Color Code)',
                                'total_beds' => 'Total Beds',
                                default => ucfirst(str_replace('_', ' ', $field)),
                            };
                        @endphp
                        <label style="display: flex; align-items: center; padding: 8px 15px; cursor: pointer;">
                            <input type="checkbox" class="filter-field-checkbox" data-field="{{ $field }}" style="margin-right: 8px;" {{ in_array($field, $defaultChecked) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                <table style="width:100%; margin-top: 0;">
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
<script src="{{ asset('js/ttusTable.js') }}"></script>
@endsection
