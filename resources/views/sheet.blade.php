<!DOCTYPE html>
<html>
<head>
    <title>CSV Viewer with Jspreadsheet</title>

    <!-- Jspreadsheet & JSuites -->
    <script src="https://jspreadsheet.com/v11/jspreadsheet.js"></script>
    <script src="https://jsuites.net/v5/jsuites.js"></script>
    <link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://jspreadsheet.com/v11/jspreadsheet.css" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons" />
</head>
<body>
    <h2>CSV Sheet Viewer (Jspreadsheet)</h2>
    <div id="spreadsheet"></div>

    <script>
        // License Key (Note: Only valid for 24h — get a valid one from jspreadsheet.com)
        jspreadsheet.setLicense('YOUR_LICENSE_HERE');

        // Load data from PHP
        const csvData = @json(array_values($csvData ?? []));

        // Convert associative array to 2D array
        const rows = csvData.map(row => Object.values(row));

        // Get headers
        const headers = csvData.length > 0 ? Object.keys(csvData[0]) : [];

        // Generate column config for jspreadsheet
        const columns = headers.map(h => ({ title: h, type: 'text' }));

        jspreadsheet(document.getElementById('spreadsheet'), {
            data: rows,
            columns: columns,
            tabs: true,
            toolbar: true,
            worksheets: [{
                minDimensions: [headers.length || 6, rows.length || 6]
            }],
        });
    </script>
</body>
</html>
