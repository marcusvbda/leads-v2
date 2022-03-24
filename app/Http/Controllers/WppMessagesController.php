<?php

namespace App\Http\Controllers;

use App\Http\Models\WppMessage;
use Illuminate\Http\Request;
use marcusvbda\vstack\Vstack;

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
}
