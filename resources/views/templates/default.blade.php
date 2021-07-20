<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<script> window.laravel = {!! $javascript_globals !!}</script>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
		@yield("header")
		<link rel="icon" href="/favicon.ico" />
		<title>@yield("title") | {{ config("app.name") }}</title>
	</head>
	<body>
		<div id="app">
			@yield("body")
			@include("templates.alerts")
		</div>
		<script src="{{ mix('assets/js/manifest.js')}}"></script>
		<script src="{{ mix('assets/js/vendor.js')}}"></script>
		<script src="{{ mix('assets/js/app.js') }}"></script>
		@yield("scripts")
	</body>
</html>
