<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Admin Dashboard</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/toastr/css/toastr.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/images/logo.png') }}' />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
</head>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('admin.common.header')
            @include('admin.common.side_menu')
            @yield('content')
            @include('admin.common.footer')
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('public/admin/assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/datatables.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>

    <script>
        // toastr popup function
        function toastrPopUp() {
          toastr.options = {
            closeButton: true,
            newestOnTop: false,
            progressBar: true,
            positionClass: 'toast-top-right',
            preventDuplicates: false,
            onclick: null,
            showDuration: '3000',
            hideDuration: '1000',
            timeOut: '5000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
          };
        }
        // Call toastrPopUp function
        toastrPopUp();

        // Get notifications and update the UI
        function getNotifications() {
          $.ajax({
            url: '{{ URL::to('/admin/notification') }}',
            method: 'GET',
            success: function(response) {
              $('#unread-notification').html(response.unread);
              $('#notification').append(response.notification);
            }
          });
        }
        // Call getNotifications() every 5 seconds using setInterval()
        setInterval(getNotifications, 5000);

        // Attach a click event listener to a button with ID "get-notifications"
        $('#get-notifications').click(getNotifications);

        // Make an AJAX call to mark all items as read
        function markAllAsRead() {
          $.ajax({
            url: '{{ URL::to('/admin/markAllRead') }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              console.log('All items have been marked as read.');
              location.reload();
            },
            error: function(xhr, status, error) {
              console.log('There was an error marking items as read.');
            }
          });
        }

        // Add a click event listener to the "Mark All As Read" link
        $('#markAllReadLink').click(function(event) {
          event.preventDefault(); // prevent the default behavior of the link
          markAllAsRead(); // Call markAllAsRead function
        });

        // Make an AJAX call to update the notification with the given id
        function urlNotification(id) {
          $.ajax({
            url: '{{ URL::to('/admin/url-notification') }}',
            type: 'GET',
            data: {
              _token: '{{ csrf_token() }}',
              id: id
            }
          });
        }
      </script>

    @yield('js')

</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
