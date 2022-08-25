<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\WppMessagesController;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;

class ProcessWppMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bar = null;
    protected $limit = 200;

    public function __construct()
    {
        $this->onQueue('process-wpp-messages');
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
