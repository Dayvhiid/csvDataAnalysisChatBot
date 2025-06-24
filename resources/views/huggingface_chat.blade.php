<!DOCTYPE html>
<html>
<head>
    <title>Hugging Face ChatBot</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: auto; padding: 2rem; }
        textarea, input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; }
        .message { padding: 1rem; background: #f0f0f0; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h2>🤖 Hugging Face CSV ChatBot</h2>

    @if (session('success')) <p style="color:green">{{ session('success') }}</p> @endif
    @if (session('error')) <p style="color:red">{{ session('error') }}</p> @endif

    <form action="{{ route('hf.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Upload CSV</button>
    </form>

    @if (session('hf_csv_data'))
    <form action="{{ route('hf.ask') }}" method="POST">
        @csrf
        <textarea name="question" rows="3" placeholder="Ask something about your CSV..."></textarea>
        <button type="submit">Ask</button>
    </form>

    <h3>💬 Chat History</h3>
    @foreach ($chatHistory as $chat)
        <div class="message">
            <strong>You:</strong> {{ $chat['question'] }} <br>
            <strong>AI:</strong> {{ $chat['answer'] }}
        </div>
    @endforeach
    @endif
</body>
</html>
