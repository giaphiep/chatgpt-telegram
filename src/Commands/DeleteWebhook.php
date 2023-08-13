<?php

namespace GiapHiep\OpenAI\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use GiapHiep\OpenAI\Telegram;

class DeleteWebhook extends Command
{
    protected $signature = 'del:webhook
                            {webhook? : Webhook already set}';

    protected $description = 'Use this method to remove webhook integration';

    public function handle()
    {
        $webhook = $this->argument('webhook');

        if (!$webhook) {
            $webhook = $this->ask('Please enter full url webhook?', config('app.url') . '/telegram/' . config('chatgpt.telegram_bot_token') . '/webook');
        }

        $token = config('chatgpt.telegram_bot_token');
        if (!$token) {
          $this->error("Please set TELEGRAM_BOT_TOKEN in .env file first then run again!");
          return;
        }
        $telegram = new Telegram();
        $response = $telegram->deleteWebhook($webhook);

         if ($response['ok']) {
            $this->info($response['description'] . " <comment>{$webhook}</comment>");
        } else {
            $this->error($response['description']);
        }
    }
}