<?php
$user = Auth::user();
function currentClass($routes)
{
    $routes = is_array($routes) ? $routes : [$routes];
    $class = "";
    $current = Request::server('REQUEST_URI');
    foreach($routes as $route)
    {
        $contain = strpos($route, "/*");
        if (!$contain) {
            if($current == $route) return "active";
        } else {
            $route = str_replace("/*", "", $route);
            if(strpos($current, $route) !== false) return "active";
        }
    }
    return "";
}
$user = Auth::user();
$is_super_admin = $user->isSuperAdmin();
$is_admin = $user->hasRole(["admin"]);
$is_admin_or_super_admin = $user->hasRole(["admin","super-admin"]);
$polo = $user->polo;

function getMenuClass($permission,$array_current=[]) {
	$class = "dropdown-item ".currentClass($array_current);
	if(!hasPermissionTo($permission)) $class.= " disabled ";
	return $class;
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light py-0">
	<a class="navbar-brand py-0" href="/admin">
		<text-logo size="30" app_name="{{ config('app.name') }}" />
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item {{ currentClass(['/admin']) }}">
				<a class="nav-link" href="/admin"><i class="el-icon-data-line mr-2"></i>CRM Dashboard <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/leads/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-attract mr-2"></i>Oportunidades
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-leads',['/admin/leads/*']) }}" href="/admin/leads" data-label="Base de Leads">Leads</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/emails/*','/admin/automacoes-customizadas/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-s-flag mr-2"></i>Marketing
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-email',['/admin/emails/*']) }}"  href="/admin/emails" data-label="Pré-Definição de Emails">Emails</a>
					<a  class="{{ getMenuClass('viewlist-automation',['/admin/automacoes-customizadas/*']) }}" href="/admin/automacoes-customizadas" data-label="Automação de Campanha Customizada">Automações Customizadas</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/relatorios/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-data-analysis mr-2"></i>Relatórios
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('view-leads-report',['/admin/relatorios/leads/*']) }}" href="/admin/relatorios/leads" data-label="Relatório de Leads">Leads</a>
					<a class="{{ getMenuClass('report-automation',['/admin/relatorios/automacoes-customizadas/*']) }}" href="/admin/relatorios/automacoes-customizadas" data-label="Relatório de Automações">Automação</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/resposta-contatos/*','/admin/tipos-contato/*','/admin/respostas-contato/*','/admin/regra-classificacao/*','/admin/objecoes/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-bangzhu mr-2"></i>Extras
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-objections',['/admin/objecoes/*']) }}" href="/admin/objecoes" data-label="Objeções de respostas negativas">Objeções de Contato</a>
					<a class="{{ getMenuClass('viewlist-contacttype',['/admin/tipos-contato/*']) }}" href="/admin/tipos-contato" data-label="Formar que o lead foi contato">Tipos de Contato</a>
					<a  class="{{ getMenuClass('viewlist-leadanswer',['/admin/respostas-contato/*']) }}" href="/admin/respostas-contato" data-label="Contatos com Lead">Respostas de Contato</a>
					<a class="{{ getMenuClass('config-rating-behavior',['/admin/regra-classificacao/*']) }}" href="/admin/regra-classificacao" data-label="Regra de Rating de Lead">Regra de Classificação</a>
				</div>
			</li>
		</ul>
		<select-polo polo_name="{{ $polo->name }}" user_id="{{ $user->id }}" :logged_id='@json($polo->id)'></select-polo>
		<notification-bell polo_id="{{ $polo->id }}" :active='@json(currentClass(['/admin/notificacoes/*']))'></notification-bell>
		<ul class="navbar-nav">
			<li class="nav-item dropdown hover-color ml-0">
				<a class="nav-link dropdown-toggle py-0 d-flex flex-row align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="d-flex flex-column mr-2">
						<b>{{ $user->name }}</b>
						<small class="f-12">{{ $user->email }}</small>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
					@if($is_super_admin)
						<a class="dropdown-item" href="/admin/permissoes">Permissões</a>
					@endif
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/grupos-de-acesso">Grupos de Acesso</a>
					<a class="dropdown-item" href="/admin/usuarios/{{ $user->code }}/edit">
						<div class="d-flex justify-content-between">
							<span>Conta</span>
							<span class="badge badge-default ml-5 pt-1 px-2">ID.: {{ $user->code }}</span>
						</div>
					</a>
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/usuarios">Usuários</a>
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/polos">Polos</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/login">Sair</a>
				</div>
			</li>
		</ul>	
	</div>
</nav>