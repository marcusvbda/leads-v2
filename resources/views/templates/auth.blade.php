@extends('templates.default')
@section('title', 'Login')
@section('body')
    <main class="h-full flex flex-col md:flex-row">
        <div class="w-full h-full px-8 hidden md:block md:w-1/2">
            <div class="flex items-center justify-center h-full">
                <img src="@yield('image')" class="max-w-md" />
            </div>
        </div>
        <div class="bg-white w-full h-full order-2 md:w-1/2 p-4 flex items-center justify-center dark:bg-gray-800">
            @yield('form')
        </div>
    </main>
@endsection
