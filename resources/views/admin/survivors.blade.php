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

        .hh-size {
            display: inline-block;
            width: 40px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    </style>
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
        const searchInput = document.getElementById('search-input').value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput);
        window.location.href = url.toString();
    });

    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            const searchInput = document.getElementById('search-input').value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput);
            window.location.href = url.toString();
        }
    });

    // --- Dynamic Table Rendering ---
    function renderTable() {
        const survivors = JSON.parse(document.getElementById('survivors-data').textContent);
        const fields = JSON.parse(document.getElementById('fields-data').textContent);
        const checkedFields = Array.from(document.querySelectorAll('.filter-field-checkbox:checked')).map(cb => cb.dataset.field);

        // Render header
        const headerRow = document.getElementById('dynamic-table-header');
        headerRow.innerHTML = '';
        checkedFields.forEach(field => {
            const th = document.createElement('th');
            // Merge fname and lname into "Name" column
            if (field === 'fname' || field === 'lname') {
                if (!checkedFields.includes('name')) {
                    th.textContent = 'Name';
                    headerRow.appendChild(th);
                }
            } else if (field === 'name') {
                th.textContent = 'Name';
                headerRow.appendChild(th);
            } else if (field === 'primary_phone' || field === 'secondary_phone') {
                if (!checkedFields.includes('phone')) {
                    th.textContent = 'Phone';
                    headerRow.appendChild(th);
                }
            } else if (field === 'phone') {
                th.textContent = 'Phone';
                headerRow.appendChild(th);
            } else if (field === 'fema_id') {
                th.textContent = 'FEMA-ID';
                headerRow.appendChild(th);
            } else if (field === 'hh_size') {
                th.textContent = 'HH Size';
                headerRow.appendChild(th);
            } else if (field === 'own_rent') {
                th.textContent = 'Own/Rent';
                headerRow.appendChild(th);
            } else {
                th.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                headerRow.appendChild(th);
            }
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
        survivors.forEach(survivor => {
            const tr = document.createElement('tr');
            let skipName = false;
            let skipPhone = false;
            checkedFields.forEach(field => {
                // Merge fname and lname into "Name" column
                if ((field === 'fname' || field === 'lname') && !skipName) {
                    const td = document.createElement('td');
                    td.textContent = (survivor.fname || '') + ' ' + (survivor.lname || '');
                    tr.appendChild(td);
                    skipName = true;
                } else if (field === 'name') {
                    const td = document.createElement('td');
                    td.textContent = (survivor.fname || '') + ' ' + (survivor.lname || '');
                    tr.appendChild(td);
                } else if ((field === 'primary_phone' || field === 'secondary_phone') && !skipPhone) {
                    const td = document.createElement('td');
                    td.innerHTML = (survivor.primary_phone || '') +
                        (survivor.secondary_phone ? '<br>' + survivor.secondary_phone : '');
                    tr.appendChild(td);
                    skipPhone = true;
                } else if (field === 'phone') {
                    const td = document.createElement('td');
                    td.innerHTML = (survivor.primary_phone || '') +
                        (survivor.secondary_phone ? '<br>' + survivor.secondary_phone : '');
                    tr.appendChild(td);
                } else if (field === 'hh_size') {
                    const td = document.createElement('td');
                    if (survivor['hh_size'] === null || survivor['hh_size'] === undefined) {
                        td.innerHTML = '';
                    } else {
                        td.innerHTML = `<span class="hh-size">${survivor['hh_size']}</span>`;
                    }
                    tr.appendChild(td);
                } else if (field === 'own_rent') {
                    const td = document.createElement('td');
                    td.textContent = survivor['own_rent'] == 0 ? 'Own' : 'Rent';
                    tr.appendChild(td);
                } else if (field !== 'fname' && field !== 'lname' && field !== 'primary_phone' && field !== 'secondary_phone') {
                    const td = document.createElement('td');
                    td.textContent = survivor[field] !== undefined ? survivor[field] : '';
                    tr.appendChild(td);
                }
            });
            // Options column
            const tdOptions = document.createElement('td');
            tdOptions.className = 'options-icon';
            tdOptions.style.position = 'relative';
            tdOptions.innerHTML = `⋮
                <div class="dropdown-menu" style="right:0; left:auto; min-width:120px; position:absolute;">
                    <a href="/admin/survivors/edit/${survivor.id}">Edit</a>
                    <form action="/admin/survivors/delete/${survivor.id}" method="POST" style="margin: 0;">
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
