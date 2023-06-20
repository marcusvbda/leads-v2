<div class="flex flex-col">
    @if (@$after_row)
        <div class="p-3 text-sm flex justify-end">
            <a href="/admin/campanhas/{{ $row->code }}/dashboard">
                Abrir em tela cheia
            </a>
        </div>
    @endif
    <campaign-dashboard @if (@$after_row) class="px-4" @endif :campaign='@json($row)'>
    </campaign-dashboard>
</div>
