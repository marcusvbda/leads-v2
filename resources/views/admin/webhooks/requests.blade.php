<tr>
    <td class="w-25">
        <div class="d-flex flex-column">
            <b class="input-title">
                Requests
            </b>
            <small class="text-muted mt-1">
                Requests realizados neste webhook
            </small>
        </div>
    </td>
    <td>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive-sm">
                    @if ($data->total() > 0)
                        <table class="table table-sm table-striped hovered resource-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <td><b>Status</b></td>
                                    <td><b>Request</b></td>
                                    <td><b>Data</b></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td class="f-12">{!! $row->f_approved !!}</td>
                                        <td class="f-12">
                                            <json-viewer :content='@json($row->content)' :webhook='@json($webhook)' :approved='@json($row->approved)'
                                                tenant_id="{{ $tenant_id }}">
                                            </json-viewer>
                                        </td>
                                        <td class="f-12">{!! $row->f_created_at_badge !!}</td>
                                        <td class="f-12">
                                            <list-action-btns resource="requests" row_id="{{ $row->id }}" campaign_code="{{ $webhook->code }}">
                                            </list-action-btns>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data->links() }}

                    @else
                        <small class="text-muted my-5">Sem Requests Recebidos</small>
                    @endif
                </div>
            </div>
        </div>
    </td>

</tr>
