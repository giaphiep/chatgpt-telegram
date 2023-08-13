<?php

namespace GiapHiep\OpenAI;

class Telegram {

  private $token;
  private $baseUrl = 'https://api.telegram.org/bot';

  public function __construct() {
    $this->token = config('chatgpt.telegram_bot_token');
  }

  public function setWebhook($url) {
    try {  
        $curl = curl_init();
        $webhookUrl = $this->baseUrl . $this->token ."/setWebhook?url=" . $url;
        curl_setopt_array($curl, [
        CURLOPT_URL => $webhookUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
                "accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          \Log::error('Set webhook failed: ' . $err);
          return [
            'ok' => false,
            'description' => 'Set webhook failed: ' . $err
          ];
        } else {
            return json_decode($response, true);
        }

    } catch(\Exception $e) {
       \Log::error('Set webhook failed: '. $e->getMessage());
        return [
          'ok' => false,
          'description' => 'Set webhook failed: ' . $e->getMessage()
        ];
    }
  }

  public function deleteWebhook($url) {
    try {
        $curl = curl_init();
        $webhookUrl = $this->baseUrl . $this->token ."/setWebhook?remove=" . $url;
        curl_setopt_array($curl, [
        CURLOPT_URL => $webhookUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
              "accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          \Log::info("Delete webhook failed: " . $err);
          return [
            'ok' => false,
            'description' => "Delete webhook failed: " .  $err
          ];
        } else {
            return json_decode($response, true);
        }

    } catch(\Exception $e) {
        \Log::info("Delete webhook failed: " . $e->getMessage());
        return [
            'ok' => false,
            'description' => "Delete webhook failed: " .  $e->getMessage()
        ];
    }
  }

  public function sendMessage($chatId, $text) {
    $data = [
      'chat_id' => $chatId,
      'text' => $text,
      'disable_web_page_preview' => false,
      'disable_notification' => false,
      'reply_to_message_id' => null
    ];

    try {

      $curl = curl_init($this->baseUrl . $this->token. '/sendMessage');

      curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
          "User-Agent: Telegram Bot SDK - (https://github.com/irazasyed/telegram-bot-sdk)",
          "accept: application/json",
          "content-type: application/json"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      if ($err) {
        \Log::info($err);
      } else {
        return json_decode($response, true);
      }
      
    } catch(\Exception $e) {
       \Log::info($e->getMessage());
    }
  }
}