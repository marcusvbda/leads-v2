<?php

namespace App\Console\Commands;

use App\Http\Controllers\WppMessagesController;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;

class sendWppMessage extends Command
{
    protected $signature = 'command:send-wpp-message {session_id} {ids*}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $ids =  $this->argument("ids");
        $session_id =  $this->argument("session_id");

        $session = WppSession::find($session_id);
        $queryMessages = WppMessage::whereIn("id", $ids);
        $this->bar = $this->output->createProgressBar($queryMessages->count());
        $controller = new WppMessagesController;

        $this->bar->start();
        $batch = [];
        (clone $queryMessages)->update(["status" => "processing"]);
        $messages = (clone $queryMessages)->orderBy("id", "asc")->get();
        $controller->sendSocket($messages, $session);
        foreach ($messages as $message) {
            $batch = $controller->pushToBatch($message, $batch);
            $this->bar->advance();
        }
        $controller->sendBatch($batch, $session);
        $this->bar->finish();
    }
}
