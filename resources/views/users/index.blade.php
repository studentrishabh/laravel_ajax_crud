<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AJAX CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- <script src="{{ assets('js/add.js') }}"></script> --}}

    <form action="{{route('logout')}}" method="POST">
        @csrf
<div class="container mt-5">
    <h2 class="text-center">Laravel AJAX CRUD</h2>
    <button class="btn btn-success mb-3" onclick="showUserForm()">Add User</button>
    <button class="btn btn-danger mb-3" type="submit">Logout</button>
    <h2>Welcome to the Dashboard, {{ Auth::user()->name }}!</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="users-table"></tbody>
    </table>
</div>
    </form>

<!-- User Modal -->
<div class="modal" id="userModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Form</h4>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" >
                    
                    <input type="hidden" id="userId">
                    <div class="mb-3">
                        <label>Name:</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3" id="password-field">
                        <label>Password:</label>
                        <input type="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        fetchUsers();

        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            let id = $('#userId').val();
            let url = id ? `/users/${id}` : '/users';
            let type = id ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: type,
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    fetchUsers();
                    closeModal();
                }
            });
        });
    });

    function fetchUsers() {
        $.get('/users', function (users) {
            let rows = '';
            users.forEach(user => {
                rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <button onclick="editUser(${user.id})">Edit</button>
                        <button onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                </tr>`;
            });
            $('#users-table').html(rows);
        });
    }

    function editUser(id) {
        $.get(`/users/${id}`, function (user) {
            $('#userId').val(user.id);
            $('#name').val(user.name);
            $('#email').val(user.email);
            $('#password-field').hide();
            $('#userModal').show();
        });
    }

    function deleteUser(id) {
        $.ajax({
            url: `/users/${id}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: fetchUsers
        });
    }

    function showUserForm() {
        $('#userForm')[0].reset();
        $('#password-field').show();
        $('#userModal').show();
    }

    function closeModal() {
        $('#userModal').hide();
    }
</script>
</body>
</html>
