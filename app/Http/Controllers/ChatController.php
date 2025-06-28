<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleSheetService;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

   public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // max 10MB
        ]);

        $file = $request->file('csv_file');

        if (!$file) {
            return back()->with('error', 'No file was uploaded.');
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = storage_path('app/csv');

        // Make sure the folder exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Move uploaded file manually
        $file->move($destinationPath, $filename);

        $fullPath = $destinationPath . '/' . $filename;

        if (!file_exists($fullPath)) {
            return back()->with('error', 'Still could not find file at: ' . $fullPath);
        }

        // Read the file
        $csv = array_map('str_getcsv', file($fullPath));

        if (count($csv) < 2) {
            return back()->with('error', 'CSV file appears to be empty.');
        }

        $headers = array_map('trim', $csv[0]);
        $formatted = [];

        foreach (array_slice($csv, 1) as $row) {
            if (count($row) === count($headers)) {
                $formatted[] = array_combine($headers, $row);
            }
        }

        session(['csv_data' => $formatted,
     'csv_filename' => $filename]);

        return redirect()->route('chat.index')->with('success', 'CSV uploaded and parsed successfully!');
    }


    public function ask(Request $request)
    {
        $request->validate(['message' => 'required']);
        $csvData = session('csv_data');
        $prompt = $request->input('message');

        if (!$csvData) {
            return back()->with('error', 'Please upload a CSV file first.');
        }

        // Limit to first 10 rows to keep prompt size small
        $csvSample = array_slice($csvData, 0, 150);
        $csvText = "Here is the CSV data: Analyse it like a data analyst woth 30 years of experience\n";

        foreach ($csvSample as $row) {
            $csvText .= implode(', ', $row) . "\n";
        }

        $finalPrompt = $csvText . "\n\nUser Question: " . $prompt;

        // OpenRouter request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'HTTP-Referer' => 'http://localhost:8000', // Must match your domain
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct:free',
            'messages' => [
                ['role' => 'user', 'content' => $finalPrompt],
            ],
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'API Error: ' . $response->body());
        }

        $reply = $response['choices'][0]['message']['content'];

        return back()->with('reply', $reply);
    }


    public function prompt(Request $request)
{
    $request->validate(['message' => 'required|string']);

    $csvData = session('csv_data');
    $message = $request->input('message');

    if (!$csvData) {
        return redirect()->route('chat.index')->with('error', 'No CSV data found.');
    }

    // Format CSV data as JSON or Markdown Table
    $csvSample = array_slice($csvData, 0, 10); // Limit to 10 rows for prompt
    $csvJson = json_encode($csvSample, JSON_PRETTY_PRINT);

    $prompt = "Given this CSV data:\n$csvJson\n\nUser question: $message";

    // Call OpenRouter model
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
        'HTTP-Referer' => 'http://localhost:8000', // optional
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model' => 'mistralai/mistral-7b-instruct:free',
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    if (!$response->successful()) {
        return back()->with('error', 'API Error: ' . $response->body());
    }

    $data = $response->json();
    $answer = $data['choices'][0]['message']['content'] ?? 'No answer returned.';

    // Save chat history (append to session)
    $chat = session('chat', []);
    $chat[] = ['question' => $message, 'answer' => $answer];
    session(['chat' => $chat]);

    return redirect()->route('chat.index');
}





public function pushToGoogleSheet(GoogleSheetService $googleSheetService)
{
    $csvData = session('csv_data');

    if (!$csvData) {
        return redirect()->back()->with('error', 'No CSV data found in session.');
    }

    $url = $googleSheetService->createAndFillSheet('Uploaded CSV - ' . now()->format('Ymd_His'), $csvData);

    return redirect($url); // or pass to view
}


}
