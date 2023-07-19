<div class="flex flex-col">
    @if (@$after_row)
        <div class="p-3 text-sm flex justify-end">
            <a href="/admin/campanhas/{{ $row->code }}/dashboard" class="vstack-link">
                Abrir em tela cheia
            </a>
        </div>
    @endif
    <campaign-dashboard :after_row='@json(@$after_row)' @if (@$after_row) class="px-4" @endif
        :campaign='@json($row)' :fields='@json($leads_fields)'>
    </campaign-dashboard>
</div>
