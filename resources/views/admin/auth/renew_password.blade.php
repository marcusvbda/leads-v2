@extends("templates.default")
@section('title',"Renovação de Senha")
@section('body')
	<renew-password
		token="{{ $token }}"
	>
	</renew-password>
@endsection