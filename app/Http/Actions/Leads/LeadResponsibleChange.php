<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use App\User;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Services\Messages;

class LeadResponsibleChange extends Action
{
    public $run_btn = "Alterar";
    public $title = "Alteração de responsável";
    public $message = "Essa ação irá alterar o responsável de todos os leads selecionados para o responsável selecionado";

    public function inputs()
    {
        $fields = [];
        $fields[] = new BelongsTo([
            "label" => "Responsável",
            "description" => "Novo responsável que deseja definir aos leads selecionados",
            "field" => "user_id",
            "model" => User::class
        ]);

        $cards = [];
        $cards[] = new Card("Informações", $fields);
        return $cards;
    }

    public function handler(Request $request)
    {
        Lead::whereIn("id", $request["ids"])->update(["responsible_id" => @$request->user_id]);
        Messages::send("success", "Responsável dos Leads selecionados alterado");
        return ['success' => true];
    }
}
