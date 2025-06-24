<!DOCTYPE html>
<html>
<head>
    <title>CSV ChatBot</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: auto; padding: 2rem; }
        textarea, input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; }
        .message { margin-bottom: 1rem; padding: 1rem; background: #f8f8f8; border-radius: 8px; }
    </style>
</head>
<body>
    <h2>📊 CSV Data ChatBot</h2>

    @if (session('success')) <p style="color:green">{{ session('success') }}</p> @endif
    @if (session('error')) <p style="color:red">{{ session('error') }}</p> @endif

    <form action="{{ route('chat.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Upload CSV</button>
    </form>

    @if (session('csv_data'))
    <form action="{{ route('chat.ask') }}" method="POST">
        @csrf
        <textarea name="question" rows="3" placeholder="Ask a question..."></textarea>
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
