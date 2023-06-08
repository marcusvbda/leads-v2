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
$canViewObjecoes = (new Objecoes())->canView();
$canViewIntegradores = (new Integradores())->canView();
$canViewTiposContato = (new TiposContato())->canView();
$canViewResposta = (new RespostasContato())->canView();
$user = Auth::user();
$isAdmin = $user->hasRole(['admin']);
$isSuperAdmin = $user->hasRole(['super-admin']);
$isAdminOrSuperAdmin = $isAdmin || $isSuperAdmin;

$items = [
  [
    "title" => "Dashboard",
    "route" => "/admin/dashboard",
    "visible" => true,
    "items" => []
  ],
  [
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
  ]
];
?>
<theme-navbar :items='@json($items)'></theme-navbar>