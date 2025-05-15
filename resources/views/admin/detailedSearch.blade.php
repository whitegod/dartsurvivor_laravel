@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .search-container {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }

        .filter {
            display: flex;
            align-items: center;
            gap: 5px;
            flex: 1;
        }

        select, input[type="text"] {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            flex: 1;
        }

        .address-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        input[type="text"] {
            flex: 1;
        }

        .search-button-container {
            display: flex;
            justify-content: flex-end;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
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
        }

        th {
            background-color: #f5f5f5;
        }

        .view-link {
            color: #007bff;
            text-decoration: none;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
            }

            .filter, .address-filter {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="search-container">
                <div class="filters">
                    <div class="filter">
                        <label for="scope">Scope</label>
                        <select id="scope">
                            <option>All</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="filter">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" placeholder="Type Here">
                    </div>
                    <div class="filter">
                        <label for="countBy">Count By (Field)</label>
                        <select id="countBy">
                            <option>Author</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <div class="address-filter">
                    <select>
                        <option>Address</option>
                        <!-- Add more options as needed -->
                    </select>
                    <input type="text" placeholder="5309 Aerosmith Rd.">
                </div>

                <div class="search-button-container">
                    <button>Search</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Address</th>
                        <th>Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Carol Hughes</td>
                        <td>5309 Aerosmith Rd.</td>
                        <td>53</td>
                        <td><a href="#" class="view-link">View</a></td>
                    </tr>
                    <tr>
                        <td>Damien Weyas</td>
                        <td>5309 Aerosmith Rd</td>
                        <td>12</td>
                        <td><a href="#" class="view-link">View</a></td>
                    </tr>
                    <tr>
                        <td>Garrett Thesborne</td>
                        <td>5309 Aerosmith Rd</td>
                        <td>231</td>
                        <td><a href="#" class="view-link">View</a></td>
                    </tr>
                    <tr>
                        <td>Sweet Caroline</td>
                        <td>5309 Aerosmith Rd</td>
                        <td>1</td>
                        <td><a href="#" class="view-link">View</a></td>
                    </tr>
                    <tr>
                        <td>Pedro Weredugo</td>
                        <td>5309 Aerosmith Rd</td>
                        <td>9</td>
                        <td><a href="#" class="view-link">View</a></td>
                    </tr>
                </tbody>
            </table>          
        </div>
    </div>
</section>

@endsection
