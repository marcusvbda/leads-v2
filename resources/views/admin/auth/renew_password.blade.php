@extends("templates.auth")
@section('title',"Renovação de Senha")
@section('image', '/assets/images/auth/signup.png')
@section('form')
<renew-password token="{{ $token }}">
</renew-password>
@endsection