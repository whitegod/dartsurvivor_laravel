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

            <table>
                <thead>
                    <tr>
                        <th>VIN - Last 7</th>
                        <th>Location</th>
                        <th>Address</th>
                        <th>Unit</th>
                        <th>Status (Color Code)</th>
                        <th>Total Beds</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ttus as $ttu)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::substr($ttu->vin, -7) }}</td>
                        <td>{{ $ttu->location }}</td>
                        <td>{{ $ttu->address }}</td>
                        <td>Lot {{ $ttu->lot }}</td>
                        <td><span class="status {{ strtolower($ttu->status) }}">{{ ucfirst($ttu->status) }}</span></td>
                        <td><span class="total-beds">{{ $ttu->total_beds }}</span></td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.ttus.edit', $ttu->id) }}">Edit</a>
                                <form action="{{ route('admin.ttus.delete', $ttu->id) }}" method="POST" style="margin: 0;">
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
</script>
@endsection
