<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('chart.render') }}">
    @csrf

    {{-- <label>X-Axis Column</label>
    <input name="x_key" required placeholder="e.g., Date" />

   <label>X-Axis Column</label>
    <input name="y_key" required placeholder="e.g., Sales" /> --}}

    <select name="x_key">
        <label>X-Axis Column</label>
    @foreach($headers as $header)
        <option value="{{ $header }}">{{ $header }}</option>
    @endforeach
</select>

<select name="y_key">
    <label>X-Axis Column</label>
    @foreach($headers as $header)
        <option value="{{ $header }}">{{ $header }}</option>
    @endforeach
</select>


    <label>Chart Type</label>
    <select name="chart_type">
        <option value="bar">Bar</option>
        <option value="line">Line</option>
        <option value="pie">Pie</option>
    </select>

    <button type="submit">Generate Chart</button>
</form>

</body>
</html>