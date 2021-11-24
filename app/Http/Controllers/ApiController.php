<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    private $events = [
        "registration-store-or-update" => [
            "description" => "Atualizará ou criará um novo cadastro no sistema ( caso a integration_key não exista na base ), sem influenciar em status",
            "rules" => [
                "params.data" => ["required"]
            ]
        ],
        "registred-student" => [
            "description" => "Informará ao CRM que o aluno se cadastrou no sistema acadêmico, mudará o status para aluno cadastrado",
            "rules" => [
                "params.data" => ["required"]
            ]
        ],
        "waiting-exame" => [
            "description" => "Informará ao CRM que o aluno está pronto para prestar o vestibular, mudará o status para aguardando vestibular",
            "rules" => [
                "params.subscription_key" => ["required"],
            ]
        ],
        "passed-the-test" => [
            "description" => "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no vestibular",
            "rules" => [
                "params.subscription_key" => ["required"],
            ]
        ],
        "failed-the-test" => [
            "description" => "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para reprovado no vestibular",
            "rules" => [
                "params.subscription_key" => ["required"],
            ]
        ],
        "pre-subscripted" =>  [
            "description" => "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no pré-matriculado",
            "rules" => [
                "params.subscription_key" => ["required"],
            ]
        ],
        "subscripted" =>  [
            "description" => "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no matriculado",
            "rules" => [
                "params.subscription_key" => ["required"],
            ]
        ]
    ];

    private $actions = ['lead-update'];

    public function testAuth(Request $request)
    {
        return response()->json($request->user);
    }

    public function eventHandler(Request $request)
    {
        $this->validate($request, [
            'action' => ['required', function ($att, $val, $fail) {
                if (!in_array($val, $this->actions)) {
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
        return @$this->events[$event]["rules"] ?? [];
    }

    public function getEvents()
    {
        return response()->json($this->events);
    }

    public function getActions()
    {
        return response()->json($this->actions);
    }
}
