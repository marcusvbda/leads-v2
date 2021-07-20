@extends('errors::minimal')

@section('title', __('errors.unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'errors.unavailable'))
