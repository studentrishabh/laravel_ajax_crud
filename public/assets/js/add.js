
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


    console.log('add.js is successfully loaded!');
