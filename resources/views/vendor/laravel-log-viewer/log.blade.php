@php
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;
use Illuminate\Support\Facades\Crypt;
@endphp
@extends("templates.admin")
@section('title',"Log Viewer")

@section('breadcrumb')
<div class="row">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <a href="/admin" class="link">Página Inicial</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Log Viewer</li>
        </ol>
      </nav>
    </nav>
  </div>
</div>
@endsection
@section('content')
<div class="row">
  <div class="col sidebar mb-3">
    <div class="list-group div-scroll">
      @foreach($folders as $folder)
      <div class="list-group-item">
        @php
        LaravelLogViewer::DirectoryTreeStructure( $storage_path, $structure );
        @endphp
      </div>
      @endforeach
      @foreach($files as $file)
      <a href="?l={{ Crypt::encrypt($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif">
        {{$file}}
      </a>
      @endforeach
    </div>
  </div>
  <div class="col-10 table-container">
    @if ($logs === null)
    <div>
      Arquivo de Log é maior que 50M, baixe o arquivo para visualiza-lo.
    </div>
    @else
    <div class="table-responsive">
      <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
        <thead>
          <tr>
            @if ($standardFormat)
            <th>Nível</th>
            <th>Contexto</th>
            <th>Data</th>
            @else
            <th>Número da Linha</th>
            @endif
            <th>Conteúdo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($logs as $key => $log)
          <tr data-display="stack{{{$key}}}">
            @if ($standardFormat)
            <td class="nowrap text-{{{$log['level_class']}}}">
              <div class="d-flex flex-row align-items-center">
                <span class="fa mr-2 fa-{{{$log['level_img']}}}" aria-hidden="true"></span>{{$log['level']}}
              </div>
            </td>
            <td class="text">{{$log['context']}}</td>
            @endif
            <td class="date">{{{$log['date']}}}</td>
            <td class="text">
              @if ($log['stack'])
              <button type="button" class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                data-display="stack{{{$key}}}">
                <span class="fa fa-search"></span>
              </button>
              @endif
              {{{$log['text']}}}
              @if (isset($log['in_file']))
              <br />{{{$log['in_file']}}}
              @endif
              @if ($log['stack'])
              <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{
                trim($log['stack']) }}}
              </div>
              @endif
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
      @endif
      <div class="p-3">
        @if($current_file)
        <a
          href="?dl={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}">
          <span class="fa fa-download"></span> Baixar Arquivo
        </a>
        -
        <a id="clean-log"
          href="?clean={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}">
          <span class="fa fa-sync"></span> Limpar Arquivo
        </a>
        -
        <a id="delete-log"
          href="?del={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}">
          <span class="fa fa-trash"></span> Excluir Arquivo
        </a>
        @if(count($files) > 1)
        -
        <a id="delete-all-log"
          href="?delall=true{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}">
          <span class="fa fa-trash-alt"></span> Excluir Todos os Arquivos
        </a>
        @endif
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
  integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function () {
		$('.table-container tr').on('click',  function() {
			$('#' + $(this).data('display')).toggle();
		});

		$('#table-log').DataTable({
			"order": [$('#table-log').data('orderingIndex'), 'desc'],
			"stateSave": true,
			"stateSaveCallback": function (settings, data) {
				window.localStorage.setItem("datatable", JSON.stringify(data));
			},
			"stateLoadCallback": function (settings){
				var data = JSON.parse(window.localStorage.getItem("datatable"));
				if (data) {
					data.start = 0;
				}
				return data;
			},
			"language": {
				"sProcessing":    "Procesando...",
				"sLengthMenu":    "Mostrar _MENU_",
				"sZeroRecords":   "Nenhum registro encontrado",
				"sEmptyTable":    "Nenhum registro encontrado",
				"sInfo":          "Mostrando registros de _START_ ate _END_ de um total de _TOTAL_ registros",
				"sInfoEmpty":     "Mostrando registros de 0 até 0 de um total de 0 registros",
				"sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix":   "",
				"sSearch":        "Buscar:",
				"sUrl":           "",
				"sInfoThousands":  ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst":    "Primero",
					"sLast":    "Último",
					"sNext":    "Seguinte",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending":  ": Clique para ordenar a columna de maneira ascendente",
					"sSortDescending":  ": Clique para ordenar a columna de maneira descendente"
				}
			}
		});

		$('#delete-log, #clean-log, #delete-all-log').click( () =>{
			return confirm('Tem certeza ?');
		});
  	});
</script>
@endsection