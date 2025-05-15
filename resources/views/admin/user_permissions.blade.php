@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-container input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        .search-container svg {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .search-container input[type="checkbox"] {
            margin-right: 5px;
        }

        .add-user {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-reset,
        .btn-remove,
        .btn-reactivate {
            width: 120px;
            text-align: center;
            display: inline-block;
            background-color: #f0f0b5;
            border: none;
            border-radius: 3px;
            padding: 5px 0;
            cursor: pointer;
        }

        .btn-remove {
            background-color: #f5b5b5;
        }

        .btn-reactivate {
            background-color: #facab0;
        }
    </style>
@endsection

@section('content')
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="toolbar">
                <div class="search-container">
                    <input type="text" placeholder="Search">
                    <svg class="filter-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L16 11.414V17a1 1 0 01-.293.707l-3 3A1 1 0 0112 21v-6.586l-4.707-4.707A1 1 0 017 6V4z" />
                    </svg>
                    <label>
                        <input type="checkbox" checked>
                        Show Inactive
                    </label>
                </div>
                <button class="add-user">Add User</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Permission Level</th>
                        <th>Records Authored</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{ $user->status ? '' : 'inactive-user' }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->email }} {{ $user->status ? '' : '(INACTIVE)' }}</td>
                        <td>
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </td>
                        <td>
                            {{ $user->records_authored }}
                        </td>
                        <td>
                            @if($user->status)
                                <button class="btn-reset">Reset Password</button>
                                <button class="btn-remove">Remove User</button>
                            @else
                                <button class="btn-reactivate">Reactivate</button>
                                <button class="btn-remove">Remove User</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
document.querySelector('input[type="checkbox"][checked]').addEventListener('change', function() {
    const showInactive = this.checked;
    document.querySelectorAll('tr.inactive-user').forEach(row => {
        row.style.display = showInactive ? '' : 'none';
    });
});

// On page load, hide inactive users if checkbox is unchecked (for robustness)
window.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.querySelector('input[type="checkbox"][checked]');
    if (!checkbox.checked) {
        document.querySelectorAll('tr.inactive-user').forEach(row => {
            row.style.display = 'none';
        });
    }
});

// Email search filter
document.querySelector('.search-container input[type="text"]').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        const emailCell = row.querySelector('td:nth-child(2)');
        if (emailCell && emailCell.textContent.toLowerCase().includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

@endsection
