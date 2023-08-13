<?php 

namespace GiapHiep\OpenAI;

use Illuminate\Support\ServiceProvider;

use GiapHiep\OpenAI\Contracts\ChatContract;
use GiapHiep\OpenAI\Repositories\ChatRepository;
use GiapHiep\OpenAI\Services\ChatService;
use GiapHiep\OpenAI\Commands\SetWebhook;
use GiapHiep\OpenAI\Commands\DeleteWebhook;

class ChatGPTServiceProvider extends ServiceProvider {

  public function register() {

      $this->app->bind(ChatContract::class, ChatRepository::class);

      $this->app->bind(ChatService::class, function ($app) {
            return new ChatService($app->make(ChatContract::class));
        });

      $this->app->singleton('ChatGPT', function ($app) {
          return new \GiapHiep\OpenAI\ChatGPT();
      });
  }

  public function boot() {
    $this->mergeConfigFrom(__DIR__.'/../config/chatgpt.php', 'chatgpt');
    $this->loadRoutesFrom(__DIR__.'/../routes/hook.php');
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    // Publishing is only necessary when using the CLI.
    if ($this->app->runningInConsole()) {
        $this->bootForConsole();
    }
  }

  // Publishing the configuration file.
  protected function bootForConsole() {
      $this->publishes([
          __DIR__.'/../database/migrations/' => database_path('migrations')
      ], 'migrations');

      $this->publishes([
          __DIR__ . '/../config/chatgpt.php' => config_path('chatgpt.php'),
      ], 'chatgpt');

      $this->publishes([
          __DIR__ . '/../routes/hook.php' => base_path('routes/hook.php')
      ], 'hook');

       $this->commands([
            SetWebhook::class,
            DeleteWebhook::class
        ]);

  }
}