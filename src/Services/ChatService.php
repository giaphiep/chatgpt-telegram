<?php

namespace GiapHiep\OpenAI\Services;

use GiapHiep\OpenAI\Contracts\ChatContract;

class ChatService
{
  protected $chat;

  public function __construct(ChatContract $chat) {
    $this->chat = $chat;
  }

  public function createChat($question, $result, $sender_id = null) {
    // handle data
    if ($result['object'] === 'chat.completion') {
       $answer = $result['choices'][0]['message']['content'] ?? NULL;
    } else if ($result['object'] === 'chat.completion.chunk') {
      $answer = $result['choices'][0]['delta']['content'] ?? NULL;
    } else {
       $answer = NULL;
    }
    $data = [
      'sender_id' => $sender_id,
      'question' => $question,
      'answer' => $answer,
      'total_tokens' => $result['usage']['total_tokens'] ?? NULL
    ];
    return $this->chat->create($data);
  }

  public function latestHistory($sender_id = null, $number = 5) {
    $messages = [];
    $latest = $this->chat->latestHistory($sender_id, $number);
    foreach($latest->reverse() ?? [] as $item) {
      $messages[] = [
        'role' => 'user',
        'content' => $item->question,
      ];
       $messages[] = [
        'role' => 'assistant',
        'content' => $item->answer,
      ];
    }
    return $messages;
  }
}
 