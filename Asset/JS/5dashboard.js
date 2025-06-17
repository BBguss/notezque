// Variabel global untuk notifikasi
let notificationDropdown = null;
let notificationBell = null;
let notificationBadge = null;
let notificationList = null;

document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM loaded ✅");
  initializeCalendar();
  initializeNotifications();

  // Panggil pertama kali saat halaman dimuat
  loadNotifications();

  // Auto-refresh notifikasi setiap 10 detik
  setInterval(loadNotifications, 10000);

  // Refresh saat kembali ke tab (user balik ke halaman)
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") {
      loadNotifications();
    }
  });
});

// ========================= CALENDAR FUNCTIONS =========================
function initializeCalendar() {
  // DOM Elements
  const nextbtn = document.querySelector(".next");
  const tampilBulanTahun = document.querySelector(".tanggal");
  const prevbtn = document.querySelector(".prev");
  const kontainerHari = document.querySelector(".harian");
  const popupAcara = document.getElementById("eventPopup");
  const judulAcara = document.getElementById("eventTitle");
  const deskripsiAcara = document.getElementById("eventDesc");
  const tutupPopup = document.querySelector(".close-popup");

  const namaBulan = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  let sekarang = new Date();
  let bulanSekarang = sekarang.getMonth();
  let tahunSekarang = sekarang.getFullYear();

  // Function to fetch events from database
  async function ambilAcara() {
    try {
      const response = await fetch(
        `../dashboard/get_events.php?bulan=${
          bulanSekarang + 1
        }&tahun=${tahunSekarang}`
      );

      if (!response.ok) {
        throw new Error("Failed to fetch data");
      }

      const data = await response.json();

      const acaraFormat = {};
      if (data && typeof data === "object") {
        for (const tanggalStr in data) {
          const bagianTanggal = tanggalStr.split("-");
          if (bagianTanggal.length === 3) {
            const hari = parseInt(bagianTanggal[2]);
            acaraFormat[hari] = data[tanggalStr];
          }
        }
      }

      return acaraFormat;
    } catch (error) {
      console.error("Error:", error);
      return {};
    }
  }

  function tampilkanTooltip(element) {
    sembunyikanTooltip();

    // Create and position new tooltip
    const tooltip = document.createElement("div");
    tooltip.className = "event-tooltip";
    tooltip.textContent = element.dataset.judul || "";
    document.body.appendChild(tooltip);

    const dotRect = element.getBoundingClientRect();
    tooltip.style.left = `${dotRect.left + window.scrollX}px`;
    tooltip.style.top = `${dotRect.top + window.scrollY - 30}px`;
  }

  function sembunyikanTooltip() {
    const tooltip = document.querySelector(".event-tooltip");
    tooltip?.remove();
  }

  // Main calendar rendering function
  async function renderKalender() {
    const acara = await ambilAcara();

    tampilBulanTahun.textContent = `${namaBulan[bulanSekarang]} ${tahunSekarang}`;

    const hariPertama = new Date(tahunSekarang, bulanSekarang, 1);
    const hariTerakhir = new Date(tahunSekarang, bulanSekarang + 1, 0);
    const hariTerakhirBulanLalu = new Date(tahunSekarang, bulanSekarang, 0);

    const offsetHari = hariPertama.getDay();
    const totalHari = hariTerakhir.getDate();

    kontainerHari.innerHTML = "";

    // Previous month days
    for (let i = offsetHari; i > 0; i--) {
      const tgl = hariTerakhirBulanLalu.getDate() - i + 1;
      const elemenHari = document.createElement("div");
      elemenHari.className = "hari hariSebelum";
      elemenHari.textContent = tgl;
      kontainerHari.appendChild(elemenHari);
    }

    // Current month days
    for (let tgl = 1; tgl <= totalHari; tgl++) {
      const elemenHari = document.createElement("div");

      const isHariIni =
        tgl === sekarang.getDate() &&
        bulanSekarang === sekarang.getMonth() &&
        tahunSekarang === sekarang.getFullYear();

      const adaAcara = acara[tgl] && acara[tgl].length > 0;

      elemenHari.className = `hari ${isHariIni ? "hari-ini" : ""}`;
      elemenHari.textContent = tgl;
      elemenHari.dataset.tgl = tgl;

      if (adaAcara) {
        const titikContainer = document.createElement("div");
        titikContainer.className = "titik-container";

        const jumlahTitik = Math.min(acara[tgl].length, 3);
        const warnaTitik = ["#ea4335", "#4285f4", "#34a853"];

        for (let i = 0; i < jumlahTitik; i++) {
          const titikAcara = document.createElement("span");
          titikAcara.className = "titik-event";
          titikAcara.style.backgroundColor = warnaTitik[i];

          if (acara[tgl][i] && acara[tgl][i].judul) {
            titikAcara.dataset.judul = acara[tgl][i].judul;
          }

          titikAcara.addEventListener("mouseenter", (e) =>
            tampilkanTooltip(titikAcara, e)
          );
          titikAcara.addEventListener("mouseleave", sembunyikanTooltip);
          titikAcara.addEventListener("mousemove", (e) => e.stopPropagation());

          titikContainer.appendChild(titikAcara);
        }

        elemenHari.appendChild(titikContainer);
      }

      kontainerHari.appendChild(elemenHari);
    }

    // Next month days
    const sisaHari = 42 - (offsetHari + totalHari);
    for (let tgl = 1; tgl <= sisaHari; tgl++) {
      const elemenHari = document.createElement("div");
      elemenHari.className = "hari hariSetelah";
      elemenHari.textContent = tgl;
      kontainerHari.appendChild(elemenHari);
    }
  }

  // Event listeners for month navigation
  if (nextbtn) {
    nextbtn.addEventListener("click", () => {
      bulanSekarang = (bulanSekarang + 1) % 12;
      if (bulanSekarang === 0) tahunSekarang++;
      renderKalender();
    });
  }

  if (prevbtn) {
    prevbtn.addEventListener("click", () => {
      bulanSekarang = (bulanSekarang - 1 + 12) % 12;
      if (bulanSekarang === 11) tahunSekarang--;
      renderKalender();
    });
  }

  // Initial render
  renderKalender();
}

