@extends("templates.admin")
@section('title',"Dashboard")

@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item">
						<a href="/admin" class="link">Dashboard</a>
					</li>
		 			<li class="breadcrumb-item active" aria-current="page">Mostradores e Desempenho</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@section('content')
@php
    $user = Auth::user(); 
    $is_superadmin = $user->hasRole(["super-admin"]);
	$tenant = (@$_GET["tenant_id"] && $is_superadmin) ? \App\Http\Models\Tenant::findOrFail($_GET["tenant_id"]) : $user->tenant;
	$is_head = $user->polo->data->head
@endphp
<dashboard-content
	data-aos="fade-down"
	title="Mostradores e Desempenho"
	user_id="{{ $user->id }}"
	:is_head='@json($is_head)'
	@if(!$is_head)
		:selected_polo_ids='@json([$user->polo_id])'
	@else
		:selected_polo_ids='@json($user->polos()->pluck("id"))'
	@endif
>
</dashboard-content>
@endsection
