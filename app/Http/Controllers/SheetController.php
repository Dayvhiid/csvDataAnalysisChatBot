<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SheetController extends Controller
{
    //
  public function spreadsheet()
{
    $data = session('csv_data') ?? [];

    return view('sheet', compact('data'));
}

}
