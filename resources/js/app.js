import "./bootstrap";

import Alpine from "alpinejs";
import * as faceapi from "face-api.js";
import { createIcons, icons } from "lucide";

import "leaflet/dist/leaflet.css";
import L from "leaflet";

// Fix icon path issue
import icon from "leaflet/dist/images/marker-icon.png";
import iconShadow from "leaflet/dist/images/marker-shadow.png";

let DefaultIcon = L.icon({
  iconUrl: icon,
  shadowUrl: iconShadow,
});
L.Marker.prototype.options.icon = DefaultIcon;

// ✅ TAMBAHKAN INI - Expose Leaflet ke window agar bisa dipakai di inline script
window.L = L;

createIcons({ icons });
window.Alpine = Alpine;
window.faceapi = faceapi;

Alpine.start();
