<?php

namespace App\Http\Controllers;

use App\Http\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use marcusvbda\vstack\Vstack;
use Auth;

class WppSessionController extends Controller
{
    private $service_uri, $app_uri;

    public function __construct()
    {
        $this->service_uri = config('wpp.service.uri');
        $this->app_uri = config('app.url');
    }

    private function getSessionCreateUri()
    {
        return $this->service_uri . "/sessions/create";
    }

    private function getSessionDeleteUri($code)
    {
        return $this->service_uri . "/sessions/$code";
    }

    private function getPostbackUri($code)
    {
        return $this->app_uri . "/api/sessoes-wpp/postback/" . $code;
    }

    private function getToken()
    {
        return  config('wpp.service.token');
    }

    public function createSession(Request $request)
    {
        $payload = [
            "postback" => $this->getPostbackUri(Auth::user()->tenant->code),
            "code" =>  $request->code
        ];
        $response = Http::withToken($this->getToken())->post($this->getSessionCreateUri(), $payload);
        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function postback($tenant_code, Request $request)
    {
        try {
            Tenant::findByCodeOrFail($tenant_code);
            $data = $request->all();
            $event = data_get($data, "event");
            $code = data_get($data, "code");
            debug_log('Wpp/Sender/Postback', 'Postback Recebido', ['data' => $data]);
            $this->postSocketEvent($code, $event, $data);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            debug_log('Wpp/Sender/Postback', 'Postback Recebido com Erro', ['data' => $data, "error" => $message]);
            return response()->json(['error' => $message], 500);
        }
    }

    private function postSocketEvent($code, $event, $data)
    {
        $event = "WppSocket.Postback";
        $channel = "WppSocket@Session:" . $code;
        Vstack::SocketEmit($event, $channel, $data);
    }

    public function deleteSession($session)
    {
        Http::withToken($this->getToken())->delete($this->getSessionDeleteUri($session->token));
    }
}
