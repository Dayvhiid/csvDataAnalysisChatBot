<form action="{{ route('chat.ask') }}" method="POST">
    @csrf
    <textarea name="message" placeholder="Ask a question about your CSV..." rows="3" required></textarea>
    <button type="submit">Ask</button>
</form>

@if(session('reply'))
    <div class="chat-reply">
        <strong>AI:</strong> {{ session('reply') }}
    </div>
@endif
