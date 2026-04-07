<!DOCTYPE html>
<html>
<head>
    <title>Generate Schedule</title>
</head>
<body>
    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('schedules.generate') }}" method="POST">
        @csrf
        <label>Number of halls:</label>
        <input type="number" name="halls" min="1" required><br><br>

        <label>Schedule type:</label>
        <select name="type" required>
            <option value="course">Course Schedule</option>
            <option value="exam">Exam Schedule</option>
        </select><br><br>

        <button type="submit">Generate Schedule</button>
    </form>
</body>
</html>