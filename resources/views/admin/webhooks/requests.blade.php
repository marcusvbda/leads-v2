@if ($settings->count())
    <div data-aos="fade-right">
        <div class="row d-flex align-items-center">
            <div class="col-6">
                <h4><span class="{{ $resource->icon() }} mr-2"></span> Configurações de transferência deste {{ $resource->label() }}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive-sm">
                    <table class="table table-sm table-striped hovered resource-table table-hover mb-0">
                        <thead>
                            <tr>
                                <td><b>Parâmetros</b></td>
                                <td><b>Polo</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settings as $row)
                                <tr>
                                    <td>
                                        <json-template :content='@json($row->json_indexes)'>
                                        </json-template>
                                    </td>
                                    <td class="align-center">{{ $row->polo->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif


@if ($data->total())
    <div data-aos="fade-right" class="mt-5">
        <div class="row d-flex align-items-center">
            <div class="col-6">
                <h4><span class="{{ $resource->icon() }} mr-2"></span> Requests deste {{ $resource->label() }}</h4>
            </div>
            <div class="col-6 d-flex justify-content-end">
                {{ $data->links() }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive-sm">
                    <table class="table table-sm table-striped hovered resource-table table-hover mb-0">
                        <thead>
                            <tr>
                                <td><b>#</b></td>
                                <td><b>Status</b></td>
                                <td><b>Request</b></td>
                                <td><b>Data</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td><b>#{{ $row->code }}</b></td>
                                    <td>{!! $row->f_approved !!}</td>
                                    <td>
                                        <json-viewer :content='@json($row->content)' :webhook='@json($webhook)' :approved='@json($row->approved)'
                                            tenant_id="{{ $tenant_id }}">
                                        </json-viewer>
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
@endif
