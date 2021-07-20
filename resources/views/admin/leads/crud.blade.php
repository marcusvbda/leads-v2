@extends("templates.admin")
@section('title',"Lead")

@section('breadcrumb')
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
		  <li class="breadcrumb-item">Oportunidades</li>
		  <li class="breadcrumb-item"><a href="/admin/leads">Leads</a></li>
		  <li class="breadcrumb-item active" aria-current="page"><a href="/admin/leads/create">Cadastro</a></li>
		</ol>
	</nav>
@endsection
@section('content')
<lead-crud></lead-crud>
@endsection
