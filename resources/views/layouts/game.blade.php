<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Put vladara')</title>

    <link rel="stylesheet" href="{{ asset('css/avantura.css') }}?v={{ time() }}">
</head>
<body class="game-body">
    <div class="pv-script-switch" aria-label="Izbor pisma">
        <button type="button" id="scriptLatBtn" class="is-active">Lat</button>
        <button type="button" id="scriptCyrBtn">&#1035;&#1080;&#1088;</button>
    </div>

    @yield('content')

    <script src="{{ asset('js/avantura.js') }}?v={{ time() }}"></script>
</body>
</html>
