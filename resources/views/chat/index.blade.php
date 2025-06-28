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


<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Inter%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900"
    />

    <title>Stitch Design</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  </head>
  <body>

      @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f1f2f4] px-10 py-3">
          <div class="flex items-center gap-4 text-[#121417]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M24 4C25.7818 14.2173 33.7827 22.2182 44 24C33.7827 25.7818 25.7818 33.7827 24 44C22.2182 33.7827 14.2173 25.7818 4 24C14.2173 22.2182 22.2182 14.2173 24 4Z"
                  fill="currentColor"
                ></path>
              </svg>
            </div>
            <h2 class="text-[#121417] text-lg font-bold leading-tight tracking-[-0.015em]">File AI</h2>
          </div>
          <div class="flex flex-1 justify-end gap-8">
            <div class="flex items-center gap-9">
              <a class="text-[#121417] text-sm font-medium leading-normal" href="#">Home</a>
              <a class="text-[#121417] text-sm font-medium leading-normal" href="#">About</a>
              <a class="text-[#121417] text-sm font-medium leading-normal" href="#">Contact</a>
            </div>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#d2e2f3] text-[#121417] text-sm font-bold leading-normal tracking-[0.015em]"
            >
              <span class="truncate">Upload</span>
            </button>
            <div
              class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA8fTFLmIUBzKu0L6kGb_VqMoD81Qk6kRj1HyVXU03ndClGmeVbTOmQezp_p59fnnUIDrxo9zbNbS5lr7VhdF8pn0Kt7R6PwrF2c4j-9R5lHlS7opneqnruLXk9RE5DOaAtVRt9y4SCUVq6LGdAGln40z_h7NQvJfPHEYkf970C225U_6LJEs-vd8QI08COAr1WLdOB-_7m4S_oyLU7xfda7ZqaNG9pldHgvDdDD6Q4OQU6yN4tl6v6amDT4xktAaT20M5KwHkCOQYd");'
            ></div>
          </div>
        </header>
        <div class="px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <h2 class="text-[#121417] tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Upload your file and interact with AI</h2>
             <form action="{{ route('chat.upload') }}" method="POST" enctype="multipart/form-data" accept=".csv,.txt">
             <div class="flex flex-col p-4">
              <div class="flex flex-col items-center gap-6 rounded-xl border-2 border-dashed border-[#dde0e4] px-6 py-14">
                <div class="flex max-w-[480px] flex-col items-center gap-2">
                  <p class="text-[#121417] text-lg font-bold leading-tight tracking-[-0.015em] max-w-[480px] text-center">Drag and drop a file here</p>
                  <p class="text-[#121417] text-sm font-normal leading-normal max-w-[480px] text-center">Or browse to upload</p>
                </div>
                <button
                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#f1f2f4] text-[#121417] text-sm font-bold leading-normal tracking-[0.015em]"
                >
                  {{-- <span class="truncate">Browse Files</span> --}}
                    <input type="file" name="csv_file" required>
                    <button type="submit">Ask</button>
                </button>
              </div>
            </div>
            </form>
            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
              <label class="flex flex-col min-w-40 flex-1">
                <input
                  placeholder="Ask AI about your file"
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#121417] focus:outline-0 focus:ring-0 border border-[#dde0e4] bg-white focus:border-[#dde0e4] h-14 placeholder:text-[#677583] p-[15px] text-base font-normal leading-normal"
                  value=""
                />
              </label>
            </div>
            <h2 class="text-[#121417] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">AI Responses</h2>
            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
              <label class="flex flex-col min-w-40 flex-1">
                <textarea
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#121417] focus:outline-0 focus:ring-0 border border-[#dde0e4] bg-white focus:border-[#dde0e4] min-h-36 placeholder:text-[#677583] p-[15px] text-base font-normal leading-normal"
                ></textarea>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

