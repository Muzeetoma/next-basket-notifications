<?php

namespace App\Listeners;

use App\Core\RabbitMQService;
use Illuminate\Support\Facades\Log;

class MessageListener
{
    private $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {
        $this->rabbitMQService->consume(function ($msg) {
            Log::info("Received message from RabbitMQ: " . $msg->body . "\n");
        });
    }
}
