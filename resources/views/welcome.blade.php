<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
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

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .subtitle {
                font-size: 44px;
                font-style: italic;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .btn-primary {
                color: #fff;
                background-color: #337ab7;
                border-color: #2e6da4;
            }

            .btn-primary:hover {
                color: #fff;
                background-color: #286090;
                border-color: #204d74;
            }

            .btn {
                display: inline-block;
                margin-bottom: 0;
                font-weight: 400;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                -ms-touch-action: manipulation;
                touch-action: manipulation;
                cursor: pointer;
                background-image: none;
                border: 1px solid transparent;
                padding: 6px 12px;
                font-size: 20px !important;
                line-height: 1.42857143;
                border-radius: 4px;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                font-family: inherit;
                -webkit-appearance: button;
                text-transform: none;
                overflow: visible;
                color: inherit;
                margin: 0;
                -webkit-writing-mode: horizontal-tb !important;
                text-rendering: auto;
                color: buttontext;
                letter-spacing: normal;
                word-spacing: normal;
                text-indent: 0px;
                text-shadow: none;
                align-items: flex-start;
                background-color: buttonface;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Eiprice Coding Challenge
                </div>

                <div class="subtitle m-b-md">
                    Felipe Pacífico de Campos
                </div>

                <div class="links">
                    <a class="btn btn-primary" href="{{  route('report.download') }}">Download report</a>
                    <a href="https://github.com/felipedecampos/eiprice-coding-challenge">GitHub</a>
                    <a href="https://felipedecampos.github.io/">Website</a>
                    <a href="https://www.linkedin.com/in/felipepacificodecampos/">LinkedIn</a>
                </div>
            </div>
        </div>
    </body>
</html>
