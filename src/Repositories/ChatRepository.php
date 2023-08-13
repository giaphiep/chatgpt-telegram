<?php

namespace GiapHiep\OpenAI\Repositories;

use GiapHiep\OpenAI\Contracts\ChatContract;
use GiapHiep\OpenAI\Models\Chat;

class ChatRepository implements ChatContract
{
  protected $model;

  public function __construct(Chat $model)
  {
    $this->model = $model;
  }

  public function latestHistory($sender_id = null, $number = 5) {
    if ($sender_id) 
      return $this->model->where('sender_id', $sender_id)->orderBy('id', 'desc')->take($number)->get();
    else 
      return $this->model->orderBy('id', 'desc')->take($number)->get();
  }

  public function create($data) {
    $chat =  $this->model->create($data);
    return $data;
  }

}
 