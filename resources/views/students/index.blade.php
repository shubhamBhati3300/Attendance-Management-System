<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Students</h1>
    <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Add Student</a>
    <a href="{{ route('attendance.scan') }}" class="btn btn-success mb-3">Open Attendance Scanner</a>

    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Roll</th><th>Email</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($students as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->name }}</td>
                <td>{{ $s->roll_no }}</td>
                <td>{{ $s->email }}</td>
                <td>
                    <a class="btn btn-sm btn-secondary" href="{{ route('students.enroll', $s->id) }}">Enroll Face</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
