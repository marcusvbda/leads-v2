<?php

return [
    "super-admin" => [
        "title" => "Super Admin",
        "permissions" => "all",
        "hidden" => true
    ],
    "user" => [
        "title" => "Usuário",
        "permissions" => [
            ["viewlist-campaign", "Ver Lista de Campanhas"],
            ["create-campaign", "Criar Campanhas"],
            ["edit-campaign", "Editar Campanhas"],
            ["destroy-campaign", "Excluir Campanhas"],

            ["viewlist-leads", "Ver Lista de Leads"],
            ["create-leads", "Criar Leads"],
            ["edit-leads", "Editar Leads"],
            ["destroy-leads", "Excluir Leads"],

            ["viewlist-users", "Ver lista de Usuários"],
        ]
    ],
    "admin" => [
        "title" => "Admin",
        "permissions" => [
            ["viewlist-leads", "Ver Lista de Leads"],
            ["create-leads", "Criar Leads"],
            ["edit-leads", "Editar Leads"],
            ["destroy-leads", "Excluir Leads"],

            ["viewlist-users", "Ver lista de Usuários"],
            ["create-users", "Criar Usuários"],
            ["edit-users", "Editar Usuários"],
            ["destroy-users", "Excluir Usuários"],

            ["viewlist-email-templates", "Ver Lista de Modelos de Email"],
            ["create-email-templates", "Criar Modelos de Email"],
            ["edit-email-templates", "Editar Modelos de Email"],
            ["destroy-email-templates", "Excluir Modelos de Email"],
        ]
    ]
];
