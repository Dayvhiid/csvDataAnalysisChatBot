<!-- resources/views/sheet.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CSV Editor</title>

  <!-- Handsontable CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.min.css" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    #spreadsheet {
      height: 500px;
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  <div class="max-w-7xl mx-auto p-8">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold mb-2">CSV Editor</h1>
      <p class="text-gray-600">Edit your CSV data in-browser and save it to session.</p>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-4">
      <div id="spreadsheet" class="overflow-x-auto rounded-md"></div>
    </div>

    <div class="flex justify-center mt-6">
      <button id="saveBtn"
        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
        Save Changes
      </button>
    </div>
  </div>

  <!-- Handsontable JS -->
  <script src="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const container = document.getElementById('spreadsheet');

      const hot = new Handsontable(container, {
        data: {!! json_encode(session('csv_data')) !!},
         colHeaders: {!! json_encode(session('csv_headers')) !!},
        rowHeaders: true,
        // colHeaders: true,
        contextMenu: true,
        licenseKey: 'non-commercial-and-evaluation'
      });

      document.getElementById("saveBtn").addEventListener("click", () => {
        const data = hot.getData();

        fetch("{{ route('csv.save') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ data })
        })
        .then(res => res.json())
        .then(() => {
          alert("✅ Changes saved to session!");
        })
        .catch(err => {
          alert("❌ Error saving: " + err);
        });
      });
    });
  </script>

</body>
</html>
