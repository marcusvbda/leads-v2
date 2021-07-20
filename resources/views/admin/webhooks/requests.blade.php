<div data-aos="fade-right">
	<div class="row d-flex align-items-center">
		<div class="col-6">
			<h4><span class="{{ $resource->icon() }} mr-2"></span>  Requests deste {{ $resource->label() }}</h4>
		</div>
		<div class="col-6 d-flex justify-content-end">
			{{$data->links()}}
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="table-responsive-sm">
				<table class="table table-sm table-striped hovered resource-table table-hover mb-0">
					<thead>
						<tr>
							<td>#</td>
							<td>Status</td>
							<td>Request</td>
							<td>Data</td>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $row)
							<tr>
								<td><b>#{{ $row->code }}</b></td>
								<td>{!! $row->f_approved !!}</td>
								<td>
									<json-viewer :content='@json($row->content)'></json-viewer>
								</td>
								<td>{!! $row->f_created_at_badge !!}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>