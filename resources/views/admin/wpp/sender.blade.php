@extends("templates.admin")
@section('title',"Whatsapp Sender")

@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item">
						<a href="/admin" class="link">Dashboard</a>
					</li>
		 			<li class="breadcrumb-item active" aria-current="page">Whatsapp Sender</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@php
	$user = Auth::user();
	$session = $user->getWppLastSession();
	// 5514997569008@c.us
@endphp
@section('content')
<wpp-sender
	:session='@json($session)'
>
</wpp-sender>
@endsection
