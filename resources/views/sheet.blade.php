<!DOCTYPE html>
<html>
<head>
    <title>Handsontable Spreadsheet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@13.0.0/dist/handsontable.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@13.0.0/dist/handsontable.min.js"></script>
</head>
<body>
    <h2>Spreadsheet Interface</h2>
    <div id="spreadsheet" style="width: 100%; height: 500px;"></div>

    <script>
        // Example data from Laravel (you can pass in session or controller)
        const data = @json(session('csv_data', []));

        const container = document.getElementById('spreadsheet');
        const hot = new Handsontable(container, {
            data: data,
            rowHeaders: true,
            colHeaders: true,
            filters: true,
            dropdownMenu: true,
            licenseKey: 'non-commercial-and-evaluation', // Use valid key in production
        });



        // Save changes via AJAX
        hot.addHook('afterChange', function(changes, source) {
            if (source === 'loadData') return; // prevent loop

            fetch('/save-csv-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    data: hot.getData()
                }),
            });
        });

    </script>
</body>
</html>
