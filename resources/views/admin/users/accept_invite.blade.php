@extends("templates.auth")
@section('title',"Cadastro de usuário")
@section('image', '/assets/images/auth/forgot_password.png')
@section('form')
<create-user :invite='@json($invite)'></create-user>
@endsection