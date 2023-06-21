<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ __('Web Console') }}</title>
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/xterm@5.2.1/css/xterm.min.css' />
</head>

<body>
    <x-web-console::terminal :url="$url" />
    <script src='https://cdn.jsdelivr.net/npm/xterm@5.2.1/lib/xterm.min.js'></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
