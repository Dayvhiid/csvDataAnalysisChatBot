<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\SpreadsheetController;
use App\Http\Controllers\HuggingFaceChatController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [ChatController::class, 'index'])->name('chat.index');
Route::post('/upload', [ChatController::class, 'upload'])->name('chat.upload');
Route::post('/ask', [ChatController::class, 'ask'])->name('chat.ask');
Route::post('/chat/prompt', [ChatController::class, 'prompt'])->name('chat.prompt');



Route::get('/chart', [ChartController::class, 'index'])->name('chart.index');
Route::post('/chart/render', [ChartController::class, 'render'])->name('chart.render');


Route::get('/open-csv', [SpreadsheetController::class, 'openSpreadsheet'])->name('csv.open');


Route::get('/spreadsheet', [SpreadsheetController::class, 'spreadsheetEditor'])->name('csv.editor');
Route::get('/sheet', [SheetController::class, 'viewSheet'])->name('csv.editor');
Route::post('/spreadsheet/save', [SpreadsheetController::class, 'saveSpreadsheet'])->name('csv.save');
