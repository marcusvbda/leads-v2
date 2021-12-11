<?php

namespace App\Http\Actions\Leads;

use App\Http\Controllers\WebhookController;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Services\Messages;

class LeadSourceReprocess extends Action
{
    public $id = "lead-reprocess-origin";
    public $run_btn = "Reprocessar Origens";
    public $title = "Reprocessar Origem dos Leads";
    public $message = "Essa ação irá reprocessar os sources dos leads selecionados";


    public function handler(Request $request)
    {
        $leads = Lead::whereIn("id", $request["ids"])->get();
        $controllerWebhook = new WebhookController();
        foreach ($leads as $lead) {
            $content = @$lead->data->lead_api;
            $sources = ["RD Station"];
            if ($content) {
                $sources = $controllerWebhook->getSources($content, ["RD Station"]);
            }
            $_data = $lead->data;
            $_data->source = $sources;
            $lead->data = $_data;
            $lead->save();
        }
        Messages::send("success", "Os leads foram reprocessados");
        return ['success' => true];
    }
}
