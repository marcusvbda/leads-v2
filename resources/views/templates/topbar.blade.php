<?php

use App\Http\Resources\Integradores;
use App\Http\Resources\Leads;
use App\Http\Resources\Objecoes;
use App\Http\Resources\RespostasContato;
use App\Http\Resources\TiposContato;

$resourceLeads = new Leads();
$canViewLeads = $resourceLeads->canViewList();
$canViewAttendance = $resourceLeads->canUpdate();
$canViewReportLeads = $resourceLeads->canViewReport();
$canViewObjecoes = (new Objecoes())->canViewList();
$canViewIntegradores = (new Integradores())->canViewList();
$canViewTiposContato = (new TiposContato())->canViewList();
$canViewResposta = (new RespostasContato())->canViewList();
$user = Auth::user();
$polo = $user->polo;
$isAdmin = $user->hasRole(['admin']);
$isSuperAdmin = $user->hasRole(['super-admin']);
$isAdminOrSuperAdmin = $isAdmin || $isSuperAdmin;

$items = [
  [
    "position" => "center",
    "title" => "Dashboard",
    "route" => "/admin/dashboard",
    "visible" => true,
    "items" => []
  ],
  [
    "position" => "center",
    "title" => "Oportunidades",
    "visible" => $canViewLeads || $canViewAttendance,
    "items" => [
      [
        "title" => "Leads",
        "route" => "/admin/leads",
        "visible" => $canViewLeads,
      ],
      [
        "title" => "Atendimento",
        "route" => "/admin/atendimento",
        "visible" => $canViewAttendance,
      ],
    ],
  ],
  [
    "position" => "center",
    "title" => "Relatórios",
    "visible" => $canViewReportLeads,
    "items" => [
      [
        "title" => "Relatório de Leads",
        "route" => "/admin/relatorios/leads",
        "visible" => $canViewReportLeads,
      ],
    ]
  ],
  [
    "position" => "center",
    "title" => "Captação",
    "visible" => $isAdminOrSuperAdmin,
    "items" => [
      [
        "title" => "Webhook",
        "route" => "/admin/webhook",
        "visible" => $isAdminOrSuperAdmin,
      ],
      [
        "title" => "Integradores",
        "route" => "/admin/integradores",
        "visible" => $isAdminOrSuperAdmin,
      ],
    ]
  ],
  [
    "position" => "center",
    "title" => "Configurações",
    "visible" => $canViewObjecoes || $canViewTiposContato || $canViewResposta || $canViewIntegradores,
    "items" => [
      [
        "title" => "Objeções",
        "route" => "/admin/objecoes",
        "visible" => $canViewObjecoes,
      ],
      [
        "title" => "Tipos de contato",
        "route" => "/admin/tipos-contato",
        "visible" => $canViewTiposContato,
      ],
      [
        "title" => "Respostas de contato",
        "route" => "/admin/respostas-contato",
        "visible" => $canViewResposta,
      ],
      [
        "title" => "Respostas de contato",
        "route" => "/admin/integradores-de-email/",
        "visible" => $canViewIntegradores,
      ],
    ]
  ],
  [
    "position" => "right",
    "title" => $user->email,
    "visible" => true,
    "custom_style" => "left: -66px;",
    "items" => [
      [
        "title" => "Polos",
        "route" => "/admin/polos",
        "visible" => $isAdminOrSuperAdmin,
      ],
      [
        "title" => "Departamentos",
        "route" => "/admin/departamentos",
        "visible" => $isAdminOrSuperAdmin,
      ],
      [
        "title" => "Log Viewer",
        "route" => "/admin/log-viewer",
        "visible" => $isSuperAdmin,
      ],
      [
        "title" => "Sair",
        "route" => "/login",
        "visible" => true,
      ],
    ]
  ]
];
?>
<theme-navbar :items='@json($items)'>
  <select-polo polo_name='{{$polo->name}}' :user_id='{{$user->id}}' :logged_id='{{$polo->id}}'></select-polo>
</theme-navbar>