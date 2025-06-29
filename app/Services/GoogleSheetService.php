<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Exception as GoogleServiceException;

class GoogleSheetService
{
    protected $client;
    protected $sheetService;
    protected $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('csvDataAnalysisChat-Bot');
        $this->client->setScopes([
            Sheets::SPREADSHEETS,
            Drive::DRIVE
        ]);
        $this->client->setAuthConfig(storage_path('app/google-sheets.json'));
        $this->client->setAccessType('offline');

        $this->sheetService = new Sheets($this->client);
        $this->driveService = new Drive($this->client);
    }

    public function createAndFillSheet(string $title, array $data)
    {
        try {
            $sharedFolderId = '1tJlja8EuCiUNkf7XlqOg3CX1RH3nHRaC';

            // Check quota before proceeding (simplistic approach)
            $about = $this->driveService->about->get(['fields' => 'storageQuota']);
            $quota = $about->getStorageQuota();
            
            if ($quota->getUsage() >= $quota->getLimit()) {
                throw new \Exception('Google Drive storage quota exceeded. Please free up space or use a different account.');
            }

            $fileMetadata = new DriveFile([
                'name' => $title,
                'mimeType' => 'application/vnd.google-apps.spreadsheet',
                'parents' => [$sharedFolderId],
            ]);

            $file = $this->driveService->files->create($fileMetadata, [
                'fields' => 'id'
            ]);

            $spreadsheetId = $file->id;

            $body = new Sheets\ValueRange(['values' => $data]);
            $params = ['valueInputOption' => 'RAW'];

            $this->sheetService->spreadsheets_values->update(
                $spreadsheetId,
                'Sheet1!A1',
                $body,
                $params
            );

            return $spreadsheetId;

        } catch (GoogleServiceException $e) {
            // Handle specific Google API errors
            throw new \Exception('Google API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other errors
            throw $e;
        }
    }
}