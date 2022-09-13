<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Services\Messages;

class LeadDelete extends Action
{
    public $run_btn = "Excluir";
    public $title = "Excluir Leads";
    public $message = "Essa ação irá excluir os leads selecionados";


    public function handler(Request $request)
    {
        Lead::whereIn("id", $request["ids"])->delete();
        Messages::send("success", "Os leads foram excluidos");
        return ['success' => true];
    }
}
