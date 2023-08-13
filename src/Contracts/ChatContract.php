<?php

namespace GiapHiep\OpenAI\Contracts;


interface ChatContract
{

  public function latestHistory($sender_id = null, $number = 5);

  public function create($data);

}
 