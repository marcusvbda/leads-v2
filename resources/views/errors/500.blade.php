@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

<script>
    console.log("error","{{$exception->getMessage()}}")
    console.log("line","{{$exception->getLine()}}")
</script>