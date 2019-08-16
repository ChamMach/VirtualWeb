<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/favicon.ico">

        <title>VirtualWEB</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background: #6da1ee;
                background: -webkit-linear-gradient(to right, #6da1ee, #3d5e94);
                background: linear-gradient(to right, #6da1ee, #3d5e94);
                color: white;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            canvas.pg-canvas {
                position: absolute;
            }
            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #ffffff;
                padding: 10px 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                border: 1px solid;
                text-transform: uppercase;
                transition: 300ms;
            }

            .links > a:hover {
                background: white;
                color: black;
                border: solid 1px white;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="particles" class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md fadeInDown animated">
                    Virtual Web
                </div>
                @if (Route::has('login'))
                    <div class="links fadeIn animated">
                        @auth
                            @if (Auth::user()->status == 1)
                                <a href="{{ route('administration') }}">Tableau de bord</a>
                            @else
                                <a href="{{ route('accueil') }}">Tableau de bord</a>
                            @endif
                            <a href="{{ route('deconnexion') }}">Déconnexion</a>
                        @else
                            <a href="{{ route('connexion') }}">Connexion</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </body>

<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/particule.min.js') }}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    particleground(document.getElementById('particles'), {
        dotColor: '#FFFF',
        lineColor: '#FFFF'
    });
}, false);
</script>
</html>
