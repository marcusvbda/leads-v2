@extends("templates.admin")
@section('title', $qty > 0 ? "($qty) Notificações" : 'Notificações')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="link">Página Inicial</a></li>
            <li class="breadcrumb-item"><a href="/admin/dashboard" class="link">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notificações</li>
        </ol>
    </nav>
@endsection
@section('content')
    <notification-list polo_id='{{ $user->polo_id }}'>
    </notification-list>
@endsection
