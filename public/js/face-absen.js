document.addEventListener("DOMContentLoaded", async () => {
  const user = window.userData;
  const video = document.getElementById("video");
  const canvas = document.getElementById("overlay");
  const context = canvas.getContext("2d");
  const compareBtn = document.getElementById("compare");
  const coordsDisplay = document.getElementById("location");

  // get location
  let lat;
  let lon;
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      lat = pos.coords.latitude;
      lon = pos.coords.longitude;

      coordsDisplay.innerHTML = `${lat}, ${lon}`;

      console.log("Latitude:", lat);
      console.log("Longitude:", lon);
      console.log("Accuracy:", pos.coords.accuracy);
    },
    (err) => {
      console.error("Error:", err);
    },
    {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0,
    }
  );

  const faceapi = window.faceapi;

  async function startCamera() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: {
          width: { ideal: 640 },
          height: { ideal: 480 },
        },
      });
      video.srcObject = stream;
      videoStream = stream;
    } catch (err) {
      console.error("Gagal akses kamera:", err);
      alert("Tidak bisa akses kamera. Periksa izin atau perangkat.");
    }
  }

  async function loadModels() {
    const MODEL_URL = "/models";
    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
  }

  async function detectFace() {
    const detections = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceDescriptor();

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    context.clearRect(0, 0, canvas.width, canvas.height);

    if (detections) {
      const box = detections.detection.box;
      context.beginPath();
      context.rect(box.x, box.y, box.width, box.height);
      context.lineWidth = 2;
      context.strokeStyle = "lime";
      context.stroke();

      compareBtn.disabled = false;
      compareBtn.innerHTML = "Ambil Wajah";
    } else {
      compareBtn.disabled = true;
      compareBtn.innerHTML = "Wajah tidak terdeteksi";
    }

    requestAnimationFrame(detectFace);
  }

  async function start() {
    await loadModels();
    await startCamera();

    video.addEventListener("play", () => {
      console.log("Video mulai, deteksi wajah aktif...");
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      detectFace();
    });
  }

  await start();

  // tombol: Bandingkan wajah
  compareBtn.addEventListener("click", async () => {
    if (!user) {
      alert("Belum ada wajah yang disimpan!");
      return;
    }

    const detection = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceDescriptor();
    if (!detection) {
      alert("Tidak ada wajah yang terdeteksi!");
      return;
    }
    const raw = JSON.parse(user.face_descriptors);
    const arr = JSON.parse(raw);

    const savedDescriptor = new Float32Array(arr);

    const newDescriptor = detection.descriptor;

    const distance = faceapi.euclideanDistance(savedDescriptor, newDescriptor);
    console.log("Jarak antar wajah:", distance);

    const kirim = async () => {
      try {
        const res = await fetch("/absen", {
          method: "POST",
          credentials: "same-origin",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
              .content,
          },
          body: JSON.stringify({
            lat,
            lon,
            face_distance: distance,
          }),
        });

        const data = await res.json();
        alert(data.message);
        location.reload();
      } catch (error) {
        console.log(error);
      }
    };

    await kirim();
  });
});
