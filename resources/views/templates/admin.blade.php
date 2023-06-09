@extends("templates.default")
@section('body')
@php
@endphp
@include("templates.topbar")
<div class="px-4 mt-4 mx-auto max-w-screen-xl lg:px-6">
    @yield("breadcrumb")
    <div class="px-1 mt-4">
        @yield("content")
    </div>
</div>
@endsection