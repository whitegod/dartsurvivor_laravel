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
                    <tr>
                        <td>123455</td>
                        <td>Dave Kaminsky</td>
                        <td>4831 Wetsone Dr.</td>
                        <td>(123) 456-7890</td>
                        <td>5</td>
                        <td>Dec 1, 2046</td>
                        <td class="options-icon">⋮</td>
                    </tr>
                    <tr>
                        <td>124566</td>
                        <td>Jane Fonda</td>
                        <td>1234 Alphabet Dr.</td>
                        <td>(123) 222-2221</td>
                        <td>1</td>
                        <td>Jan 5, 2010</td>
                        <td class="options-icon">⋮</td>
                    </tr>
                    <tr>
                        <td>190762</td>
                        <td>Charles Xavier</td>
                        <td>9998 State Park St.</td>
                        <td>(123) 458-2896</td>
                        <td>22</td>
                        <td>Aug 5, 2025</td>
                        <td class="options-icon">⋮</td>
                    </tr>
                    <tr>
                        <td>999999</td>
                        <td>Test Man 82</td>
                        <td>4020 Divebar Ln.</td>
                        <td>(123) 165-8945</td>
                        <td>346</td>
                        <td>Oct 5, 1987</td>
                        <td class="options-icon">⋮</td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>        
        </div>
    </div>
</section>
@endsection
