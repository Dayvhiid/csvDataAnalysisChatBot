<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spreadsheet Editor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@13.0.0/dist/handsontable.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/handsontable@13.0.0/dist/handsontable.min.js"></script>
</head>
<body>
    <div id="spreadsheet" style="margin: 20px;"></div>

    <form method="POST" action="{{ route('csv.save') }}">
        @csrf
        <input type="hidden" name="data" id="csvData">
        <button type="submit">Save Changes</button>
    </form>

    <script>
        const container = document.getElementById('spreadsheet');
        const data = @json($rows);
        const hot = new Handsontable(container, {
            data: data,
            rowHeaders: true,
            colHeaders: true,
            licenseKey: 'non-commercial-and-evaluation',
        });

        document.querySelector('form').addEventListener('submit', function () {
            const updatedData = hot.getData();
            document.getElementById('csvData').value = JSON.stringify(updatedData);
        });
    </script>
</body>
</html>
