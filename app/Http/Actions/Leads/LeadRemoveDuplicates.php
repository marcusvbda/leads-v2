<?php

namespace App\Http\Actions\Leads;

use App\Http\Controllers\WebhookController;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Services\Messages;

class LeadRemoveDuplicates extends Action
{
    public $id = "lead-remove-duplicate";
    public $run_btn = "Remover";
    public $title = "Remover Leads Duplicados";
    public $message = "Essa ação irá remover os leads com os mesmo polo, nome, email e status destes selecionados'";


    public function handler(Request $request)
    {
        $leads = Lead::whereIn("id", $request["ids"])->get();
        foreach ($leads as $lead) {
            Lead::where("polo_id", $lead->polo_id)
                ->where("data->name", $lead->name)
                ->where("data->email", $lead->email)
                ->where("status_id", $lead->status_id)
                ->where("id", "!=", $lead->id)
                ->delete();
        }
        Messages::send("success", "Os leads foram removidos");
        return ['success' => true];
    }
}
