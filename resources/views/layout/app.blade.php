<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta Information -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        <!-- Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
        {{--<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>--}}
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

        <!-- CSS -->
        <link href="/css/sweetalert.css" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <style>
            .footer{
                font-size: 16px;
            }

            .red-links a{
                color: #b84d45;
            }

            .bg-white{
                background-color: white;
            }

            .p-t-xl{
                padding-top: 40px;
            }

            .p-b-xl{
                padding-bottom: 40px;
            }
        </style>

        <!-- Scripts -->
        @yield('scripts', '')

        <!-- Global Spark Object -->
        <script>
            window.Spark = <?php echo json_encode(array_merge(
                App\Configuration\Spark::scriptVariables(), []
            )); ?>;
        </script>

    </head>

    <body class="with-navbar @unless(empty($body_class)){{$body_class}}@endunless">
        <div id="app" v-cloak>
            <!-- Navigation -->
            @if (Auth::check())
                @include('layout.nav.user')
            @else
                @include('layout.nav.guest')
            @endif

            @if(session('status'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')

            <!-- Footer -->
            <div class="container-fluid p-b-xl p-t-xl m-t-md red-links bg-white footer">
            
            </div>

            <!-- Application Level Modals -->
            @if (Auth::check())
                @include('modals.support')
                @include('modals.session-expired')
            @endif
        </div>

        <!-- JavaScript -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="/js/sweetalert.min.js"></script>

        @yield('body-scripts', '')
    </body>

</html>
