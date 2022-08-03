<?php

namespace App\Console\Commands;

use App\Http\Controllers\WppMessagesController;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use Illuminate\Console\Command;

class processWppMessages extends Command
{
    protected $signature = 'command:process-wpp-messages';
    protected $bar = null;
    // protected $limit = 1;
    protected $limit = 200;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $queryMessages = WppMessage::whereIn("status", ["waiting", "processing"])->limit($this->limit);
        $this->bar = $this->output->createProgressBar(count((clone $queryMessages)->get()));
        $controller = new WppMessagesController;
        foreach (WppSession::get() as $session) {
            $this->bar->start();
            $batch = [];
            $query = (clone $queryMessages)->orderBy("id", "asc")->where("wpp_session_id", $session->id);
            (clone $query)->update(["status" => "processing"]);
            $ids = (clone $query)->get()->pluck("id")->toArray();
            $controller->sendSocket($ids, $session->tenant->code, "processing");
            foreach ((clone $query)->orderBy("id", "asc")->get() as $message) {
                $batch = $controller->pushToBatch($message, $batch);
                $this->bar->advance();
            }
            $controller->sendBatch($batch, $session);
            $this->bar->finish();
        }
    }
}
