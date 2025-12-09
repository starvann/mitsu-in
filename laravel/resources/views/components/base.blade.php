<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{--  Document Title  --}}
  <title>{{ $title ?? 'Unknown' }}</title>
  <link rel="stylesheet" href="{!! url('assets/css/style.css') !!}">
  {{--  HTML Extra Heading  --}}
  {!! $head ?? '' !!}
</head>
<body @isset($bodyClass) class="{{ $bodyClass }}" @endisset>
  {{--  Content  --}}
  @if(isset($noMain))
    {!! $slot !!}
  @else
  <main class="{{ $mainClass ?? 'container' }}" {{ $attributes->except(['class']) }}>
    {!! $slot !!}
  </main>
  @endif
</body>
</html>