<?php

namespace App\Http\Resources;

use App\Http\Models\MailIntegrator;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    BelongsTo,
    Card,
    Text,
};

class IntegradoresDeEmail extends Resource
{
    public $model = MailIntegrator::class;

    public function label()
    {
        return "Integradores de Email";
    }

    public function singularLabel()
    {
        return "Integrador de Email";
    }

    public function icon()
    {
        return "el-icon-receiving";
    }

    public function search()
    {
        return ["name", "email"];
    }

    public function table()
    {
        $columns = [];
        $columns["code"] = ["label" => "Código", "sortable_index" => "id"];
        $columns["name"] = ["label" => "Nome"];
        $columns["email"] = ["label" => "Email"];
        $columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
        return $columns;
    }

    public function canViewList()
    {
        return hasPermissionTo("viewlist-email-integrators");
    }

    public function canView()
    {
        return  false;
    }

    public function canCreate()
    {
        return hasPermissionTo("create-email-integrators");
    }

    public function canClone()
    {
        return  false;
    }

    public function canUpdate()
    {
        return hasPermissionTo("edit-email-integrators");
    }

    public function canDelete()
    {
        return hasPermissionTo("destroy-email-integrators");
    }

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return false;
    }

    public function fields()
    {
        $fields = [];
        $fields[] = new Text([
            "label" => "Nome",
            "field" => "name",
            "rules" => ["required", "max:255"],
            "description" => "Nome do para indentificação do integrador"
        ]);
        $fields[] = new BelongsTo([
            "label" => "Provedor",
            "field" => "provider",
            "rules" => ["required", "max:255"],
            "description" => "Provedor integrador que fará os intermédio de disparos de Email",
            "options" => ["Sendgrid"]
        ]);
        $cards[] = new Card("Identificação", $fields);

        $fields = [];
        $fields[] = new Text([
            "label" => "Email",
            "field" => "email",
            "type" => "email",
            "rules" => ["required", "email", "max:255"],
            "description" => "Endereço de Email"
        ]);
        $fields[] = new Text([
            "label" => "Identificação",
            "field" => "from_name",
            "rules" => ["required", "max:255"],
            "description" => "Nome que será utilizado como 'From' no envio de Email"
        ]);
        $fields[] = new Text([
            "label" => "Senha",
            "field" => "hash_password",
            "type" => "password",
            "rules" => ["required", "max:255"],
            "description" => "Hash password do integrador"
        ]);
        $cards[] = new Card("Autenticação", $fields);

        $fields = [];
        return $cards;
    }
}
