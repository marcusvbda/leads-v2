@extends("templates.admin")
@section('title', $page->title)

@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item">
						<a href="/admin" class="link">Dashboard</a>
					</li>
                    <li class="breadcrumb-item">
						<a href="/admin/wiki" class="link">Wiki</a>
					</li>		
		 			<li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@section('content')
<div class="py-3">
	<div class="card">
		<div class="card-body">
			{!!  $page->body !!}
		</div>
	</div>
</div>
@endsection
