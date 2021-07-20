@extends("templates.admin")
@section('title',"Configurar Regra de Classificaçao")
@section("breadcrumb")
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/regra-classificacao" class="link">Regra de Classificaçao</a></li>
                </ol>
            </nav>
        </nav>
    </div>
</div>
@endsection
@section("content")
<config-rating-rules
	:rating_rule='@json($rating_rules)'
>
</config-rating-rules>
@endsection