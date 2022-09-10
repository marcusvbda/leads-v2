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
            ["viewlist-leads", "Ver Lista de Leads"],
            ["create-leads", "Criar Leads"],
            ["edit-leads", "Editar Leads"],
            ["destroy-leads", "Excluir Leads"],

            ["viewlist-users", "Ver lista de Usuários"],

            ["viewlist-objections", "Ver lista de Objeções"],

            ["viewlist-contacttype", "Ver lista de Tipos de Contato"],

            ["viewlist-leadanswer", "Ver lista de Respostas de Contato"],
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

            ["viewlist-objections", "Ver lista de Objeções"],
            ["create-objections", "Criar Objeções"],
            ["edit-objections", "Editar Objeções"],
            ["destroy-objections", "Excluir Objeções"],

            ["viewlist-contacttype", "Ver lista de Tipos de Contato"],
            ["create-contacttype", "Criar Tipos de Contato"],
            ["edit-contacttype", "Editar Tipos de Contato"],
            ["destroy-contacttype", "Excluir Tipos de Contato"],

            ["viewlist-leadanswer", "Ver lista de Respostas de Contato"],
            ["create-leadanswer", "Criar Respostas de Contato"],
            ["edit-leadanswer", "Editar Respostas de Contato"],
            ["destroy-leadanswer", "Excluir Respostas de Contato"],

            ["config-rating-behavior", "Comportamento de Rating"],

            ["viewlist-email-integrators", "Ver Lista de Integradores de Emails"],
            ["create-email-integrators", "Criar Integradores de Emails"],
            ["edit-email-integrators", "Editar Integradores de Emails"],
            ["destroy-email-integrators", "Excluir Integradores de Emails"],

            ["viewlist-email-templates", "Ver Lista de Modelos de Email"],
            ["create-email-templates", "Criar Modelos de Email"],
            ["edit-email-templates", "Editar Modelos de Email"],
            ["destroy-email-templates", "Excluir Modelos de Email"],

            ["viewlist-wppsession", "Ver Lista de Sessões de WhatsApp"],
            ["create-wppsession", "Criar Sessões de WhatsApp"],
            ["destroy-wppsession", "Excluir Sessões de WhatsApp"],

            ["viewlist-wppmessage", "Ver Lista de Mensagens de WhatsApp"],
            ["create-wppmessage", "Criar Sessões de WhatsApp"],
            ["destroy-wppmessage", "Excluir Sessões de WhatsApp"],
        ]
    ]
];
