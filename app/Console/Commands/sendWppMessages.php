<?php

namespace App\Console\Commands;

use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;
use \GuzzleHttp\Client as GuzzleCLient;
use marcusvbda\vstack\Vstack;

class sendWppMessages extends Command
{
    protected $signature = 'command:wpp-messages';
    protected $bar = null;
    protected $limit = 200;

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $queryMessages = WppMessage::whereIn("status", ["waiting", "sending"])->limit($this->limit);
        $this->bar = $this->output->createProgressBar(count((clone $queryMessages)->get()));

        foreach (WppSession::get() as $session) {
            $this->bar->start();
            $batch = [];
            $query = (clone $queryMessages)->where("wpp_session_id", $session->id);
            (clone $query)->update(["status" => "sending"]);
            foreach ((clone $query)->orderBy("id", "asc")->get() as $message) {
                $batch = $this->pushToBatch($message, $batch);
                $this->bar->advance();
            }
            $this->sendBatch($batch, $session);
            $this->bar->finish();
        }
    }

    private function pushToBatch($message, $batch)
    {
        $batch[] = [
            "_uid" => data_get($message, "id"),
            "message" => data_get($message, "data.mensagem"),
            "number" => data_get($message, "phone"),
            "type" => "text",
        ];
        return $batch;
    }

    private function sendBatch($messages, $session)
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
