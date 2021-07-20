@extends('errors::minimal')

@section('title', __('errors.forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'errors.forbidden'))
