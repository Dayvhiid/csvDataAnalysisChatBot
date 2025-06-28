<?php
namespace App\Services;

use Google_Client;
use Google_Service_Sheets;

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

    public function createAndFillSheet($title, array $values)
    {
        // Step 1: Create Sheet
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => ['title' => $title]
        ]);

        $spreadsheet = $this->service->spreadsheets->create($spreadsheet);
        $spreadsheetId = $spreadsheet->spreadsheetId;

        // Step 2: Insert Data
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);

        $this->service->spreadsheets_values->update(
            $spreadsheetId,
            'Sheet1!A1',
            $body,
            ['valueInputOption' => 'RAW']
        );

        return $spreadsheet->spreadsheetUrl;
    }
}
