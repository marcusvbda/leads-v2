@extends("templates.admin")
@section('title',"Atendimento")

@section('breadcrumb')
<div class="row">
	<div class="col-12">
		<nav aria-label="breadcrumb">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="/admin" class="link">Dashboard</a>
					</li>
					<li class="breadcrumb-item">
						<a href="/admin/leads" class="link">Leads</a>
					</li>
		 			<li class="breadcrumb-item active" aria-current="page">Atendimento</li>					
				</ol>
			</nav>
		</nav>
	</div>
</div>
@endsection
@section('content')
<lead-attendance
	:user='@json($logged_user)'
	:preset_date='@json( \App\Http\Filters\FilterByPresetData::getAllDates())'
></lead-attendance>
@endsection
