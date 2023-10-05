@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>
        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#user-tab" data-content-url="{{ route('core.user.user-tab') }}">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#artist-tab" data-content-url="{{ route('artist-song.artist-tab') }}">Artist</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="user-tab" class="tab-pane fade show active">
                <h2>User Dashboard</h2>
                <div id="user-content">
                    <!-- Content will be loaded here via AJAX -->
                </div>
            </div>

            <!-- Artist Tab Content -->
            <div id="artist-tab" class="tab-pane fade">
                <h2>Artist</h2>
                <div id="artist-content">
                    <!-- Content will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script>
    // Function to load content for the active tab
    function loadActiveTabContent() {
        activeTabContentUrl = $('#myTabs .nav-link.active').data('content-url');
        $.ajax({
            url: activeTabContentUrl,
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $('.tab-pane.active').html(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.log('Initial content load failed. URL:', activeTabContentUrl);
            }
        });
    }

    // Initially load the active tab's content from the backend API when the document is ready
    $(document).ready(function () {
        loadActiveTabContent();
    });

    // Handle tab click events
    $('#myTabs a').click(function (e) {
        e.preventDefault();
        const contentUrl = $(this).data('content-url');
        const tabId = $(this).attr('href');

        // Update the activeTabContentUrl variable
        activeTabContentUrl = contentUrl;

        // Load tab content via AJAX from the backend API
        $.ajax({
            url: contentUrl,
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                console.log('AJAX Request Successful. URL:', contentUrl);
                console.log('Response:', response);
                $(tabId + ' .tab-pane').html(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.log('AJAX Request failed. URL:', contentUrl);
            }
        });

        // Show the clicked tab
        $(this).tab('show');
    });
</script>

@endsection

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
