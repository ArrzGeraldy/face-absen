document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector("aside");
  const sectionDashboard = document.getElementById("section-dashboard");
  const toggleBtn = document.getElementById("toggle-sidebar");
  const mobileToggleBtn = document.getElementById("mobile-toggle");
  console.log({ sidebar });

  const alertMessage = document.getElementById("alert-message");
  const alertClose = document.getElementById("alert-close");
  console.log(alertClose);
  console.log(alertMessage);
  // Tombol utama (desktop + mobile) → buka sidebar
  toggleBtn.addEventListener("click", () => {
    if (window.innerWidth >= 1024) {
      // Desktop mode
      sidebar.classList.toggle("lg:-translate-x-0");
      sectionDashboard.classList.toggle("lg:ms-64");
    } else {
      // Mobile mode
      sidebar.classList.remove("-translate-x-64");
      // sectionDashboard.classList.add("ms-64");
    }
  });

  // Tombol di sidebar (khusus mobile) → hanya untuk menutup
  mobileToggleBtn.addEventListener("click", () => {
    if (window.innerWidth < 1024) {
      sidebar.classList.add("-translate-x-64");
      sectionDashboard.classList.remove("ms-64");
    }
  });

  // Tombole close alert message
  alertClose.addEventListener("click", () => {
    alertMessage.classList.add("hidden");
    console.log(alertClose);
    console.log(alertMessage);
  });

  // Menangani perubahan ukuran layar agar layout tetap rapi
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
      // desktop
      sidebar.classList.remove("-translate-x-64");
      sectionDashboard.classList.add("lg:ms-64");
      sectionDashboard.classList.remove("ms-64");
    } else {
      // mobile
      sidebar.classList.add("-translate-x-64");
      sectionDashboard.classList.remove("lg:ms-64");
    }
  });
});
