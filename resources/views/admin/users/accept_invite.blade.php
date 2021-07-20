@extends("templates.default")
@section('title',"Cadastro de Usuário")
@section('body')
	<create-user :invite='@json($invite)'></create-user>
@endsection