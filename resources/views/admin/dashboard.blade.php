@extends("templates.admin")
@section('title',"Dashboard")
@section('breadcrumb')
<vstack-breadcrumb :items="[
			{
				url: '/admin',
				title: 'Página Inicial'
			},
			{
				url: '/admin/dashboard',
				title: 'Dashboard'
			}
		]">
</vstack-breadcrumb>
@endsection
@section('content')
<?php
$user = Auth::user();
$is_superadmin = $user->hasRole(["super-admin"]);
$tenant = (@$_GET["tenant_id"] && $is_superadmin) ? \App\Http\Models\Tenant::findOrFail($_GET["tenant_id"]) : $user->tenant;
$is_head = $user->polo->data->head;
?>
<dashboard-content title="Mostradores e Desempenho" user_id="{{ $user->id }}" :is_head='@json($is_head)' @if(!$is_head) :selected_polo_ids='@json([$user->polo_id])' @else :selected_polo_ids='@json($user->polos()->pluck("id"))' @endif>
</dashboard-content>
@endsection