@extends("templates.admin")
@section('title',"Cadastro de grupo de acesso")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/usuarios" class="link">Usuários</a></li>
                    <li class="breadcrumb-item"><a href="/admin/usuarios/create" class="link">Enviar convite de usuário</a></li>
                </ol>
            </nav>
        </nav>
    </div>
</div>
@endsection
@section("content")
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-row justify-content-between mb-3">
            <h4><span class="el-icon-s-promotion mr-2"></span> Envio de convite {{$resource->singularLabel()}}</h4>
        </div>
    </div>
</div>
<makeuser-invite 
    :polos='@json(\App\Http\Models\Polo::get())'
    :roles='@json($roles)'
>
</makeuser-invite>
@endsection