<?php

namespace GiapHiep\OpenAI;

use Client;

class ChatGPT {

  private $model;
  private $key;

  protected $baseUrl = 'https://api.openai.com/v1';

  public function __construct() {
    $this->model = config('chatgpt.chatgpt_model');
    $this->key = config('chatgpt.openai_api_key');
  }

  public function chatCompletion($messages) {

    $data = [
      "model" => $this->model,
      "messages" => $messages
    ];

    try {

      $curl = curl_init($this->baseUrl . '/chat/completions');

      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
          "Content-Type: application/json",
          "Authorization: Bearer $this->key"
      ]);
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

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