<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\HuggingFaceChatController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [ChatController::class, 'index'])->name('chat.index');
Route::post('/upload', [ChatController::class, 'upload'])->name('chat.upload');
Route::post('/ask', [ChatController::class, 'ask'])->name('chat.ask');
Route::post('/chat/prompt', [ChatController::class, 'prompt'])->name('chat.prompt');





Route::get('/hf-chat', [HuggingFaceChatController::class, 'index'])->name('hf.index');
Route::post('/hf-upload', [HuggingFaceChatController::class, 'upload'])->name('hf.upload');
Route::post('/hf-ask', [HuggingFaceChatController::class, 'ask'])->name('hf.ask');




Route::get('/hf-test', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('HF_API_KEY'),
        'Accept' => 'application/json',
    ])->post('https://api-inference.huggingface.co/models/google/flan-t5-base', [
        'inputs' => 'What is Laravel?',
    ]);

    if ($response->successful()) {
        return $response->json();
    }

    return response()->json([
        'status' => $response->status(),
        'error' => $response->body(),
    ]);
});



Route::get('/openrouter-chat', function () {
    $prompt = 'Explain Laravel to a beginner';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
        'HTTP-Referer' => 'http://localhost:8000', // REQUIRED by OpenRouter
        'Content-Type' => 'application/json',
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model' => 'microsoft/phi-4-reasoning-plus:free',
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    if (!$response->successful()) {
        return response()->json([
            'error' => $response->status(),
            'body' => $response->body(),
        ], $response->status());
    }

    return response()->json([
        'reply' => $response['choices'][0]['message']['content'],
    ]);
});


