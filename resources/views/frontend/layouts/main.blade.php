<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title') | Pelosok Nusantara</title>

  <link rel="stylesheet" href="{{ asset('custom/css/style.css') }}">

  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-100">
  @yield('content')

  <script src="{{ asset('custom/js/script.js') }}"></script>
</body>
</html>
