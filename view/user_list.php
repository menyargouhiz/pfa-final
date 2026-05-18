<?php
include("../config/database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>

    <link rel="stylesheet" href="users_list.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- ================= TOASTS ================= -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="errorToast" class="toast text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="errorMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="container mt-5">

        <h1 class="text-center mb-4">Users List</h1>

        <!-- SEARCH -->
        <div class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
                <button class="btn btn-outline-secondary" id="searchBtn">Search</button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card shadow">
            <div class="card-body">

                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="4">Loading...</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>


    <!-- ================= EDIT MODAL (Template) ================= -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editUserId" name="idu">

                        <label>Username</label>
                        <input type="text" id="editUsername" name="user_name" class="form-control" required>

                        <label>Email</label>
                        <input type="email" id="editEmail" name="email" class="form-control" required>

                        <label>Password</label>
                        <input type="password" id="editPassword" name="password" class="form-control">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= DELETE MODAL (Template) ================= -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete user <strong id="deleteUserName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let allUsers = [];
        let selectedUserId = null;

        // Initialize
        async function init() {
            await loadUsers();
        }

        // Load users from API
        async function loadUsers(searchTerm = '') {
            try {
                const formData = new FormData();
                formData.append('action', searchTerm ? 'search' : 'getAll');
                if (searchTerm) {
                    formData.append('name', searchTerm);
                }

                const res = await fetch('../controller/traitement.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (!data.success) {
                    showError('Failed to load users: ' + data.error);
                    return;
                }

                allUsers = data.data;
                renderTable();

            } catch (err) {
                console.error('Error:', err);
                showError('Connection error');
            }
        }

        // Render table
        function renderTable() {
            const tbody = document.getElementById('usersTableBody');
            
            if (allUsers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4">No users found</td></tr>';
                return;
            }

            tbody.innerHTML = allUsers.map((user, idx) => `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${user.nom}</td>
                    <td>${user.email}</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="openEditModal(${user.id}, '${user.nom}', '${user.email}')">
                            Update
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="openDeleteModal(${user.id}, '${user.nom}')">
                            Delete
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Open edit modal
        function openEditModal(id, nom, email) {
            selectedUserId = id;
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = nom;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPassword').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }

        // Open delete modal
        function openDeleteModal(id, nom) {
            selectedUserId = id;
            document.getElementById('deleteUserName').textContent = nom;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Save user
        document.getElementById('saveBtn').addEventListener('click', async function() {
            const username = document.getElementById('editUsername').value;
            const email = document.getElementById('editEmail').value;
            const password = document.getElementById('editPassword').value;

            if (!username || !email) {
                showError('Username and email required');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('idu', selectedUserId);
                formData.append('user_name', username);
                formData.append('email', email);
                if (password) {
                    formData.append('password', password);
                }

                const res = await fetch('../controller/update_user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (!data.success) {
                    showError(data.error);
                    return;
                }

                showSuccess('User updated successfully');
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                await loadUsers();

            } catch (err) {
                console.error('Error:', err);
                showError('Update failed');
            }
        });

        // Delete user
        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            try {
                const formData = new FormData();
                formData.append('id', selectedUserId);

                const res = await fetch('../controller/delete_user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (!data.success) {
                    showError(data.error);
                    return;
                }

                showSuccess('User deleted successfully');
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                await loadUsers();

            } catch (err) {
                console.error('Error:', err);
                showError('Delete failed');
            }
        });

        // Search
        document.getElementById('searchBtn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value;
            loadUsers(searchTerm);
        });

        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        // Toast helpers
        function showSuccess(message) {
            document.getElementById('toastMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            toast.show();
        }

        // Load on page load
        init();
    </script>

</body>

</html>