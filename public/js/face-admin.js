const video = document.getElementById("video");
const btnCapture = document.getElementById("capture");
const btnRetake = document.getElementById("retake");
const canvas = document.getElementById("overlay");
const context = canvas.getContext("2d");
const capturedImage = document.getElementById("captured-image");
const inputPhoto = document.querySelector("#photo");
const inputDescriptors = document.getElementById("face_descriptors");
const loader = document.getElementById("loader");

let videoStream = null;
let detectionLoop = null;
let faceapi = null; // Pindahkan ke scope global

// loader action
loader.classList.remove("hidden");
document.body.style.overflow = "hidden";
btnCapture.disabled = true;
btnCapture.innerHTML = "Loading...";

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

function stopCamera() {
  if (videoStream) {
    videoStream.getTracks().forEach((track) => track.stop());
    video.srcObject = null;
  }
  if (detectionLoop) {
    cancelAnimationFrame(detectionLoop);
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

    btnCapture.disabled = false;
    btnCapture.innerHTML = "Ambil Wajah";
  } else {
    btnCapture.disabled = true;
    btnCapture.innerHTML = "Wajah tidak terdeteksi";
  }

  detectionLoop = requestAnimationFrame(detectFace);
}

document.addEventListener("DOMContentLoaded", async () => {
  // Cek apakah sudah ada foto (untuk halaman edit)
  const existingPhoto = capturedImage.getAttribute("src");

  if (existingPhoto && existingPhoto.trim() !== "") {
    // Jika ada foto, langsung tampilkan foto dan tombol retake
    video.classList.add("hidden");
    canvas.classList.add("hidden");
    capturedImage.classList.remove("hidden");
    btnCapture.classList.add("hidden");
    btnRetake.classList.remove("hidden");

    // Hilangkan loader
    loader.classList.add("hidden");
    document.body.style.overflow = "";

    // Tetap assign faceapi untuk retake nanti
    faceapi = window.faceapi;

    return; // Stop di sini, tidak perlu load model atau start camera
  }

  // Jika tidak ada foto, lanjutkan seperti biasa
  if (!window.faceapi) {
    alert(
      "face-api.js belum ter-load, pastikan app.js sudah di-compile dan disertakan di Blade."
    );
    return;
  }

  faceapi = window.faceapi; // Assign ke variabel global

  await loadModels();
  await startCamera();

  // end loader
  loader.classList.add("hidden");
  document.body.style.overflow = "";

  // camera on
  video.addEventListener("play", () => {
    detectFace();
  });
});

// get code face-descriptor & capture face
btnCapture.addEventListener("click", async () => {
  btnCapture.disabled = true;
  btnCapture.innerHTML = "Memproses...";

  const detections = await faceapi
    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
    .withFaceLandmarks()
    .withFaceDescriptor();

  if (!detections) {
    alert("Wajah tidak terdeteksi. Silakan coba lagi.");
    btnCapture.disabled = false;
    btnCapture.innerHTML = "Ambil Wajah";
    return;
  }

  const descriptor = Array.from(detections.descriptor);
  inputDescriptors.value = JSON.stringify(descriptor);

  // Capture foto
  const photoCanvas = document.createElement("canvas");
  photoCanvas.width = video.videoWidth;
  photoCanvas.height = video.videoHeight;
  photoCanvas.getContext("2d").drawImage(video, 0, 0);
  const photoData = photoCanvas.toDataURL("image/png");

  inputPhoto.value = photoData;
  capturedImage.src = photoData;

  // Stop camera dan sembunyikan video
  stopCamera();
  video.classList.add("hidden");
  canvas.classList.add("hidden");
  capturedImage.classList.remove("hidden");

  // Toggle tombol
  btnCapture.classList.add("hidden");
  btnRetake.classList.remove("hidden");
});

// Retake photo
btnRetake.addEventListener("click", async () => {
  btnRetake.classList.add("hidden");
  btnCapture.classList.remove("hidden");
  btnCapture.disabled = true;
  btnCapture.innerHTML = "Loading...";

  capturedImage.classList.add("hidden");
  video.classList.remove("hidden");
  canvas.classList.remove("hidden");

  // Clear data
  inputPhoto.value = "";
  inputDescriptors.value = "";

  // Load models jika belum ter-load
  if (!faceapi) {
    faceapi = window.faceapi;
  }

  if (faceapi && !faceapi.nets.tinyFaceDetector.isLoaded) {
    await loadModels();
  }

  // Restart camera
  await startCamera();

  // Start detection setelah video play
  video.addEventListener(
    "play",
    () => {
      detectFace();
    },
    { once: true }
  );
});
