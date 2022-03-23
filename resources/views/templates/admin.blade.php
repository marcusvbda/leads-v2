@extends("templates.default")
@section('body')
    @php 
        $user = \Auth::user();
        $polo_code = $user->polo->code;
        $socket_settings = config("vstack.socket_service");
    @endphp
    <socket-alert 
        polo_code="{{ $polo_code }}" 
        :socket_settings='@json($socket_settings)' 
        user_code="{{ $user->code }}"
    >
    </socket-alert>
    @include("templates.navbar")
    <div class="my-2 container-fluid">
		@yield("breadcrumb")
	</div>
    <div class="container-fluid pb-5" >
        @yield("content")
    </div>
@endsection