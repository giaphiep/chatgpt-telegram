<?php

namespace GiapHiep\ChatGPT\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Botble\Api\Supports\ApiHelper
 */
class ChatGPTFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChatGPT';
    }
}
