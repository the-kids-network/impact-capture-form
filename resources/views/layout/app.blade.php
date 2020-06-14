<!DOCTYPE html>
<html lang="en" class="tkn-app">
    <head>
        <!-- Meta Information -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        <!-- Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>

        <!-- CSS -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <!-- Scripts -->
        @yield('scripts', '')

        @if (App::environment('production'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-167891964-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-167891964-1'); 
        </script>
        @endif

        <!-- Window properties -->
        <script>
            window.csrfToken = "{{ csrf_token() }}"
            window.env = "{{ config('app.env') }}"
        </script>

    </head>

    <body class="@unless(empty($body_class)){{$body_class}}@endunless">
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

            <!-- Application Level Modals -->
            @if (Auth::check())
                @include('modals.support')
            @endif
        </div>

        <!-- JavaScript -->
        <script src="{{ mix('js/app.js') }}"></script>

        @yield('body-scripts', '')
    </body>

</html>