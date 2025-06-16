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
<section id="UserPermissions">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                <button class="add-user" onclick="openAddUserModal()">Add User</button>
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
                                <button type="button" class="btn-reset" onclick="openResetPasswordModal({{ $user->id }})">Reset Password</button>
                                <form action="{{ route('admin.user_permissions.remove', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove" onclick="return confirm('Are you sure you want to remove this user?')">Remove User</button>
                                </form>
                            @else
                                <form action="{{ route('admin.user_permissions.reactivate', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-reactivate">Reactivate</button>
                                </form>
                                <form action="{{ route('admin.user_permissions.remove', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove" onclick="return confirm('Are you sure you want to remove this user?')">Remove User</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add User Modal (styled) -->
<div id="addUserModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:30px; border-radius:8px; min-width:320px; position:relative;">
        <h3>Add New User</h3>
        <form id="addUserForm" method="POST" action="{{ route('admin.user_permissions.add') }}">
            @csrf
            <div style="margin-bottom:10px;">
                <label>Email:</label>
                <input type="email" name="email" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label>Name:</label>
                <input type="text" name="name" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label>Password:</label>
                <input type="password" name="password" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label>Role:</label>
                <select name="role_id" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    @foreach(\App\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="text-align:right;">
                <button type="button"
                        onclick="closeAddUserModal()"
                        style="margin-right:10px; background-color:#6c757d; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                    Cancel
                </button>
                <button type="submit"
                        class="btn-submit-user"
                        style="background-color:#007bff; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:30px; border-radius:8px; min-width:320px; position:relative;">
        <h3>Reset Password</h3>
        <form id="resetPasswordForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:10px;">
                <label>New Password:</label>
                <input type="password" name="password" id="reset-password" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label>Retype New Password:</label>
                <input type="password" name="password_confirmation" id="reset-password-confirm" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div id="reset-password-error" style="color:red; display:none; margin-bottom:10px;">
                Passwords do not match.
            </div>
            <div style="text-align:right;">
                <button type="button"
                        onclick="closeResetPasswordModal()"
                        style="margin-right:10px; background-color:#6c757d; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                    Cancel
                </button>
                <button type="submit"
                        class="btn-submit-reset"
                        style="background-color:#007bff; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

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

// Open Add User Modal
function openAddUserModal() {
    document.getElementById('addUserModal').style.display = 'flex';
}

// Close Add User Modal
function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}

// Only attach open modal to the toolbar Add User button
document.querySelector('.add-user').addEventListener('click', function(e) {
    e.preventDefault();
    openAddUserModal();
});

function openResetPasswordModal(userId) {
    var modal = document.getElementById('resetPasswordModal');
    var form = document.getElementById('resetPasswordForm');
    // Set the form action to the correct route
    form.action = '/admin/user_permissions/' + userId + '/reset-password';
    modal.style.display = 'flex';
}
function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').style.display = 'none';
}

// Validate password match in Reset Password Modal
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    var pass = document.getElementById('reset-password').value;
    var confirm = document.getElementById('reset-password-confirm').value;
    var errorDiv = document.getElementById('reset-password-error');
    if (pass !== confirm) {
        errorDiv.style.display = 'block';
        e.preventDefault();
    } else {
        errorDiv.style.display = 'none';
    }
});
</script>

@endsection
