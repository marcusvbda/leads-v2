@php
    use App\Http\Resources\Integradores;
    use App\Http\Resources\Leads;
    use App\Http\Resources\Usuarios;
    use App\Http\Resources\Campanhas;
    
    $resourceLeads = new Leads();
    $canViewLeads = $resourceLeads->canViewList();
    $canViewReportLeads = $resourceLeads->canViewReport();
    $canViewUsuarios = (new Usuarios())->canViewList();
    $canViewCampanha = (new Campanhas())->canViewList();
    $user = Auth::user();
    $polo = $user->polo;
    $hasMorePolos = $user->polos->count() > 1;
    $isAdmin = $user->hasRole(['admin']);
    $isSuperAdmin = $user->hasRole(['super-admin']);
    $isAdminOrSuperAdmin = $isAdmin || $isSuperAdmin;
    
    $items = [
        [
            'position' => 'center',
            'title' => 'Dashboard',
            'route' => '/admin/dashboard',
            'visible' => true,
            'items' => [],
        ],
        [
            'position' => 'center',
            'title' => 'Oportunidades',
            'visible' => $canViewLeads || $canViewCampanha,
            'items' => [
                [
                    'title' => 'Campanhas',
                    'route' => '/admin/campanhas',
                    'visible' => $canViewCampanha,
                ],
                [
                    'title' => 'Leads',
                    'route' => '/admin/leads',
                    'visible' => $canViewLeads,
                ],
            ],
        ],
        [
            'position' => 'center',
            'title' => 'Relatórios',
            'visible' => $canViewReportLeads,
            'items' => [
                [
                    'title' => 'Relatório de Leads',
                    'route' => '/admin/relatorios/leads',
                    'visible' => $canViewReportLeads,
                ],
            ],
        ],
        [
            'position' => 'right',
            'title' => $user->email,
            'visible' => true,
            'custom_style' => 'left: -100px;',
            'items' => [
                [
                    'title' => 'Empresas',
                    'route' => '/admin/empresas',
                    'visible' => $isAdminOrSuperAdmin,
                ],
                [
                    'title' => 'Departamentos',
                    'route' => '/admin/departamentos',
                    'visible' => $isAdminOrSuperAdmin,
                ],
                [
                    'title' => 'Usuários',
                    'route' => '/admin/usuarios',
                    'visible' => $canViewUsuarios,
                ],
                [
                    'title' => 'Sair',
                    'route' => '/login',
                    'visible' => true,
                ],
            ],
        ],
    ];
@endphp
<theme-navbar :items='@json($items)'>
    <select-polo polo_name='{{ $polo->name }}' :user_id='{{ $user->id }}' :logged_id='{{ $polo->id }}'
        :has_more_polos='@json($hasMorePolos)'>
    </select-polo>
</theme-navbar>
