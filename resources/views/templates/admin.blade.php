@extends("templates.default")
@section('body')
    @php 
        $user = \Auth::user();
        $polo_id = $user->polo_id;
    @endphp
    <socket-alert polo_id="{{ $polo_id }}" event='notifications.user' channel="Alert"></socket-alert>
    @include("templates.navbar")
    <div class="my-2 container-fluid">
		@yield("breadcrumb")
	</div>
    <div class="container-fluid pb-5" >
        @yield("content")
    </div>
@endsection