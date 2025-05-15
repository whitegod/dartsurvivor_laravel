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
            min-width: 100px;
            padding: 10px;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a {
            text-decoration: none;
            color: black;
            display: block;
            padding: 5px 0;
        }

        .dropdown-menu a:hover {
            background-color: #f0f0f0;
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
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header">
                <div class="search-container">
                    <input type="text" placeholder="Search">
                    <div class="filter-icon">🔍</div>
                </div>
                <a href="#" class="add-new-button">Add New</a>
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
                        <td>{{ $ttu->vin }}</td>
                        <td>{{ $ttu->location }}</td>
                        <td>{{ $ttu->address }}</td>
                        <td>{{ $ttu->unit }}</td>
                        <td><span class="status {{ strtolower($ttu->status) }}">{{ ucfirst($ttu->status) }}</span></td>
                        <td><span class="total-beds">{{ $ttu->total_beds }}</span></td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{Route('admin.ttus.edit', 1)}}">Edit</a>
                                <a href="#">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>8675309</td>
                        <td>Wilson’s RV Park</td>
                        <td>435 California Blvd.</td>
                        <td>Lot 243</td>
                        <td><span class="status occupied">Occupied</span></td>
                        <td><span class="total-beds">3</span></td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{Route('admin.ttus.edit', 1)}}">Edit</a>
                                <a href="#">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>9870911</td>
                        <td>Motor Pool</td>
                        <td>12687 Federal Way</td>
                        <td>Lot 12</td>
                        <td><span class="status pending">Pending</span></td>
                        <td><span class="total-beds">143</span></td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{Route('admin.ttus.edit', 1)}}">Edit</a>
                                <a href="#">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>6666666</td>
                        <td>Lucille’s Ranch</td>
                        <td>666 Nothell Ln.</td>
                        <td>Lot 666</td>
                        <td><span class="status ready">Ready</span></td>
                        <td><span class="total-beds">-1</span></td>
                        <td class="options-icon">
                            ⋮
                            <div class="dropdown-menu">
                                <a href="{{Route('admin.ttus.edit', 1)}}">Edit</a>
                                <a href="#">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <!-- Repeat rows as needed -->
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
</script>
@endsection
