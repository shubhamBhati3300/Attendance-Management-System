<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enroll Face - {{ $student->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
</head>
<body class="p-4">
<div class="container">
    <h1>Enroll Face: {{ $student->name }}</h1>
    <video id="video" width="480" height="360" autoplay muted></video>
    <div class="mt-2">
        <button id="capture" class="btn btn-primary">Capture & Enroll</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div id="status" class="mt-2"></div>
</div>

<script>
const video = document.getElementById('video');
const status = document.getElementById('status');

async function setup() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

    navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
        video.srcObject = stream;
    }).catch(err => { status.innerText = 'Camera error: ' + err; });
}

document.getElementById('capture').addEventListener('click', async () => {
    status.innerText = 'Detecting...';
    const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
    if (!detection) { status.innerText = 'No face detected'; return; }
    const descriptor = Array.from(detection.descriptor);

    const res = await fetch('{{ url('/students') }}/{{ $student->id }}/embedding', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ descriptor })
    });
    if (res.ok) { status.innerText = 'Enrollment saved'; }
    else { status.innerText = 'Save failed'; }
});

setup();
</script>

</body>
</html>
