<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="version" content="@yield('version', config('app.version'))">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>

    <style>
        body, html {
            background: url('/img/landing-bg.png');
            background-repeat: repeat;
            background-size: 300px 200px;
            height: 100%;
            margin: 0;
            font-family: 'Open Sans';
        }

        .links {
            padding-top: 1em;
            text-align: center;
        }
        .links a {
            text-decoration: none;
        }
        .links button {
            cursor: pointer;
            padding: 1rem 1.5rem 1rem 1.5rem;
            width: auto;
            height: auto;
            background-color: #34a5dd;
            border: 0;
            border-radius: 0.5rem;
            color: white;
            font-size: 3vw;
            font-weight: 400;
            text-transform: uppercase;
        }

        .main {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        .logo {
            max-width: 750px;
            width: 80vw;
        }
        .welcome-text {
            font-size: 6vw;
            text-align: center;
            
        }

        @media screen and (min-width: 750px) {
            .links button {
                font-size: 1.5rem;
            }
            .welcome-text {
                font-size: 3rem;
            }
        }
    
    </style>
</head>
<body>
    <div class="container">
        <nav class="links">
            <a href="/login" style="margin-right: 15px;">
                <button>
                    Login
                </button>
            </a>
        </nav>

        <div class="main">
            <h1 class="welcome-text">
                <img class="logo" src="/img/color-logo.png">
                <br>Session Report Database
            </h1>
        </div>
    </div>
</body>
</html>
