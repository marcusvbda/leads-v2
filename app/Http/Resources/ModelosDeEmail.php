<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\EmailTemplate;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\HtmlEditor;
use marcusvbda\vstack\Fields\Text;
use Auth;

class ModelosDeEmail extends Resource
{
    public $model = EmailTemplate::class;

    public function label()
    {
        return "Modelos de Email";
    }

    public function singularLabel()
    {
        return "Modelo de Email";
    }

    public function icon()
    {
        return "el-icon-receiving";
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        $columns = [];
        $columns["code"] = ["label" => "Código", "sortable_index" => "id"];
        $columns["name"] = ["label" => "Nome"];
        $columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
        return $columns;
    }

    public function canViewList()
    {
        return hasPermissionTo("viewlist-email-templates");
    }

    public function canView()
    {
        return  false;
    }

    public function canCreate()
    {
        return  hasPermissionTo("create-email-templates");
    }

    public function canClone()
    {
        return  false;
    }

    public function canUpdate()
    {
        return hasPermissionTo("edit-email-templates");
    }

    public function canDelete()
    {
        return hasPermissionTo("destroy-email-templates");
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
        $fields[] = new Text([
            "label" => "Nome",
            "field" => "name",
            "rules" => ["required", "max:255"],
            "description" => "Nome do modelo para indentificação"
        ]);
        $cards[] = new Card("Identificação", $fields);


        $fields = [];
        $fields[] = new Text([
            "label" => "Assunto",
            "field" => "subject",
            "rules" => ["required", "max:255"],
            "description" => "Assunto que será utilizado quando for enviado o email"
        ]);
        $cards[] = new Card("Parametrização", $fields);

        $fields = [];
        $fields[] = new HtmlEditor([
            "label" => "Conteúdo da Página",
            "field" => "body",
            "mode" => "newsletter",
            "description" => "Oque será exibido ao acessar a página"
        ]);
        $cards[] = new Card("Conteúdo", $fields);

        return $cards;
    }

    public function viewListBlade()
    {
        if (Auth::user()->hasRole(["super-admin"])) {
            return parent::viewListBlade();
        }
        return "admin.wiki.index";
    }
}
