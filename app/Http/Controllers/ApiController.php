<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    private $events = [
        "aluno-matriculado" => []
    ];

    public function testAuth(Request $request)
    {
        return response()->json($request->user);
    }

    public function eventHandler(Request $request)
    {
        $this->validate($request, [
            'action' => ['required', function ($att, $val, $fail) {
                if (!in_array($val, ['lead-update'])) {
                    return $fail('Action inválida');
                }
            }],
        ]);
        Log::channel("api_{$request->user->env}")->info("Api Event Handler", $request->all());
        return $this->{snakeCaseToCamelCase($request->action)}($request);
    }

    protected function leadUpdate(Request $request)
    {
        $this->validate($request, [
            'params' => 'required',
            'params.integration_key' => 'required',
            'params.event' => [
                'required',
                function ($att, $val, $fail) {
                    if (!is_array(@$this->events[$val])) {
                        return $fail('Evento inválida');
                    }
                }
            ]
        ]);
        $this->validate($request, $this->getEventValidator($request->params["event"]));
        return response("Ok");
    }

    private function getEventValidator($event)
    {
        return @$this->events[$event];
    }

    public function getEvents()
    {
        return response()->json(array_keys($this->events));
    }
}
