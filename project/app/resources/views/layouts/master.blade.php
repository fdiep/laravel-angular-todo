<html>
    <head>
        <title>Laravel App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        {{-- Fonts --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">
        {{-- CSS --}}
        <link rel="stylesheet" href="{{ elixir('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    </head>
    <body ng-app="ScannerApp" ng-controller="MainController">

      @yield('content')

      {{-- JS --}}
      <script src="{{ elixir('js/vendor.js') }}"></script>
      <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
