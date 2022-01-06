<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        html, body, #app {
            height: 100vh;
        }
    </style>
    <title>BM Backoffice</title>
</head>
<body>
<div id="app">
    <backoffice></backoffice>
</div>
<script src="{{ mix('js/app.js') }}" type="text/javascript">
</script>
</body>
</html>
