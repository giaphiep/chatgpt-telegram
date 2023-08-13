<?php

namespace GiapHiep\OpenAI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use GiapHiep\OpenAI\ChatGPT;
use GiapHiep\OpenAI\Services\ChatService;
use GiapHiep\OpenAI\Telegram;

class ChatGPTController extends Controller {

  protected $chatService;

  public function __construct(ChatService $chatService) {
    $this->chatService = $chatService;
  }

  // send 5 history messages for chatGPT understand converstation
  protected function chatCompletion($sender_id = null, $question = '') {
    $messages = $this->chatService->latestHistory($sender_id, 5) ?? [];
    $messages[] = [
      'role' => 'user',
      'content' => $question
    ];

    $chatgpt = new ChatGPT();
    
    $response = $chatgpt->chatCompletion($messages);
    return $response;
  }

  public function ask(Request $request) {
    $messages = $this->chatService->latestHistory(NULL, 5) ?? [];
    $messages[] = [
      'role' => 'user',
      'content' => $request->question ?? NULL
    ];

    $chatgpt = new ChatGPT();
    $response = $chatgpt->chatCompletion($messages);
    return $response;
  }

  public function telegramWebhook(Request $request, $token) {

    $input = $request->all();
    $sender_id = $input["message"]["chat"]["id"] ?? NULL;        
    $question = $input["message"]["text"] ?? NULL;

    // ask chatGPT
    $result = $this->chatCompletion($sender_id, $question);
    
    if ($result['object'] === 'chat.completion') {
       $answer = $result['choices'][0]['message']['content'] ?? NULL;
    } else if ($result['object'] === 'chat.completion.chunk') {
      $answer = $result['choices'][0]['delta']['content'] ?? NULL;
    } else {
       $answer = NULL;
    }
    if ($answer)  {
      // send back to telegram
      $telegram = new Telegram();
      $telegram->sendMessage($sender_id, $answer);

      // save to database
      $this->chatService->createChat($question, $result, $sender_id);
      return true;
    }

    return false;
  
  }

}