@extends('layouts.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<table class="table">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="data-container">
        <!-- User data will be displayed here -->
    </tbody>
</table>

<!-- Update Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for updating user information -->
                <form id="updateUserForm">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="dob">Email</label>
                        <input type="date" class="form-control" id="dob" name="dob">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                        <div class="col-md-6">
                            <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                                <option value="o">Other</option>
                            </select>

                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>

    // Function to load user data and populate the table
    function loadUserData() {
        $.get('/api/user', function(data) {
            var dataContainer = $('#data-container');
            dataContainer.empty(); // Clear any previous data

            $.each(data, function(index, user) {
                var row = '<tr>' +
                    '<td>' + user.first_name + '</td>' +
                    '<td>' + user.last_name + '</td>' +
                    '<td>' + user.email + '</td>' +
                    '<td>' + user.phone + '</td>' +
                    '<td>' +
                        '<button class="btn btn-primary btn-update" data-user-id="' + user.id + '">Update</button>' +
                        '<button class="btn btn-danger btn-delete" data-user-id="' + user.id + '">Delete</button>' +
                    '</td>' +
                    '</tr>';
                dataContainer.append(row);
            });

            // Add event listeners for update and delete buttons
            addEventListeners();
        });
    }

    // Function to add event listeners for update and delete buttons
    function addEventListeners() {
        // Update button click event
        $('.btn-update').click(function() {
            var userId = $(this).data('user-id');

            $.get('/api/user/' + userId, function(user) {
             // Populate the modal fields with user data
            $('#user_id').val(user.id);
            $('#first_name').val(user.first_name);
            $('#last_name').val(user.last_name);
            $('#phone').val(user.phone);
            $('#email').val(user.email);
            $('#dob').val(user.dob);
            $('#password').val(user.password); // You may want to avoid displaying the password for security reasons.
            $('#address').val(user.address);

            // Show the update modal
            $('#updateUserModal').modal('show');
        });

            console.log('Update clicked for user ID: ' + userId);

            $('#saveChangesBtn').click(function () {
            var userId = $('#user_id').val();

            // Create a data object with updated user information
            var userData = {
                id: $('#user_id').val(),
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                phone: $('#phone').val(),
                password: $('#password').val(),
                address: $('#address').val(),
                email: $('#email').val(),
                dob: $('#dob').val(),
                gender: $('#gender').val()
            };

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(csrfToken);
            // Send the updated data to the API via AJAX
            $.ajax({
                url: '/api/user/' + userId,
                type: 'PUT', // Use PUT method for updating data
                data: userData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    // Handle the API response (e.g., show a success message)
                    console.log('User data updated successfully:', response);
                    // Close the modal after successful update
                    $('#updateUserModal').modal('hide');
                    // Reload user data in the table
                    loadUserData();
                },
                error: function (xhr, status, error) {
                    // Handle the API error (e.g., show an error message)
                    console.error('Error updating user data:', error);
                }
            });
        });
    });

        // Function to handle delete button click
    $('.btn-delete').click(function() {
        var userId = $(this).data('user-id');
        $('#confirmDeleteBtn').data('user-id', userId); // Store the user ID for later use
        $('#deleteUserModal').modal('show'); // Show the delete confirmation modal
    });

    // Function to handle confirm delete button click
    $('#confirmDeleteBtn').click(function() {
        var userId = $(this).data('user-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(csrfToken);
            // Send the updated data to the API via AJAX
            $.ajax({
                url: '/api/user/' + userId,
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    // Handle the API response (e.g., show a success message)
                    console.log('User data updated successfully:', response);
                    // Close the modal after successful update
                    $('#updateUserModal').modal('hide');
                    // Reload user data in the table
                    loadUserData();
                },
                error: function (xhr, status, error) {
                    // Handle the API error (e.g., show an error message)
                    console.error('Error updating user data:', error);
                }
            });
        console.log('Delete confirmed for user ID: ' + userId);
        // Close the delete modal
        $('#deleteUserModal').modal('hide');
    });
        }

        // Load user data when the document is ready
        $(document).ready(function() {
            loadUserData();
        });
</script>

@endsection