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
    document.querySelectorAll('.options-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const dropdown = this.querySelector('.dropdown-menu');
            const isActive = dropdown.classList.contains('active');
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
            if (!isActive) {
                dropdown.classList.add('active');
            }
        });
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.options-icon')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        }
    });

    document.getElementById('search-button').addEventListener('click', function() {
        performSearch();
    });

    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const searchInput = document.getElementById('search-input').value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput);
        window.location.href = url.toString();
    }

    function openModal() {
        document.getElementById('addNewModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('addNewModal').style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('addNewModal');
        if (event.target === modal) {
            closeModal();
        }
    });

    function renderTable() {
        const ttus = JSON.parse(document.getElementById('ttus-data').textContent);
        const fields = JSON.parse(document.getElementById('fields-data').textContent);
        const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);

        // Render header
        const headerRow = document.getElementById('dynamic-table-header');
        headerRow.innerHTML = '';
        checkedFields.forEach(field => {
            const th = document.createElement('th');
            // Map DB field names to display names for the table header
            if (field === 'vin') {
                th.textContent = 'VIN - Last 7';
            } else if (field === 'unit' || field === 'loc_id') {
                th.textContent = 'Unit';
            } else if (field === 'status') {
                th.textContent = 'Status (Color Code)';
            } else if (field === 'total_beds') {
                th.textContent = 'Total Beds';
            } else {
                th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
            headerRow.appendChild(th);
        });
        // Add options/filter column
        const thOptions = document.createElement('th');
        thOptions.style.position = 'relative';
        thOptions.innerHTML = `<button id="filter-button" style="background: none; border: none; cursor: pointer; padding: 0; vertical-align: middle;">
            <i class='fa fa-filter'></i>
        </button>`;
        headerRow.appendChild(thOptions);

        // Render body
        const body = document.getElementById('dynamic-table-body');
        body.innerHTML = '';
        ttus.forEach(ttu => {
            const tr = document.createElement('tr');
            checkedFields.forEach(field => {
                const td = document.createElement('td');
                if (field === 'vin') {
                    td.textContent = ttu.vin ? ttu.vin.slice(-7) : '';
                } else if (field === 'unit' || field === 'loc_id') {
                    // Show "Lot {unit/loc_id}" if present
                    td.textContent = ttu.unit !== undefined && ttu.unit !== null
                        ? 'Lot ' + ttu.unit
                        : (ttu.loc_id !== undefined && ttu.loc_id !== null ? 'Lot ' + ttu.loc_id : '');
                } else if (field === 'status') {
                    td.innerHTML = `<span class="status ${ttu.status ? ttu.status.toLowerCase() : ''}">${ttu.status ? ttu.status.charAt(0).toUpperCase() + ttu.status.slice(1) : ''}</span>`;
                } else if (field === 'total_beds') {
                    td.innerHTML = `<span class="total-beds">${ttu.total_beds !== undefined ? ttu.total_beds : ''}</span>`;
                } else {
                    td.textContent = ttu[field] !== undefined ? ttu[field] : '';
                }
                tr.appendChild(td);
            });
            // Options column
            const tdOptions = document.createElement('td');
            tdOptions.className = 'options-icon';
            tdOptions.style.position = 'relative';
            tdOptions.innerHTML = `⋮
                <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                    <a href="/admin/ttus/edit/${ttu.id}">Edit</a>
                    <form action="/admin/ttus/delete/${ttu.id}" method="POST" style="margin: 0;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                    </form>
                </div>`;
            tr.appendChild(tdOptions);
            body.appendChild(tr);
        });

        // Re-bind filter button event after header re-render
        setTimeout(() => {
            const filterBtn = document.getElementById('filter-button');
            if (filterBtn && !filterBtn._bound) {
                filterBtn.addEventListener('click', function(event) {
                    event.stopPropagation();
                    const dropdown = document.getElementById('filter-dropdown');
                    dropdown.classList.toggle('active');
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== dropdown) menu.classList.remove('active');
                    });
                });
                filterBtn._bound = true;
            }

            // Re-bind 3-dots options-icon click events after table re-render
            document.querySelectorAll('.options-icon').forEach(icon => {
                if (!icon._bound) {
                    icon.addEventListener('click', function(event) {
                        event.stopPropagation();
                        const dropdown = this.querySelector('.dropdown-menu');
                        const isActive = dropdown.classList.contains('active');
                        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                        if (!isActive) {
                            dropdown.classList.add('active');
                        }
                    });
                    icon._bound = true;
                }
            });
        }, 0);
    }

    // Initial render
    renderTable();

    // Update table on checkbox change
    document.querySelectorAll('.filter-field-checkbox').forEach(cb => {
        cb.addEventListener('change', renderTable);
    });

    // Hide all dropdown menus when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#filter-button')) {
            document.getElementById('filter-dropdown').classList.remove('active');
        }
        // Hide all row options dropdowns if clicking outside .options-icon
        if (!event.target.closest('.options-icon')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
        }
    });
</script>
@endsection
