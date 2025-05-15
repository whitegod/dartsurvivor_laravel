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

            <table>
                <thead>
                    <tr>
                        <th>FEMA-ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>HH Size</th>
                        <th>LI Date</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($survivors as $survivor)
                    <tr>
                        <td>{{ $survivor->fema_id }}</td>
                        <td>{{ $survivor->name }}</td>
                        <td>{{ $survivor->address }}</td>
                        <td>{{ $survivor->phone }}</td>
                        <td>{{ $survivor->hh_size }}</td>
                        <td>{{ $survivor->li_date }}</td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.survivors.edit', $survivor->id) }}">Edit</a>
                                <form action="{{ route('admin.survivors.delete', $survivor->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
</script>
@endsection
