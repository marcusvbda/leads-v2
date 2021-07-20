@extends("templates.admin")
@section('title',$qty > 0 ? "($qty) Notificações" : "Notificações")

@section('breadcrumb')
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
		  <li class="breadcrumb-item active" aria-current="page">Notificações</li>
		</ol>
	</nav>
@endsection
@section('content')
<notification-list
	:user='@json($user)'
>
</notification-list>
@endsection
