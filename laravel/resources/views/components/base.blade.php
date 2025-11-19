<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{--  Document Title  --}}
  <title>{{ $title ?? 'Unknown' }}</title>
  <link rel="stylesheet" href="{!! url('pico.min.css') !!}">
  {{--  HTML Extra Heading  --}}
  {!! $head ?? '' !!}
</head>
<body>
  {{--  Content  --}}
  <main class="container">
    {!! $slot !!}
  </main>
</body>
</html>