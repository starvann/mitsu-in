<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{--  Document Title  --}}
  <title>{{ $title ?? 'Unknown' }}</title>
  <link rel="icon" type="image/x-icon" href="{{ url('assets/img/mitsu-in.ico') }}">
  <link rel="stylesheet" href="{!! url('assets/css/stylev0.css') !!}">
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
  <script>
    setTimeout(() => {
      let successMsg = document.querySelector('.success-msg');
      if(successMsg) {successMsg.remove();}
    }, 1550);
  </script>
</body>
</html>