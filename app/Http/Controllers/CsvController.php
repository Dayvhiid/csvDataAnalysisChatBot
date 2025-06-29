<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function edit()
    {
        // Dummy fallback if no data in session
        $data = session('csv_data');


        return view('handsontable.sheet', compact('data'));
    }

    public function save(Request $request)
    {
        $data = $request->input('data');
        session(['csv_data' => $data]);

        return response()->json(['status' => 'success']);
    }
}
