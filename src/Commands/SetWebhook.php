<?php

namespace GiapHiep\OpenAI\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use GiapHiep\OpenAI\Telegram;

class SetWebhook extends Command
{
    protected $signature = 'set:webhook
                            {hostname? : Hostname to set}';

    protected $description = 'Use this method to specify a url and receive incoming updates via an outgoing webhook';

    public function handle()
    {
        $hostname = $this->argument('hostname');
        if (! $hostname) {
            $hostname = $this->ask('Which hostname do you like to set?', config('app.url'));
        }

        if (! Str::of($hostname)->startsWith('http')) {
            $schema = match (app()->environment()) {
                'local' => 'http',
                default => 'https'
            };
            $hostname = "{$schema}://{$hostname}";
        }
        $token = config('chatgpt.telegram_bot_token');

        if (empty($token)) {
            $this->error("Please set TELEGRAM_BOT_TOKEN in .env file first then run again!");
            return;
        }

        $url = $hostname . route('telegram.webhook', ['token' => $token], false);
        
        $telegram = new Telegram();
        $response = $telegram->setWebhook($url);

         if ($response['ok']) {
            $this->info($response['description'] . " <comment>{$url}</comment>");
        } else {
            $this->error($response['description']);
        }

    }
}