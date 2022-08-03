<?php

namespace App\Http\Controllers;

use App\Http\Models\Tenant;
use App\Http\Models\WppMessage;
use Illuminate\Http\Request;
use marcusvbda\vstack\Vstack;
use Illuminate\Support\Facades\Http;

class WppMessagesController extends Controller
{
    private $service_uri, $app_uri;

    public function __construct()
    {
        $this->service_uri = config('wpp.service.uri');
        $this->app_uri = config('app.url');
    }

    public function postback($tenant_code, Request $request)
    {
        try {
            Tenant::findByCodeOrFail($tenant_code);
            $data = $request->all();
            $ids = data_get($data, "_uids", false);
            $status = data_get($data, "status", false);
            if (!$ids || !$status) {
                return response()->json(["success" => false, "message" => "Invalid request"]);
            }
            debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            WppMessage::whereIn("id", $ids)->update(["status" => $status]);
            debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            $this->sendSocket($ids, $tenant_code, $status);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido com Erro', ['data' => $data, "error" => $message]);
            return response()->json(['error' => $message], 500);
        }
    }

    public function pushToBatch($message, $batch)
    {
        $batch[] = [
            "_uid" => data_get($message, "id"),
            "message" => data_get($message, "data.mensagem"),
            "number" => data_get($message, "phone"),
            "type" => "text",
        ];
        return $batch;
    }

    public function sendSocket($ids, $code, $status)
    {
        $event = "WppMessage.StatusChange";
        $channel = "WppMessages@Tenant:" . $code;
        Vstack::SocketEmit($event, $channel, [
            "ids" => $ids,
            "status" => WppMessage::makeStatusHTML($status)
        ]);
    }

    private function getSendMessageUri()
    {
        return $this->service_uri . "/messages/send";
    }

    private function getToken()
    {
        return  config('wpp.service.token');
    }

    private function getPostbackUri($code)
    {
        return $this->app_uri . "/api/mensagens-wpp/postback/" . $code;
    }


    public function sendBatch($messages, $session)
    {
        $payload = [
            "code" => data_get($session, "data.token"),
            "postback" => $this->getPostbackUri($session->tenant->code),
            "messages" => $messages
        ];
        Http::withToken($this->getToken())->post($this->getSendMessageUri(), $payload);
    }
}
