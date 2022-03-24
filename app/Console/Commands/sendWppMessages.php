<?php

namespace App\Console\Commands;

use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;
use \GuzzleHttp\Client as GuzzleCLient;

class sendWppMessages extends Command
{
    protected $signature = 'command:wpp-messages';
    protected $bar = null;
    protected $limit = 50;

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
            $messages = (clone $queryMessages)->where("wpp_session_id", $session->id)->get();
            foreach ($messages as $message) {
                $this->setToSendingStatus($message);
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

    private function setToSendingStatus($message)
    {
        $message->status = "sending";
        $message->save();
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
