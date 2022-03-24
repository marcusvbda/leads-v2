<?php

namespace App\Http\Controllers;

use App\Http\Models\WppMessage;
use Illuminate\Http\Request;

class WppMessagesController extends Controller
{
    public function postback(Request $request)
    {
        try {
            $data = $request->all();
            $id = data_get($data, "_uid");
            $postback_status = data_get($data, "postback_status");
            $message = WppMessage::find($id);
            $message->status = $postback_status;
            $message->save();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido com Erro', ['data' => $data, "error" => $message]);
            return response()->json(['error' => $message], 500);
        }
    }
}
