<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
</head>
<body class="p-4">
<div class="container">
    <h1>Attendance Scanner</h1>
    <video id="video" width="480" height="360" autoplay muted></video>
    <div id="info" class="mt-2"></div>
    <a href="{{ route('students.index') }}" class="btn btn-secondary mt-2">Back</a>
</div>

<script>
const video = document.getElementById('video');
const info = document.getElementById('info');

async function setup() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

    navigator.mediaDevices.getUserMedia({ video: true }).then(stream => { video.srcObject = stream; }).catch(e => info.innerText = 'Camera error: '+e);

    // fetch known descriptors
    const resp = await fetch('{{ route('students.labels') }}');
    const labels = await resp.json();

    const labeledDescriptors = labels.map(l => new faceapi.LabeledFaceDescriptors(l.name, l.descriptors.map(d => new Float32Array(d))));
    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);

    video.addEventListener('play', () => {
        const canvas = faceapi.createCanvasFromMedia(video);
        document.body.append(canvas);
        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
            const resized = faceapi.resizeResults(detections, displaySize);
            canvas.getContext('2d').clearRect(0,0,canvas.width,canvas.height);
            const results = resized.map(d => faceMatcher.findBestMatch(d.descriptor));
            results.forEach((res, i) => {
                const box = resized[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, { label: res.toString() });
                drawBox.draw(canvas);
                if (res.label !== 'unknown') {
                    // find student id by name
                    const student = labels.find(x => x.name === res.label);
                    if (student) {
                        // mark attendance
                        fetch('{{ route('attendance.mark') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ student_id: student.id })
                        }).then(r => r.json()).then(j => { info.innerText = 'Marked '+res.label+' -> '+j.status; });
                    }
                }
            });
        }, 1200);
    });
}

setup();
</script>

</body>
</html>
