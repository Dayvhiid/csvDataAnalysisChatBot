<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\GoogleSheetService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;

class SpreadsheetController extends Controller
{


    

public function fetchFromGoogleSheet(GoogleSheetService $sheets)
{
    $data = $sheets->readSheet(); // Default: Sheet1!A1:D10
    return view('your-view', compact('data'));
}

public function openSpreadsheet()
{
    $filename = session('csv_filename');

    if (!$filename) {
        return back()->with('error', 'No uploaded file found in session.');
    }

    $filePath = storage_path('app/csv/' . $filename);

    if (!file_exists($filePath)) {
        return back()->with('error', 'File not found at: ' . $filePath);
    }

    // Load the CSV file
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    return view('csv.view', compact('rows', 'filename'));
}


public function spreadsheetEditor()
{
    $filename = session('csv_filename');
    if (!$filename) {
        return back()->with('error', 'No file uploaded.');
    }

    $path = storage_path('app/csv/' . $filename);
    if (!file_exists($path)) {
        return back()->with('error', 'File not found.');
    }

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    return view('csv.editor', compact('rows'));
}



public function saveSpreadsheet(Request $request)
{
    $data = json_decode($request->input('data'), true);
    $filename = session('csv_filename');

    if (!$filename || !$data) {
        return back()->with('error', 'Missing file or data.');
    }

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    foreach ($data as $rowIndex => $row) {
        foreach ($row as $colIndex => $cellValue) {
            // Either use this:
            // $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $cellValue);
            // Or this alternative:
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . ($rowIndex + 1), $cellValue);
        }
    }

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
    $writer->save(storage_path('app/csv/' . $filename));

    return back()->with('success', 'Spreadsheet saved successfully!');
}



}
