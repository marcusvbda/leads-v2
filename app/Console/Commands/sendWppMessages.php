<?php

namespace App\Console\Commands;

use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;
use \GuzzleHttp\Client as GuzzleCLient;

class sendWppMessages extends Command
{
    protected $signature = 'command:wpp-mesages';
    protected $description = 'Command description';
    protected $bar = null;

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $sessions = WppSession::get();
        $this->setToSendingStatus();

        $queryMessages = WppMessage::where("status", "sending");
        $this->bar = $this->output->createProgressBar($queryMessages->count());
        foreach ($sessions as $session) {
            $messages = $queryMessages->where("data->wpp_session_id", $session->id)->get();
            $this->bar->start();
            $batch = [];
            foreach ($messages as $message) {
                if (count($batch) >= 10) {
                    $this->sendBatch($batch, $session);
                    $batch = [];
                }
                $batch = $this->pushToBatch($message, $batch);
                $this->bar->advance();
            }
            $this->sendBatch($batch, $session);
            $this->bar->finish();
        }
    }

    private function setToSendingStatus()
    {
        WppMessage::where("status", "waiting")->update(["status" => "sending"]);
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
        $sending_data = [
            "session_token" => data_get($session, "data.token"),
            "messages" => $messages,
            "postback" => config("app.url") . "/api/mensagens-wpp/postback"
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
