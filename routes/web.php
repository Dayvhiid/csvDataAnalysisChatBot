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


Route::get('/spreadsheet', [SpreadsheetController::class, 'fetchFromGoogleSheet'])->name('csv.editor');
Route::post('/spreadsheet/save', [SpreadsheetController::class, 'saveSpreadsheet'])->name('csv.save');
Route::get('/sheet', [SheetController::class, 'spreadsheet'])->name('spreadsheet');

Route::post('/save-csv-data', function (Illuminate\Http\Request $request) {
    session(['csv_data' => $request->input('data')]);
    return response()->json(['status' => 'ok']);
});



Route::get('/spreadsheet/google', [ChatController::class, 'pushToGoogleSheet'])->name('spreadsheet.google');
