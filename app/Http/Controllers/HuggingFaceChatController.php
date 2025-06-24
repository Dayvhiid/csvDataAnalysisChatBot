<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HuggingFaceChatController extends Controller
{
    public function index()
    {
        return view('huggingface_chat', [
            'chatHistory' => session('hf_chat', []),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $realPath = $request->file('csv_file')->getRealPath();
        $csv = array_map('str_getcsv', file($realPath));
        $headers = array_shift($csv);

        $formatted = [];
        foreach (array_slice($csv, 0, 10) as $row) {
            $formatted[] = array_combine($headers, $row);
        }

        session(['hf_csv_data' => $formatted, 'hf_chat' => []]);

        return redirect()->route('hf.index')->with('success', 'CSV uploaded successfully!');
    }

    public function ask(Request $request)
    {
        $request->validate(['question' => 'required|string']);
        $question = $request->input('question');

        $csv = session('hf_csv_data');
        if (!$csv) {
            return back()->with('error', 'Upload a CSV first.');
        }

        $dataPreview = json_encode($csv);
        $prompt = "You are a data analyst. Here's a sample dataset:\n{$dataPreview}\n\nQuestion: {$question}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('HF_API_KEY'),
            'Accept' => 'application/json',
        ])->post('https://api-inference.huggingface.co/models/bigscience/bloomz-560m'
, [
            'inputs' => $prompt,
        ]);
 


        // if (!$response->successful()) {
        //     return back()->with('error', 'API Error: ' . $response->body());
        // }

        if (!$response->successful()) {
            $status = $response->status();
            $body = $response->body();

            // Try to decode the body if it's JSON
            $decoded = json_decode($body, true);
            $message = $decoded['error'] ?? $body;

            return back()->with('error', "API Error (HTTP $status): $message");
}

        $result = $response->json();
        $answer = $result[0]['generated_text'] ?? 'No response.';

        $chat = session('hf_chat', []);
        $chat[] = ['question' => $question, 'answer' => $answer];
        session(['hf_chat' => $chat]);

        return redirect()->route('hf.index');
    }
}
