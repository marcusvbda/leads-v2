@extends("templates.admin")
@section('title',"Log Viewer")

@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="/" class="link">Página Inicial</a></li>
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="link">Dashboard</a></li>
		 			<li class="breadcrumb-item active" aria-current="page">Log Viewer</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@section('content')
<Log-viewer :tree='@json($tree)'></Log-viewer>
@endsection
