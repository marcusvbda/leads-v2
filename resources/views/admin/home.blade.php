@extends("templates.admin")
@section('title',"Página Inicial")
@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
		 			<li class="breadcrumb-item active" aria-current="page">Página Inicial</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@section('content')
<div class="row mt-4  welcome-page">
	<div class="col-12">
		<div class="row">
			<div class="col-12">
				<h1 class="name">Olá, {{$user->first_name}}!</h1>
				@if(!$user->last_logged_at)
					<p>Este é seu primeiro acesso</p>
				@else
					<p>Último acesso: {{ $user->last_logged_at->format('d/m/Y H:i')  }} - {{ $user->last_logged_at->diffForHumans()}}</p>
				@endif
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row mt-5  welcome-page">
	<div class="col-12">
		<div class="row">
			<div class="col-md-6 col-sm-12 sm-center">
				<h4>Deseja ver as métricas da sua conta ?</h4>
				<p>
					<a href="/admin/dashboard">
						<i class="el-icon-data-line mr-1"></i>
						Acesse o dashboard de sistema
					</a>
				</p>
			</div>
			<div class="col-md-6 col-sm-12 text-right sm-center sm-mt-4">
				<h4>Tem alguma dúvida?</h4>
				<p>
					<a href="/admin/wiki">
						<i class="el-icon-link mr-1"></i>
						Acesse nossa central de ajuda
					</a>
				</p>
			</div>
		</div>
	</div>
</div>
@endsection