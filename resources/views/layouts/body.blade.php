@spaceless
    <!doctype html>
    <html lang="pt-BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">

        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/sharp-solid.css">

        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/sharp-regular.css">

        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/sharp-light.css">

        <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/css/bootstrap5-toggle.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.0/css/select.dataTables.css">

        <link rel="stylesheet" href="{{ url('assets/css/style.css') }}?v={{ rand(1, 1000) }}">

        <link rel="stylesheet" href="{{ url('assets/argon/css/nucleo-icons.css') }}?v={{ rand(1, 1000) }}">

        <link rel="stylesheet" href="{{ url('assets/argon/css/nucleo-svg.css') }}?v={{ rand(1, 1000) }}">

        <link rel="stylesheet" href="{{ url('assets/argon/css/argon-dashboard.css') }}?v={{ rand(1, 1000) }}">

        <link rel="icon" type="image/x-icon" href="{{ url('img/favicon.ico') }}">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/placeholder-loading@0.6.0/dist/css/placeholder-loading.min.css">
        <title>@yield('title')</title>

        @yield('style')
    </head>


    <body>

        @yield('navbar')
        <div class="max-width">
            @yield('content')
        </div>

        @routes
        <script src="{{ url('assets/jquery/jquery-3.7.1.min.js') }}"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="{{ url('assets/axios/axios.min.js') }}"></script>
        <script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ url('assets/js/script.js') }}?v={{ rand(1, 1000) }}"></script>
        <script src="{{ url('assets/swal/dist/sweetalert2.all.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/js/bootstrap5-toggle.jquery.min.js"></script>
        <script src="{{ url('assets/argon/js/core/popper.min.js') }}"></script>
        <script src="{{ url('assets/argon/js/argon-dashboard.min.js') }}?v={{ rand(1, 1000) }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.5.2/jscolor.min.js"></script>
        @yield('script_general')
        @yield('script')
    </body>

    </html>
@endspaceless