// ========================= NOTIFICATION FUNCTIONS =========================

// Inisialisasi elemen dan event listener
function initializeNotifications() {
  notificationDropdown = document.getElementById("notificationDropdown");
  notificationBell = document.getElementById("notificationBell");
  notificationBadge = document.getElementById("notificationBadge");
  notificationList = document.getElementById("notificationList");

  // Pastikan elemen ada sebelum menambahkan event listener
  if (
    !notificationBell ||
    !notificationDropdown ||
    !notificationBadge ||
    !notificationList
  ) {
    console.warn("Notification elements not found");
    return;
  }

  // Event listener untuk toggle dropdown
  notificationBell.addEventListener("click", function (e) {
    console.log("Lonceng diklik ✅"); // Tambahkan ini
    e.stopPropagation();
    toggleNotificationDropdown();
  });
  // Event listener untuk mark all read
  const markAllReadBtn = document.getElementById("markAllRead");
  if (markAllReadBtn) {
    markAllReadBtn.addEventListener("click", function () {
      markAllNotificationsRead();
    });
  }

  // Tutup dropdown jika klik di luar
  document.addEventListener("click", function (e) {
    if (
      notificationDropdown &&
      notificationBell &&
      !notificationDropdown.contains(e.target) &&
      !notificationBell.contains(e.target)
    ) {
      closeNotificationDropdown();
    }
  });
}

// Toggle dropdown notifikasi
function toggleNotificationDropdown() {
  if (!notificationDropdown) return;

  if (notificationDropdown.classList.contains("show")) {
    closeNotificationDropdown();
  } else {
    openNotificationDropdown();
  }
}

// Buka dropdown notifikasi
function openNotificationDropdown() {
  if (notificationDropdown) {
    notificationDropdown.classList.add("show");
    console.log(
      "Dropdown class ditambahkan ✅",
      notificationDropdown.classList
    );
    loadNotifications(); // Refresh data saat dibuka
  }
}

// Tutup dropdown notifikasi
function closeNotificationDropdown() {
  if (notificationDropdown) {
    notificationDropdown.classList.remove("show");
  }
}

// Load notifikasi dari server
function loadNotifications() {
  if (!notificationBadge || !notificationList) return;

  fetch("../dashboard/get_notifications.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        updateNotificationBadge(data.unread_count);
        displayNotifications(data.notifications);
      } else {
        console.error("Error loading notifications:", data.message);
        // Fallback jika error
        updateNotificationBadge(0);
        displayNotifications([]);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      // Fallback jika error
      updateNotificationBadge(0);
      displayNotifications([]);
    });
}

// Update badge jumlah notifikasi
function updateNotificationBadge(count) {
  if (!notificationBadge) return;

  if (count > 0) {
    notificationBadge.textContent = count > 99 ? "99+" : count;
    notificationBadge.classList.remove("hidden");
  } else {
    notificationBadge.classList.add("hidden");
  }
}

// Tampilkan notifikasi di dropdown
function displayNotifications(notifications) {
  if (!notificationList) return;

  if (notifications.length === 0) {
    notificationList.innerHTML = `
            <div class="no-notifications">
                <p>Tidak ada notifikasi</p>
            </div>
        `;
    return;
  }

  let html = "";
  notifications.forEach((notification) => {
    html += `
            <div class="notification-item ${
              notification.is_read ? "" : "unread"
            }" 
                 onclick="markAsRead(${notification.id_notification})">
                <div class="notification-title">${escapeHtml(
                  notification.title
                )}</div>
                <div class="notification-message">${escapeHtml(
                  notification.message
                )}</div>
                <div class="notification-time">${formatTime(
                  notification.created_at
                )}</div>
            </div>
        `;
  });

  notificationList.innerHTML = html;
}

// Format waktu notifikasi
function formatTime(timestamp) {
  const now = new Date();
  const time = new Date(timestamp);
  const diff = now - time;

  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(diff / 3600000);
  const days = Math.floor(diff / 86400000);

  if (minutes < 1) return "Baru saja";
  if (minutes < 60) return `${minutes} menit yang lalu`;
  if (hours < 24) return `${hours} jam yang lalu`;
  return `${days} hari yang lalu`;
}

// Escape HTML untuk keamanan
function escapeHtml(text) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
}

// Mark notifikasi sebagai dibaca
function markAsRead(notificationId) {
  fetch("../dashboard/mark_notification_read.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ notification_id: notificationId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        loadNotifications(); // Refresh notifikasi
      } else {
        console.error("Error marking notification as read:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Mark semua notifikasi sebagai dibaca
function markAllNotificationsRead() {
  fetch("../dashboard/mark_all_notifications_read.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        loadNotifications(); // Refresh notifikasi
      } else {
        console.error("Error marking all notifications as read:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}