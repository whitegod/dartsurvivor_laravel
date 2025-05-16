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

        .text-filter {
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

            .filter, .text-filter {
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
                <form method="GET" action="{{ route('admin.detailedSearch') }}">
                    <div class="filters">
                        <div class="filter">
                            <label for="scope">Scope</label>
                            <select id="scope" name="scope">
                                <option>All</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="filter">
                            <label for="keyword">Keyword</label>
                            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="Type Here">
                        </div>
                        <div class="filter">
                            <label for="countBy">Count By (Field)</label>
                            <select id="countBy" name="countBy">
                                <option value="author" {{ request('countBy') == 'author' ? 'selected' : '' }}>Author</option>
                                <option value="address" {{ request('countBy') == 'address' ? 'selected' : '' }}>Address</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                    </div>
                    <div class="text-filter">
                        <select name="search_by_field" id="search_by_field" onchange="updatePlaceholder()">
                            <option value="author" {{ request('search_by_field') == 'author' ? 'selected' : '' }}>Author</option>
                            <option value="address" {{ request('search_by_field') == 'address' ? 'selected' : '' }}>Address</option>
                            <!-- Add more options as needed -->
                        </select>
                        <input type="text" id="search_text" name="text" value="{{ request('text') }}"
                            placeholder="{{ request('search_by_field') == 'address' ? 'Enter Address' : 'Enter Author Name' }}">
                    </div>
                    <div class="search-button-container">
                        <button type="submit">Search</button>
                    </div>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        @if($countBy === 'author')
                            <th>Author</th>
                        @else
                            <th>Address</th>
                        @endif
                        <th>Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results ?? [] as $row)
                        <tr>
                            @if($countBy === 'author')
                                <td>{{ $row->author }}</td>
                            @else
                                <td>{{ $row->address }}</td>
                            @endif
                            <td>{{ $row->count }}</td>
                            <td><a href="#" class="view-link">View</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No results found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>          
        </div>
    </div>
</section>
<script>
function updatePlaceholder() {
    var select = document.getElementById('search_by_field');
    var input = document.getElementById('search_text');
    if (select.value === 'address') {
        input.placeholder = 'Enter Address';
    } else if (select.value === 'author') {
        input.placeholder = 'Enter Author Name';
    } else {
        input.placeholder = 'Type Here';
    }
}
document.addEventListener('DOMContentLoaded', updatePlaceholder);
</script>
@endsection
