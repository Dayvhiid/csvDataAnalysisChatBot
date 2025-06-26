<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SheetController extends Controller
{
    //
    public function viewSheet()
    {
        $csvData = session('csv_data');
        return view('sheet', ['csvData' => $csvData]);
    }
}
