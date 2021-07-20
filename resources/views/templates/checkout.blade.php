<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<script> window.laravel = {!! $javascript_globals !!}</script>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="icon" href="/assets/images/favicon.ico" />
		<link rel="stylesheet" href="{{ asset('assets/checkout/css/app.css') }}">
		<title>@yield("title")</title>
		@yield("header")
	</head>
	<body>
		<div id="app">
			@if(@$link->data->show_counter)
				@include("checkout.partials.countdown-navbar")
			@endif
			@if(@$link->data->show_notifications)
				@include("checkout.partials.notifications")
			@endif
			@yield("content")
			@include("templates.alerts")
		</div>
		<script src="{{ asset('assets/checkout/js/manifest.js')}}"></script>
		<script src="{{ asset('assets/checkout/js/vendor.js')}}"></script>
		<script src="{{ asset('assets/checkout/js/app.js') }}"></script>
		@yield("scripts")
	</body>
</html>