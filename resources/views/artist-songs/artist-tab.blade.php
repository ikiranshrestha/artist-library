@extends('layouts.master')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>First Year Release</th>
            <th>No. of Released Albums</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="data-container">
        <!-- User data will be displayed here -->
    </tbody>
</table>


<script>
    console.log("artist tab");
    $(document).ready(function() {
        // Make an AJAX request to the API endpoint
        $.get('/api/artists', function(data) {
            // Process and display the user data in the table
            var dataContainer = $('#data-container');
            dataContainer.empty(); // Clear any previous data

            // Loop through the user data and add it to the table
            $.each(data, function(index, user) {
                var row = '<tr>' +
                    '<td>' + user.name + '</td>' +
                    '<td>' + user.first_year_release + '</td>' +
                    '<td>' + user.no_of_albums_released + '</td>' +
                    '</tr>';
                dataContainer.append(row);
            });
        });
    });
</script>


@endsection
