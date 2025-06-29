<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSV Chatbot</title>
</head>
<body>
    <h2>Upload a CSV File</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('chat.upload') }}" method="POST" enctype="multipart/form-data" accept=".csv,.txt">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Upload</button>
    </form>

    @if(session('csv_data'))
        <hr>
        <h2>Ask a Question About the CSV</h2>

        <form action="{{ route('chat.ask') }}" method="POST">
            @csrf
            <textarea name="message" rows="4" cols="50" placeholder="Ask your question..." required></textarea><br>
            <button type="submit">Ask</button>
        </form>
    @endif

    @if(session('reply'))
        <hr>
        <h3><strong>AI Reply:</strong></h3>
        <p>{{ session('reply') }}</p>
    @endif

    @if (session('chat'))
    <div class="chat-history">
        @foreach (session('chat') as $entry)
            <div class="chat-question"><strong>You:</strong> {{ $entry['question'] }}</div>
            <div class="chat-answer"><strong>Bot:</strong> {!! nl2br(e($entry['answer'])) !!}</div>
        @endforeach
    </div>
@endif

</body>
</html>


