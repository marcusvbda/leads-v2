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

    /**
     * Testar a Autenticação
     * @authenticated
     * 
     * Está rota serve apenas para validar se sua autenticação está correta
     * 
     * @responseFile  docs/responses/testAuth.post.json
     */
    public function postTestAuth(Request $request)
    {
        return response()->json(["id" => $request->user->id, "name" => $request->user->name, "env" => $request->user->env]);
    }

    /**
     * Listar todos os eventos disponíveis
     * @authenticated
     * 
     * Está rota serve apenas para validar se sua autenticação está correta
     * 
     * @responseFile  docs/responses/events.get.json
     */
    public function getEvents()
    {
        return response()->json($this->events);
    }

    /**
     * Listar todos as actions disponíveis
     * @authenticated
     * 
     * Está rota serve apenas para validar se sua autenticação está correta
     * 
     * @responseFile  docs/responses/actions.get.json
     */
    public function getActions()
    {
        return response()->json($this->actions);
    }

    /**
     * Disparar eventos para a action selecionada
     * @authenticated
     * 
     * Esta rota é a principal da api e é responsável por disparar os eventos para a action selecionada
     * 
     * Esta rota possui alguns parametros que são obrigatórios porém dependendo do evento que você está disparando é possível que mais parametros sejam obrigatórios ( verifique a documentação do evento )
     * 
     * Deve enviar os parametros no formato json no corpo da requisição ( ex: {"action" : "lorem-ipsum", "params" : {...} } )
     * 
     * @bodyParam action string required Precisa ser uma das actions habilitadas, você pode listar todas actions na routa get-actions'.
     * @bodyParam params.integration_key string required key única de refêrencia'.
     * @bodyParam params.event string required nome do evento que será disparado para a action selecionado, você pode listar todos os eventos na routa get-events'.
     * @bodyParam params.data required object dados adicionais que serão utilizados por alguns eventos, por exemplo, no caso de lead update, deve-se passar as informações do lead neste parâmetro'.
     * @bodyParam params.subscription_key required string adicional necessário para quase todos os eventos habilitados'.
     */
    public function postEventHandler(Request $request)
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
}
