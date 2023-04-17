<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Pharmacy Dashboard</title>
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
            @include('pharmacy.common.header')
            @include('pharmacy.common.side_menu')
            @yield('content')
            @include('pharmacy.common.footer')
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
    <script src="{{ asset('public/admin/assets/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/datatables.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>
    <script>
        /*toastr popup function*/
        function toastrPopUp() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "3000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }

        /*toastr popup function*/
        toastrPopUp();
    </script>
    @yield('js')
    <script>
        function getNotifications() {
            $.ajax({
                url: '{{ URL::to('/pharmacy/notification') }}',
                method: 'GET',
                success: function(response) {
                    $('#unread-notification').html(response.unread);
                    $('#notification').append(response.notification);
                },
            });
        }

        // Call getNotifications() every 5 seconds using setInterval()
        setInterval(function() {
            getNotifications();
        }, 5000);


        $('#get-order-notifications').click(function() {
            getNotifications();
        });
    </script>
    <script>
        function UrlNotification(id) {
            $.ajax({
                url: '{{ URL::to('/pharmacy/url-notification') }}',
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },

            });
        }
    </script>
    <script>
        function markAllRead(id) {
            // alert(id);
            $.ajax({
                url: '{{ URL::to('/pharmacy/markAllRead') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                success: function(response) {
                        console.log('All items have been marked as read.');
                        location.reload();
                    },

            });
        }
    </script>
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
