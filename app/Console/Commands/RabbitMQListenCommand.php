<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Listeners\MessageListener;

class RabbitMQListenCommand extends Command
{
    protected $signature = 'rabbitmq:listen';
    protected $description = 'Listen for incoming messages from RabbitMQ queue';

    public function handle(MessageListener $messageListener)
    {
        $messageListener->handle();
    }
}
