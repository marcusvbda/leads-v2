<?php

namespace App\Http\Actions\Leads;

use App\Http\Controllers\WebhookController;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Services\Messages;

class LeadReprocess extends Action
{
    public $id = "lead-reprocess";
    public $run_btn = "Reprocessar";
    public $title = "Reprocessar Requests dos Leads";
    public $message = "Essa ação irá reprocessar este lead baseado nas configurações de webhooks";


    public function handler(Request $request)
    {
        $leads = Lead::whereIn("id", $request["ids"])->get();
        $controllerWebhook = new WebhookController();
        foreach ($leads as $lead) {
            $webhook = $lead->webhook;
            $webhook_request = $lead->webhook_request;
            $request = new Request($webhook_request->content);
            $controllerWebhook->handler($webhook->token, $request, $lead->id);
        }
        Messages::send("success", "Os leads foram excluidos");
        return ['success' => true];
    }
}
