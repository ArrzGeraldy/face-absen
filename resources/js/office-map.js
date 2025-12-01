import L from "leaflet";

document.addEventListener("DOMContentLoaded", () => {
  const latitudeInput = document.getElementById("latitudeInput");
  const longitudeInput = document.getElementById("longitudeInput");
  const mapElement = document.getElementById("map");

  // Jika elemen map tidak ada, return
  if (!mapElement) return;

  // Inisialisasi map (default Jakarta)
  const map = L.map("map").setView([-6.2, 106.8], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);

  let marker;

  const btnGetCurrent = document.getElementById("getCurrentLocation");

  // ========== 1. Dapatkan lokasi saat ini ==========
  btnGetCurrent.addEventListener("click", (e) => {
    e.preventDefault();
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;

        console.log("Latitude:", lat);
        console.log("Longitude:", lon);
        console.log("Accuracy:", pos.coords.accuracy);
        console.log(
          "Source:",
          pos.coords.altitude !== null ? "GPS" : "Network/WiFi/IP"
        );

        updateMarker(lat, lon);
        updateInputs(lat, lon);
      },
      (err) => {
        console.error("Error:", err);
        alert(
          "Gagal mendapatkan lokasi. Coba dekatkan ke jendela atau aktifkan GPS."
        );
      },
      {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0,
      }
    );
  });

  // ========== 2. Klik map → pindahkan marker + update input ==========
  map.on("click", function (e) {
    const { lat, lng } = e.latlng;
    updateMarker(lat, lng);
    updateInputs(lat, lng);
  });

  // ========== 3. Input manual → pindahkan marker ==========
  latitudeInput.addEventListener("input", () => moveMarkerFromInput());
  longitudeInput.addEventListener("input", () => moveMarkerFromInput());

  function moveMarkerFromInput() {
    const lat = parseFloat(latitudeInput.value);
    const lon = parseFloat(longitudeInput.value);

    if (!isNaN(lat) && !isNaN(lon)) {
      updateMarker(lat, lon);
    }
  }

  // ----------- Helper update marker ----------
  function updateMarker(lat, lon) {
    map.setView([lat, lon], 15);

    if (marker) map.removeLayer(marker);

    marker = L.marker([lat, lon]).addTo(map);
  }

  // ----------- Helper update input ----------
  function updateInputs(lat, lon) {
    latitudeInput.value = lat.toFixed(7);
    longitudeInput.value = lon.toFixed(7);
  }

  // ========== 4. Load existing location if available ==========
  const existingLat = parseFloat(latitudeInput.value);
  const existingLon = parseFloat(longitudeInput.value);

  if (!isNaN(existingLat) && !isNaN(existingLon)) {
    updateMarker(existingLat, existingLon);
  }
});
