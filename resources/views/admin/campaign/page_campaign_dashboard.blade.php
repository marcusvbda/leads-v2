@extends('templates.admin')
@section('title', $campaign->name)
@section('breadcrumb')
    <vstack-breadcrumb
        :items="[{
                route: '/admin',
                title: 'Página Inicial'
            },
            {
                route: '/admin/dashboard',
                title: 'Dashboard'
            },
            {
                route: '/admin/campanhas',
                title: 'Campanhas'
            },
            {
                route: '/admin/campanhas/{{ $campaign->code }}/dashboard',
                title: 'Dashboard da campanha'
            }
        ]">
    </vstack-breadcrumb>
@endsection
@section('content')
    <h4 id="resource-label" class="text-3xl mb-4 md:text-2xl font-bold text-neutral-800">
        @if ($resource->icon())
            <span class="{{ $resource->icon() }} mr-2"></span>
        @endif {{ $campaign->name }}
    </h4>
    @include('admin.campaign.campaign_dashboard', ['row' => $campaign])
@endsection
