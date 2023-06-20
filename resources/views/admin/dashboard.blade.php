@extends('templates.admin')
@section('title', 'Dashboard')
@section('breadcrumb')
    <vstack-breadcrumb
        :items="[{
                route: '/admin',
                title: 'Página Inicial'
            },
            {
                route: '/admin/dashboard',
                title: 'Dashboard'
            }
        ]">
    </vstack-breadcrumb>
@endsection
@section('content')
    @php
        $user = Auth::user();
        $is_superadmin = $user->hasRole(['super-admin']);
        $tenant = @$_GET['tenant_id'] && $is_superadmin ? \App\Http\Models\Tenant::findOrFail($_GET['tenant_id']) : $user->tenant;
    @endphp
    <dashboard-content title="Mostradores e Desempenho" user_id="{{ $user->id }}"
        :selected_polo_ids='@json($user->polos()->pluck('id'))'>
    </dashboard-content>
@endsection
