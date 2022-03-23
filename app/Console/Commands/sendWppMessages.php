<?php

namespace App\Console\Commands;

use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;

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
        $queryMessages = WppMessage::where("status", "waiting");
        $this->bar = $this->output->createProgressBar($queryMessages->count());
        foreach ($sessions as $session) {
            $messages = $queryMessages->where("data->wpp_session_id", $session->id)->get();
            $this->bar->start();
            $batch = [];
            foreach ($messages as $message) {
                if (count($batch) >= 10) {
                    $this->sendBatch($batch);
                    $batch = [];
                }
                $batch = $this->pushToBatch($message, $batch);
                $this->bar->advance();
            }
            $this->sendBatch($batch);
            $this->bar->finish();
        }
    }

    private function pushToBatch($message, $batch)
    {
        $batch[] = [
            "message" => data_get($message, "data.mensagem"),
            "phone" => data_get($message, "data.telefone"),
            "session_id" => data_get($message, "data.wpp_session_id"),
        ];
        return $batch;
    }

    private function sendBatch($batch)
    {
        dd("sendBatch", $batch);
    }
}
