@extends("templates.admin")
@section('title', $page->title)

@section('breadcrumb')
@php
$user = Auth::user();
$is_super_admin = $user->isSuperAdmin();
$wiki_url = '/admin/wiki';
if(!$is_super_admin) {
    $wiki_url = '/admin/wiki/?order_by=id&order_type=asc';
}
@endphp
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item">
						<a href="/admin" class="link">Dashboard</a>
					</li>
                    <li class="breadcrumb-item">
						<a href="{{ $wiki_url }}" class="link">Wiki</a>
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
	<h1>{!!  $page->title !!}</h1>
	<div class="card">
		<div class="card-body">			
			{!!  $page->body !!}
		</div>
	</div>
</div>
@endsection
