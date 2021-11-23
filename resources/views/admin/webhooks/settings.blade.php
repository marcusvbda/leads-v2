<tr>
    <td class="w-25">
        <div class="d-flex flex-column">
            <b class="input-title">
                Configurações
            </b>
            <small class="text-muted mt-1">
                Configurações de Transferencia de Leads do request
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
                                    <td><b>Parâmetros</b></td>
                                    <td><b>Polo</b></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td>
                                            <Json-template :content='@json($row->json_indexes)'>
                                            </Json-template>
                                        </td>
                                        <td class="align-center">{{ $row->polo->name }}</td>
                                        <td>
                                            <list-action-btns resource="settings" row_id="{{ $row->id }}" campaign_code="{{ $webhook->code }}">
                                            </list-action-btns>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data->links() }}
                    @else
                        <small class="text-muted my-5">Sem Configurações Definidas</small>
                    @endif
                </div>
            </div>
        </div>
    </td>

</tr>
