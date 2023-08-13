<?php 

use GiapHiep\OpenAI\Controllers\ChatGPTController;

Route::post('chatgpt/ask', [ChatGPTController::class, 'ask'])->name('chatgpt.ask');
Route::post('telegram/{token}/webhook', [ChatGPTController::class, 'telegramWebhook'])->name('telegram.webhook');