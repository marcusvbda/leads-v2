@extends("templates.admin")
@section('title',"Seleção de Modelo de Email")
@section("breadcrumb")
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/emails" class="link">Emails</a></li>
                    <li class="breadcrumb-item active"><a href="/admin/emails/create" class="link active">Seleção de Template</a></li>
                </ol>
            </nav>
        </nav>
    </div>
</div>
@endsection
@section("content")
<select-email-template
	:templates='@json($templates)'
>
</select-email-template>
@endsection