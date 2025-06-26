<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
         $data = session('csv_data');
        $headers = array_keys($data[0] ?? []);
        return view('chart.index', compact('headers'));
    }

    public function render(Request $request)
    {
        $data = session('csv_data');

        if (!$data) {
            return back()->with('error', 'No CSV data found.');
        }
        $headers = array_keys($data[0] ?? []);

        $xKey = $request->input('x_key');
        $yKey = $request->input('y_key');
        $type = $request->input('chart_type');

        $xData = [];
        $yData = [];

        foreach ($data as $row) {
            $xData[] = $row[$xKey] ?? '';
            $yData[] = (float) ($row[$yKey] ?? 0);
        }

        return view('chart.render', compact('xData', 'yData', 'type', 'xKey', 'yKey'));
    }
}
