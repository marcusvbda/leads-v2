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
            $id = data_get($data, "_uid");
            $postback_status = data_get($data, "postback_status");
            $message = WppMessage::find($id);
            $message->status = $postback_status;
            $message->save();
            // debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            $this->postSocketEvent($tenant_code, $id, $postback_status);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido com Erro', ['data' => $data, "error" => $message]);
            return response()->json(['error' => $message], 500);
        }
    }

    private function postSocketEvent($tenant_code, $id, $status)
    {
        $event = "WppMessage.StatusChange";
        $channel = "WppMessages@Tenant:" . $tenant_code;
        Vstack::SocketEmit($event, $channel, [
            "ids" => [$id],
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

    public function sendBatch($messages, $session)
    {
        $ids = array_map(function ($message) {
            return data_get($message, "_uid");
        }, $messages);

        $event = "WppMessage.StatusChange";
        $channel = "WppMessages@Tenant:" . $session->tenant->code;
        Vstack::SocketEmit($event, $channel, [
            "ids" => $ids,
            "status" => WppMessage::makeStatusHTML("sending")
        ]);

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
