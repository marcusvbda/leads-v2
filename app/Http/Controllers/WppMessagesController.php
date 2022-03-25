<?php

namespace App\Http\Controllers;

use App\Http\Models\WppMessage;
use Illuminate\Http\Request;
use marcusvbda\vstack\Vstack;
use \GuzzleHttp\Client as GuzzleCLient;

class WppMessagesController extends Controller
{
    public function postback($tenant_code, Request $request)
    {
        try {
            $data = $request->all();
            $ids = data_get($data, "_uids");
            $postback_status = data_get($data, "postback_status");
            WppMessage::whereIn("id", $ids)->update(["status" => $postback_status]);
            debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            $this->postSocketEvent($tenant_code, $ids, $postback_status);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido com Erro', ['data' => $data, "error" => $message]);
            return response()->json(['error' => $message], 500);
        }
    }

    private function postSocketEvent($tenant_code, $ids, $status)
    {
        $event = "WppMessage.StatusChange";
        $channel = "WppMessages@Tenant:" . $tenant_code;
        Vstack::SocketEmit($event, $channel, [
            "ids" => $ids,
            "status" => WppMessage::makeStatusHTML($status)
        ]);
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

    public function sendSocket($messages, $session)
    {
        $ids = $messages->pluck("id")->toArray();
        $event = "WppMessage.StatusChange";
        $channel = "WppMessages@Tenant:" . $session->tenant->code;
        Vstack::SocketEmit($event, $channel, [
            "ids" => $ids,
            "status" => WppMessage::makeStatusHTML("processing")
        ]);
    }

    public function sendBatch($messages, $session)
    {
        $sending_data = [
            "session_token" => data_get($session, "data.token"),
            "postback" => config("app.url") . "/api/mensagens-wpp/postback/" . $session->tenant->code,
            "messages" => $messages
        ];
        $client = new GuzzleCLient();
        $uri = config("wpp.service.uri") . "/messages/send";
        $username = config("wpp.service.username");
        $password = config("wpp.service.password");
        $client->post($uri, [
            'auth' => [$username, $password],
            'json' => $sending_data,
        ]);
    }
}
