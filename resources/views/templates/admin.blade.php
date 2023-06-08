@extends("templates.default")
@section('body')
@php
@endphp
@include("templates.topbar")
<div class="my-2 container-fluid">
    @yield("breadcrumb")
</div>
<div class="container-fluid pb-5">
    @yield("content")
</div>
@endsection