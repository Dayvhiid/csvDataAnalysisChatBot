<?php
// app/Services/GoogleSheetsService.php

namespace App\Services;

// use Google_Client;
// use Google_Service_Sheets;

use Google\Service\Sheets as Google_Service_Sheets;
use Google\Client as Google_Client;

class GoogleSheetService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google-sheets.json'));
        $this->client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $this->service = new Google_Service_Sheets($this->client);
    }

    public function readSheet($range = 'Sheet1!A1:D10')
    {
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        return $response->getValues();
    }

    public function writeSheet($range, $values)
    {
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = ['valueInputOption' => 'RAW'];
        return $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }
}
