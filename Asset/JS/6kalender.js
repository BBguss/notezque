// ================================================
//              SISTEM KALENDER SEDERHANA
// ================================================

document.addEventListener("DOMContentLoaded", function () {
  
  // =============== DATA KONFIGURASI ===============
  const data = {
    tanggalSekarang: new Date(),
    tanggalDipilih: null,
    daftarAcara: [],
    acaraTerpilih: null,
    namaBulan: [
      "Januari", "Februari", "Maret", "April", "Mei", "Juni",
      "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ],
    prioritas: {
      rendah: { nilai: 1, warna: "#4caf50", label: "Rendah" },
      sedang: { nilai: 2, warna: "#ff9800", label: "Sedang" },
      tinggi: { nilai: 3, warna: "#f44336", label: "Tinggi" }
    },
    prioritasDefault: "rendah"
  };

  // =============== KONFIGURASI ELEMEN HTML ===============
  const elemen = {
    tampilanTanggal: document.querySelector(".tglBln"),
    tampilanBulanTahun: document.querySelector(".bln-thn"),
    tombolNext: document.querySelector(".next"),
    tombolPrev: document.querySelector(".prev"),
    popup: document.getElementById("modal"),
    modalTitle: document.querySelector(".modalTitle"),
    inputJudul: document.getElementById("title"),
    inputDeskripsi: document.getElementById("desk"),
    tombolTutup: document.getElementById("closeBtn"),
    tombolSimpan: document.getElementById("save"),
    inputTanggalCustom: document.getElementById("tanggalCustom"),
    inputWaktuCustom: document.getElementById("waktuCustom"),
    containerAcara: document.querySelector(".acara-container"),
    daftarAcara: document.querySelector(".acara-container"),
    btnTambahAcara: document.getElementById("btnTambahAcara"),
    activityActions: document.querySelector(".activity-actions"),
    btnEdit: document.querySelector(".edit-button"),
    btnHapus: document.querySelector(".delete-button"),
    sideModal: document.getElementById("sideModalDetail"),
    btnTutupSideModal: document.getElementById("closeSideModalBtn"),
    btnEditSideAcara: document.getElementById("editSideAcaraBtn"),
    btnHapusSideAcara: document.getElementById("hapusSideAcaraBtn"),
    sideDetailJudul: document.getElementById("sideDetailJudul"),
    sideDetailDeskripsi: document.getElementById("sideDetailDeskripsi"),
    sideDetailTanggalMulai: document.getElementById("sideDetailTanggalMulai"),
    sideDetailTanggalBerakhir: document.getElementById("sideDetailTanggalBerakhir"),
    sideDetailDurasi: document.getElementById("sideDetailDurasi"),
    sideDetailPrioritas: document.getElementById("sideDetailPrioritas")
  };

  // =============== FUNGSI HELPER ===============
  
  function formatTanggalToISO(date) {
    return date.toISOString().split('T')[0];
  }

  function formatTanggal(waktuDatabase) {
    const tanggal = new Date(waktuDatabase);
    return formatTanggalToISO(tanggal);
  }

  function formatWaktu(waktuDatabase) {
    const waktu = new Date(waktuDatabase);
    return waktu.toTimeString().slice(0, 5);
  }

  function formatTanggalTampilan(tanggalStr) {
    const tanggal = new Date(tanggalStr);
    const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const bulan = data.namaBulan;
    
    return `${hari[tanggal.getDay()]}, ${tanggal.getDate()} ${bulan[tanggal.getMonth()]} ${tanggal.getFullYear()}`;
  }

  function hitungDurasi(mulai, akhir) {
    const tanggalMulai = new Date(mulai);
    const tanggalAkhir = new Date(akhir);
    const selisih = tanggalAkhir - tanggalMulai;
    const hari = Math.ceil(selisih / (1000 * 60 * 60 * 24));
    return hari <= 1 ? "1 hari" : `${hari} hari`;
  }

  function getWaktuDipilih() {
    const tombolKustom = document.getElementById('kustom');
    if (tombolKustom && tombolKustom.classList.contains('aktif') && elemen.inputWaktuCustom) {
      return elemen.inputWaktuCustom.value || "23:00";
    }
    return "23:00";
  }

  // =============== FUNGSI BADGE DAN UI ===============
  
  function updateEventBadge(count) {
    const badge = document.getElementById('eventBadge');
    if (badge) {
      badge.textContent = count;
      badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
  }

  function getSortBadge(sortType, acara) {
    switch (sortType) {
      case 'tanggal_desc':
        return `<small style="color: #6c757d; font-size: 0.75em;"><iconify-icon icon="mdi:clock-outline" width="12" height="12"></iconify-icon> Terbaru</small>`;
      case 'tanggal_asc':
        return `<small style="color: #6c757d; font-size: 0.75em;"><iconify-icon icon="mdi:clock-outline" width="12" height="12"></iconify-icon> Terlama</small>`;
      case 'prioritas':
        return `<small style="color: ${data.prioritas[acara.prioritas]?.warna}; font-size: 0.75em; font-weight: 600;"><iconify-icon icon="mdi:flag" width="12" height="12"></iconify-icon> Prioritas</small>`;
      case 'judul':
        return `<small style="color: #6c757d; font-size: 0.75em;"><iconify-icon icon="mdi:sort-alphabetical-ascending" width="12" height="12"></iconify-icon> A-Z</small>`;
      default:
        return '';
    }
  }

  // =============== FUNGSI PRIORITAS ===============
  
  function inisialisasiPrioritas() {
    const tombolPrioritas = document.querySelectorAll('.tombol-grup button');
    tombolPrioritas.forEach(tombol => {
      if (tombol.classList.contains('rendah') || tombol.classList.contains('sedang') || tombol.classList.contains('tinggi')) {
        tombol.addEventListener('click', function() {
          document.querySelectorAll('.tombol-grup button').forEach(t => {
            if (t.classList.contains('rendah') || t.classList.contains('sedang') || t.classList.contains('tinggi')) {
              t.classList.remove('aktif');
            }
          });
          this.classList.add('aktif');
        });
      }
    });
  }

  function setPrioritasDefault() {
    const tombolRendah = document.querySelector('.tombol-grup .rendah');
    if (tombolRendah) {
      document.querySelectorAll('.tombol-grup button').forEach(t => {
        if (t.classList.contains('rendah') || t.classList.contains('sedang') || t.classList.contains('tinggi')) {
          t.classList.remove('aktif');
        }
      });
      tombolRendah.classList.add('aktif');
    }
  }

  function getPrioritasDipilih() {
    const tombolAktif = document.querySelector('.tombol-grup button.aktif');
    if (!tombolAktif) return 'rendah';
    
    if (tombolAktif.classList.contains('tinggi')) return 'tinggi';
    if (tombolAktif.classList.contains('sedang')) return 'sedang';
    return 'rendah';
  }

  function setPrioritasDipilih(prioritasKey) {
    document.querySelectorAll('.tombol-grup button').forEach(tombol => {
      if (tombol.classList.contains('rendah') || tombol.classList.contains('sedang') || tombol.classList.contains('tinggi')) {
        tombol.classList.remove('aktif');
      }
    });
    
    const tombolTarget = document.querySelector(`.tombol-grup .${prioritasKey}`);
    if (tombolTarget) {
      tombolTarget.classList.add('aktif');
    }
  }

  // =============== FUNGSI TENGGAT ===============
  
  function inisialisasiTenggat() {
    const tombolTenggat = {
      skrg: document.getElementById('skrg'),
      besok: document.getElementById('besok'), 
      kustom: document.getElementById('kustom')
    };

    const customInputContainer = document.querySelector('.custom-input-container');

    // Setup event listeners untuk tombol tenggat
    Object.entries(tombolTenggat).forEach(([key, btn]) => {
      if (btn) {
        btn.addEventListener('click', function() {
          // Reset semua tombol
          Object.values(tombolTenggat).forEach(b => {
            if (b) b.classList.remove('aktif');
          });
          
          // Set tombol aktif
          this.classList.add('aktif');
          
          // Atur tanggal berdasarkan pilihan
          const today = new Date();
          let targetDate = new Date(today);
          
          if (key === 'besok') {
            targetDate.setDate(today.getDate() + 1);
          } else if (key === 'kustom') {
            if (customInputContainer) {
              customInputContainer.style.display = 'flex';
            }
            return;
          }
          
          if (key !== 'kustom' && customInputContainer) {
            customInputContainer.style.display = 'none';
          }
          
          data.tanggalDipilih = formatTanggalToISO(targetDate);
        });
      }
    });

    // Set default hari ini
    if (tombolTenggat.skrg) {
      tombolTenggat.skrg.classList.add('aktif');
    }
    
    const today = new Date();
    data.tanggalDipilih = formatTanggalToISO(today);

    // Setup input custom
    if (elemen.inputTanggalCustom) {
      elemen.inputTanggalCustom.addEventListener('change', function() {
        data.tanggalDipilih = this.value;
      });
      elemen.inputTanggalCustom.value = formatTanggalToISO(today);
    }

    if (elemen.inputWaktuCustom) {
      elemen.inputWaktuCustom.value = "23:00";
    }
  }

  // =============== FUNGSI FILTER SEDERHANA ===============
  
  function inisialisasiFilter() {
    const priorityFilter = document.getElementById('priorityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearBtn = document.getElementById('clearFilters');

    // Event listeners
    if (priorityFilter) {
      priorityFilter.addEventListener('change', applyFilters);
    }
    
    if (sortFilter) {
      sortFilter.addEventListener('change', applyFilters);
    }

    if (clearBtn) {
      clearBtn.addEventListener('click', clearFilters);
    }
  }

  async function applyFilters() {
    const priorityFilter = document.getElementById('priorityFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    const prioritas = priorityFilter ? priorityFilter.value : '';
    const sort = sortFilter ? sortFilter.value : 'prioritas';

    try {
      const url = `filter_events.php?prioritas=${prioritas}&sort=${sort}`;
      const response = await fetch(url);
      
      if (!response.ok) {
        throw new Error('Gagal mengambil data filter');
      }

      const result = await response.json();

      if (result.success) {
        // Update data global dengan hasil filter
        data.daftarAcara = result.data.map((item) => ({
          id: item.id_acara,
          judul: item.judul_acara,
          deskripsi: item.desc_acara,
          tanggal: formatTanggal(item.waktu_acara),
          waktu: formatWaktu(item.waktu_acara),
          tanggalMulai: formatTanggal(item.waktu_acara),
          tanggalAkhir: formatTanggal(item.waktu_acara),
          waktuMulai: formatWaktu(item.waktu_acara),
          waktuAkhir: formatWaktu(item.waktu_acara),
          prioritas: item.prioritas || 'rendah',
          warna: data.prioritas[item.prioritas || 'rendah'].warna
        }));

        // Update tampilan dengan sorting yang diterapkan
        tampilkanDaftarAcaraTerurut(sort);
        tampilkanKalender();
        updateEventBadge(result.total);

        console.log(`Filter applied: ${result.total} events found with sort: ${sort}`);
        
      } else {
        throw new Error('Filter gagal dijalankan');
      }

    } catch (error) {
      console.error('Error applying filters:', error);
      alert('Gagal menerapkan filter: ' + error.message);
    }
  }

  function clearFilters() {
    const priorityFilter = document.getElementById('priorityFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    if (priorityFilter) priorityFilter.value = '';
    if (sortFilter) sortFilter.value = 'prioritas';
    
    // Load semua data dengan sort prioritas
    ambilAcaraDariDatabase();
  }

  // =============== FUNGSI TAMPILAN ===============

  function tampilkanDaftarAcaraTerurut(sortType = 'prioritas') {
    const container = elemen.containerAcara || elemen.daftarAcara;
    
    if (!container) {
      console.error('Container acara tidak ditemukan');
      return;
    }

    container.innerHTML = "";

    if (data.daftarAcara.length === 0) {
      const noEventsMsg = document.createElement('p');
      noEventsMsg.className = 'no-events';
      noEventsMsg.style.cssText = 'text-align: center; padding: 2rem; color: #999; font-style: italic;';
      noEventsMsg.textContent = 'Belum ada acara';
      container.appendChild(noEventsMsg);
      return;
    }

    // Sort berdasarkan tipe yang dipilih
    let acaraTerurut = [...data.daftarAcara];
    
    switch (sortType) {
      case 'tanggal_desc':
        acaraTerurut.sort((a, b) => {
          const dateA = new Date(a.tanggal + ' ' + a.waktu);
          const dateB = new Date(b.tanggal + ' ' + b.waktu);
          return dateB - dateA; // Terbaru ke terlama
        });
        break;
        
      case 'tanggal_asc':
        acaraTerurut.sort((a, b) => {
          const dateA = new Date(a.tanggal + ' ' + a.waktu);
          const dateB = new Date(b.tanggal + ' ' + b.waktu);
          return dateA - dateB; // Terlama ke terbaru
        });
        break;
        
      case 'judul':
        acaraTerurut.sort((a, b) => {
          return a.judul.localeCompare(b.judul); // A-Z
        });
        break;
        
      default: // prioritas (default)
        acaraTerurut.sort((a, b) => {
          const prioritasA = data.prioritas[a.prioritas]?.nilai || 1;
          const prioritasB = data.prioritas[b.prioritas]?.nilai || 1;
          if (prioritasB !== prioritasA) return prioritasB - prioritasA; // Tinggi ke rendah
          
          // Jika prioritas sama, sort berdasarkan tanggal
          const dateA = new Date(a.tanggal + ' ' + a.waktu);
          const dateB = new Date(b.tanggal + ' ' + b.waktu);
          return dateA - dateB;
        });
        break;
    }

    // Render acara yang sudah diurutkan
    acaraTerurut.forEach((acara, index) => {
      const elemenAcara = document.createElement("div");
      elemenAcara.className = "acara";
      elemenAcara.style.cssText = `border-left: 4px solid ${data.prioritas[acara.prioritas]?.warna || data.prioritas.rendah.warna}; cursor: pointer; position: relative; margin-bottom: 10px; padding: 15px; background: #f9f9f9; border-radius: 8px; transition: all 0.2s ease;`;
      elemenAcara.dataset.idAcara = acara.id;
      
      const prioritasInfo = data.prioritas[acara.prioritas] || data.prioritas.rendah;
      
      // Tambahkan indikator urutan untuk prioritas (default sort)
      let sortIndicator = '';
      if (sortType === 'prioritas') {
        sortIndicator = `<span class="sort-indicator" style="position: absolute; top: 8px; left: 8px; background: ${prioritasInfo.warna}; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.3);">#${index + 1}</span>`;
      }
      
      elemenAcara.innerHTML = `
        ${sortIndicator}
        <div class="acara-content" style="${sortIndicator ? 'margin-top: 20px;' : ''}">
          <h4 style="margin-bottom: 8px; color: #333; font-size: 1.05rem; font-weight: 600;">${acara.judul}</h4>
          <p class="event-desc" style="color: #666; margin-bottom: 10px; font-size: 0.9rem; line-height: 1.5;">${acara.deskripsi || "Tidak ada deskripsi"}</p>
          <p class="event-date" style="color: #555; margin-bottom: 8px; font-size: 0.85rem; font-weight: 500;">${formatTanggalTampilan(acara.tanggal)} pukul ${acara.waktu}</p>
          <div class="event-meta" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="prioritas-badge" style="background-color: ${prioritasInfo.warna}; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.8em; font-weight: 500; display: inline-block;">
              ${prioritasInfo.label}
            </span>
            ${getSortBadge(sortType, acara)}
          </div>
        </div>
        <div class="acara-actions" style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; opacity: 0; transition: opacity 0.2s ease;">
          <button class="action-btn select-btn" title="Pilih acara" style="background: #6c757d; color: white; border: none; padding: 6px 9px; border-radius: 4px; cursor: pointer; font-size: 12px;">
            <iconify-icon icon="mdi:check-circle-outline" width="16" height="16"></iconify-icon>
          </button>
          <button class="action-btn detail-btn" title="Detail" style="background: #007bff; color: white; border: none; padding: 6px 9px; border-radius: 4px; cursor: pointer; font-size: 12px;">
             <iconify-icon icon="mdi:eye-outline" width="16" height="16"></iconify-icon>
          </button>
        </div>
       `;

      // Show actions on hover
      elemenAcara.addEventListener('mouseenter', () => {
        const actions = elemenAcara.querySelector('.acara-actions');
        if (actions) actions.style.opacity = '1';
      });
      
      elemenAcara.addEventListener('mouseleave', () => {
        const actions = elemenAcara.querySelector('.acara-actions');
        if (actions && !elemenAcara.classList.contains('selected')) actions.style.opacity = '0';
      });
      
      container.appendChild(elemenAcara);
    });
  }

  function tampilkanDaftarAcara() {
    const sortFilter = document.getElementById('sortFilter');
    const currentSort = sortFilter ? sortFilter.value : 'prioritas';
    tampilkanDaftarAcaraTerurut(currentSort);
  }

  function tampilkanKalender() {
    if (!elemen.tampilanTanggal) {
      console.error('Element tampilanTanggal tidak ditemukan');
      return;
    }

    elemen.tampilanTanggal.innerHTML = "";

    const tahun = data.tanggalSekarang.getFullYear();
    const bulan = data.tanggalSekarang.getMonth();
    const hariPertama = new Date(tahun, bulan, 1);
    const hariTerakhir = new Date(tahun, bulan + 1, 0);
    const jumlahHari = hariTerakhir.getDate();
    const hariAwal = hariPertama.getDay();
    const hariTerakhirBulanLalu = new Date(tahun, bulan, 0).getDate();

    if (elemen.tampilanBulanTahun) {
      elemen.tampilanBulanTahun.textContent = `${data.namaBulan[bulan]} ${tahun}`;
    }

    let hitungHari = 1;
    let hitungHariBulanDepan = 1;

    for (let i = 0; i < 42; i++) {
      const kotakHari = document.createElement("div");
      kotakHari.className = "tgl";

      if (i < hariAwal) {
        const hariBulanLalu = hariTerakhirBulanLalu - (hariAwal - i - 1);
        kotakHari.textContent = hariBulanLalu;
        kotakHari.classList.add("tglSblm");
        
        const bulanSebelum = bulan === 0 ? 11 : bulan - 1;
        const tahunSebelum = bulan === 0 ? tahun - 1 : tahun;
        kotakHari.dataset.tanggal = `${tahunSebelum}-${String(bulanSebelum + 1).padStart(2, "0")}-${String(hariBulanLalu).padStart(2, "0")}`;
      } else if (hitungHari <= jumlahHari) {
        kotakHari.textContent = hitungHari;
        kotakHari.dataset.tanggal = `${tahun}-${String(bulan + 1).padStart(2, "0")}-${String(hitungHari).padStart(2, "0")}`;
        
        const today = new Date();
        if (today.getDate() === hitungHari && today.getMonth() === bulan && today.getFullYear() === tahun) {
          kotakHari.classList.add("tglSkrg");
        }
        
        tampilkanAcaraPerHari(kotakHari, kotakHari.dataset.tanggal);
        hitungHari++;
      } else {
        kotakHari.textContent = hitungHariBulanDepan;
        kotakHari.classList.add("tglStlh");
        
        const bulanDepan = bulan === 11 ? 0 : bulan + 1;
        const tahunDepan = bulan === 11 ? tahun + 1 : tahun;
        kotakHari.dataset.tanggal = `${tahunDepan}-${String(bulanDepan + 1).padStart(2, "0")}-${String(hitungHariBulanDepan).padStart(2, "0")}`;
        hitungHariBulanDepan++;
      }

      elemen.tampilanTanggal.appendChild(kotakHari);
    }
  }

  function tampilkanAcaraPerHari(kotakHari, tanggal) {
    const acaraHariIni = data.daftarAcara.filter(
      (acara) => acara.tanggal === tanggal
    );

    acaraHariIni.forEach((acara) => {
      const elemenAcara = document.createElement("div");
      elemenAcara.className = "acr";
      elemenAcara.style.backgroundColor = data.prioritas[acara.prioritas]?.warna || data.prioritas.rendah.warna;
      elemenAcara.style.cursor = "pointer";
      elemenAcara.style.margin = "2px 0";
      elemenAcara.style.padding = "2px 4px";
      elemenAcara.style.borderRadius = "3px";
      elemenAcara.style.fontSize = "10px";
      elemenAcara.style.color = "white";
      elemenAcara.style.fontWeight = "500";
      elemenAcara.style.textShadow = "0 1px 1px rgba(0,0,0,0.3)";
      elemenAcara.style.transition = "all 0.2s ease";
      elemenAcara.style.overflow = "hidden";
      elemenAcara.style.textOverflow = "ellipsis";
      elemenAcara.style.whiteSpace = "nowrap";
      elemenAcara.style.maxWidth = "100%";
      
      elemenAcara.dataset.idAcara = acara.id;
      
      // Potong judul jika terlalu panjang
      const maxKarakter = 12;
      const judulPendek = acara.judul.length > maxKarakter ? 
                         acara.judul.substring(0, maxKarakter) + '...' : 
                         acara.judul;
      
      elemenAcara.textContent = judulPendek;
      elemenAcara.title = `${acara.judul} (${data.prioritas[acara.prioritas]?.label || 'Rendah'}) - ${acara.waktu}`;
      
      // Event listeners
      elemenAcara.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.3)';
        this.style.zIndex = '10';
      });
      
      elemenAcara.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
        this.style.boxShadow = 'none';
        this.style.zIndex = '1';
      });
      
      elemenAcara.addEventListener('click', function(e) {
        e.stopPropagation();
        const idAcara = this.dataset.idAcara;
        const acara = data.daftarAcara.find(a => a.id == idAcara);
        
        if (acara) {
          bukaSideModalDetail(acara);
        }
      });
      
      elemenAcara.addEventListener('dblclick', function(e) {
        e.stopPropagation();
        const idAcara = this.dataset.idAcara;
        const acara = data.daftarAcara.find(a => a.id == idAcara);
        
        if (acara) {
          bukaPopup(acara.tanggal, acara);
        }
      });
      
      kotakHari.appendChild(elemenAcara);
    });
  }

  // =============== FUNGSI DATABASE ===============

  async function ambilAcaraDariDatabase() {
    try {
      const response = await fetch("get_events.php");
      
      if (!response.ok) {
        throw new Error("Gagal mengambil data acara");
      }

      const acara = await response.json();

      if (acara.error) {
        console.error("Error dari server:", acara.error);
        alert("Gagal mengambil data acara: " + acara.error);
        data.daftarAcara = [];
      } else {
        data.daftarAcara = acara.map((item) => ({
          id: item.id_acara,
          judul: item.judul_acara,
          deskripsi: item.desc_acara,
          tanggal: formatTanggal(item.waktu_acara),
          waktu: formatWaktu(item.waktu_acara),
          tanggalMulai: formatTanggal(item.waktu_acara),
          tanggalAkhir: formatTanggal(item.waktu_acara),
          waktuMulai: formatWaktu(item.waktu_acara),
          waktuAkhir: formatWaktu(item.waktu_acara),
          prioritas: item.prioritas || 'rendah',
          warna: data.prioritas[item.prioritas || 'rendah'].warna
        }));

        updateEventBadge(data.daftarAcara.length);
      }

      tampilkanKalender();
      
      // Tampilkan dengan sorting prioritas sebagai default
      const sortFilter = document.getElementById('sortFilter');
      const currentSort = sortFilter ? sortFilter.value : 'prioritas';
      tampilkanDaftarAcaraTerurut(currentSort);
      
    } catch (error) {
      console.error('Error:', error);
      alert("Terjadi masalah saat mengambil data acara. Cek konsol untuk detail.");
      data.daftarAcara = [];
      tampilkanKalender();
      tampilkanDaftarAcara();
    }
  }

  async function simpanAcara() {
    if (!elemen.inputJudul.value.trim()) {
      alert("Judul acara harus diisi!");
      return;
    }

    const tombolKustom = document.getElementById('kustom');
    if (tombolKustom && tombolKustom.classList.contains('aktif')) {
      if (!elemen.inputTanggalCustom?.value) {
        alert("Tanggal harus dipilih untuk tenggat kustom!");
        return;
      }
      data.tanggalDipilih = elemen.inputTanggalCustom.value;
    }

    const prioritas = getPrioritasDipilih();
    const waktu = getWaktuDipilih();
    const waktuAcara = `${data.tanggalDipilih} ${waktu}`;

    try {
      const response = await fetch("add_event.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          judul_acara: elemen.inputJudul.value.trim(),
          desc_acara: elemen.inputDeskripsi.value.trim(),
          waktu_acara: waktuAcara,
          prioritas: prioritas
        }),
      });

      if (!response.ok) {
        throw new Error("Gagal menyimpan acara");
      }

      const result = await response.json();
      
      if (result.success) {
        alert("Acara berhasil ditambahkan!");
        tutupPopup();
        ambilAcaraDariDatabase();
      } else {
        alert("Gagal menambahkan acara: " + result.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert("Maaf, ada masalah saat menyimpan acara. Silakan coba lagi.");
    }
  }

  async function updateAcara(id, judul, deskripsi, prioritas) {
    const waktu = getWaktuDipilih();
    const waktuAcara = `${data.tanggalDipilih} ${waktu}`;
    
    try {
      const response = await fetch("edit_event.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: id,
          judul_acara: judul,
          desc_acara: deskripsi,
          waktu_acara: waktuAcara,
          prioritas: prioritas
        }),
      });

      const result = await response.json();
      if (result.status === "sukses") {
        alert("Acara berhasil diperbarui!");
        return true;
      } else {
        alert("Gagal memperbarui acara: " + (result.pesan || "Unknown error"));
        return false;
      }
    } catch (error) {
      console.error('Error:', error);
      alert("Maaf, ada masalah saat mengupdate acara.");
      return false;
    }
  }

  async function hapusAcara(idAcara) {
    if (confirm("Apakah Anda yakin ingin menghapus acara ini?")) {
      try {
        const response = await fetch("delete_event.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id: idAcara }),
        });

        if (!response.ok) {
          throw new Error("Gagal menghapus acara");
        }

        ambilAcaraDariDatabase();
        alert("Acara berhasil dihapus!");
        tutupSideModalDetail();
        sembunyikanActivityActions();
        data.acaraTerpilih = null;
      } catch (error) {
        console.error('Error:', error);
        alert("Maaf, ada masalah saat menghapus acara. Silakan coba lagi.");
      }
    }
  }

  // =============== FUNGSI MODAL & SIDE MODAL ===============

  function bukaPopup(tanggal, acara = null) {
    if (!elemen.popup) {
      console.error('Element popup tidak ditemukan');
      return;
    }

    data.tanggalDipilih = tanggal;
    elemen.popup.style.display = "flex";
    elemen.popup.classList.add("modal-active");

    let updateText = elemen.popup.querySelector(".update-text");
    if (!updateText && elemen.modalTitle) {
      updateText = document.createElement("h1");
      updateText.className = "update-text";
      updateText.style.display = "none";
      elemen.modalTitle.parentNode.insertBefore(updateText, elemen.modalTitle.nextSibling);
    }

    if (acara) {
      // Mode Edit
      console.log('Mode Edit - Data acara:', acara);
      data.acaraTerpilih = acara;
      
      if (elemen.modalTitle) elemen.modalTitle.style.display = "none";
      if (updateText) {
        updateText.textContent = "Update Acara";
        updateText.style.display = "block";
      }

      // Isi form dengan data acara
      if (elemen.inputJudul) elemen.inputJudul.value = acara.judul || "";
      if (elemen.inputDeskripsi) elemen.inputDeskripsi.value = acara.deskripsi || "";
      if (elemen.tombolSimpan) elemen.tombolSimpan.dataset.editId = acara.id;
      
      // Set tanggal dan waktu untuk mode edit
      data.tanggalDipilih = acara.tanggal;
      setTenggatUntukEdit(acara.tanggal, acara.waktu);
      setPrioritasDipilih(acara.prioritas || 'rendah');
      
    } else {
      // Mode Tambah Baru
      data.acaraTerpilih = null;
      if (elemen.modalTitle) elemen.modalTitle.style.display = "block";
      if (updateText) updateText.style.display = "none";
      bersihkanForm();
      setPrioritasDefault();
      if (elemen.tombolSimpan) delete elemen.tombolSimpan.dataset.editId;
    }

    if (elemen.inputJudul) elemen.inputJudul.focus();
  }

  function setTenggatUntukEdit(tanggal, waktu) {
    const tombolTenggat = {
      skrg: document.getElementById('skrg'),
      besok: document.getElementById('besok'), 
      kustom: document.getElementById('kustom')
    };
    const customInputContainer = document.querySelector('.custom-input-container');

    // Cek apakah tanggal acara sama dengan hari ini atau besok
    const hariIni = formatTanggalToISO(new Date());
    const besokDate = new Date();
    besokDate.setDate(besokDate.getDate() + 1);
    const besok = formatTanggalToISO(besokDate);

    // Reset semua tombol
    Object.values(tombolTenggat).forEach(btn => {
      if (btn) btn.classList.remove('aktif');
    });

    if (tanggal === hariIni) {
      if (tombolTenggat.skrg) tombolTenggat.skrg.classList.add('aktif');
      if (customInputContainer) customInputContainer.style.display = 'none';
    } else if (tanggal === besok) {
      if (tombolTenggat.besok) tombolTenggat.besok.classList.add('aktif');
      if (customInputContainer) customInputContainer.style.display = 'none';
    } else {
      if (tombolTenggat.kustom) tombolTenggat.kustom.classList.add('aktif');
      if (customInputContainer) {
        customInputContainer.style.display = 'flex';
      }
      if (elemen.inputTanggalCustom) elemen.inputTanggalCustom.value = tanggal;
    }

    // Set waktu
    if (elemen.inputWaktuCustom && waktu) {
      const waktuFormatted = waktu.length === 5 ? waktu : waktu.substring(0, 5);
      elemen.inputWaktuCustom.value = waktuFormatted;
    }
  }

  function tutupPopup() {
    if (elemen.popup) {
      elemen.popup.style.display = "none";
      elemen.popup.classList.remove("modal-active");
    }
    bersihkanForm();
    data.acaraTerpilih = null;
  }

  function bukaSideModalDetail(acara) {
    if (!elemen.sideModal) return;

    console.log('Membuka side modal dengan data:', acara);
    data.acaraTerpilih = acara;
    
    if (elemen.sideDetailJudul) elemen.sideDetailJudul.textContent = acara.judul;
    if (elemen.sideDetailDeskripsi) elemen.sideDetailDeskripsi.textContent = acara.deskripsi || "Tidak ada deskripsi";
    if (elemen.sideDetailTanggalMulai) elemen.sideDetailTanggalMulai.textContent = `${formatTanggalTampilan(acara.tanggalMulai || acara.tanggal)} pukul ${acara.waktuMulai || acara.waktu}`;
    if (elemen.sideDetailTanggalBerakhir) elemen.sideDetailTanggalBerakhir.textContent = `${formatTanggalTampilan(acara.tanggalAkhir || acara.tanggal)} pukul ${acara.waktuAkhir || acara.waktu}`;
    if (elemen.sideDetailDurasi) elemen.sideDetailDurasi.textContent = hitungDurasi(acara.tanggalMulai || acara.tanggal, acara.tanggalAkhir || acara.tanggal) || "1 hari";
    
    const priorityInfo = data.prioritas[acara.prioritas] || data.prioritas.rendah;
    if (elemen.sideDetailPrioritas) {
      elemen.sideDetailPrioritas.textContent = priorityInfo.label;
      elemen.sideDetailPrioritas.style.backgroundColor = priorityInfo.warna;
    }

    elemen.sideModal.classList.add("open");
    document.body.classList.add("side-modal-open-overlay");
  }

  function tutupSideModalDetail() {
    if (elemen.sideModal) {
        elemen.sideModal.classList.remove("open");
    }
    document.body.classList.remove("side-modal-open-overlay");
  }

  function tampilkanActivityActions(acara) {
    if (!elemen.activityActions) return;
    
    data.acaraTerpilih = acara;
    elemen.activityActions.style.display = 'flex';
  }

  function sembunyikanActivityActions() {
    if (elemen.activityActions) elemen.activityActions.style.display = 'none';
    // Reset selected state
    document.querySelectorAll('.acara.selected').forEach(el => {
      el.classList.remove('selected');
      el.style.background = '#f9f9f9';
      const actions = el.querySelector('.acara-actions');
      if(actions) actions.style.opacity = '0';
    });
    data.acaraTerpilih = null;
  }

  function bersihkanForm() {
    if (elemen.inputJudul) elemen.inputJudul.value = "";
    if (elemen.inputDeskripsi) elemen.inputDeskripsi.value = "";
    
    if (elemen.inputTanggalCustom) {
      const today = new Date();
      const todayFormatted = formatTanggalToISO(today);
      elemen.inputTanggalCustom.value = todayFormatted;
    }
    
    if (elemen.inputWaktuCustom) {
      elemen.inputWaktuCustom.value = "23:00";
    }
    
    const customInputContainer = document.querySelector('.custom-input-container');
    if (customInputContainer) {
      customInputContainer.style.display = 'none';
    }
    
    const tombolSkrg = document.getElementById('skrg');
    const tombolBesok = document.getElementById('besok');
    const tombolKustom = document.getElementById('kustom');
    
    if (tombolSkrg) tombolSkrg.classList.add('aktif');
    if (tombolBesok) tombolBesok.classList.remove('aktif');
    if (tombolKustom) tombolKustom.classList.remove('aktif');
    
    const today = new Date();
    data.tanggalDipilih = formatTanggalToISO(today);
  }

  // =============== FUNGSI NAVIGASI ===============

  function pindahBulan(arah) {
    data.tanggalSekarang.setMonth(
      data.tanggalSekarang.getMonth() + (arah === "prev" ? -1 : 1)
    );
    tampilkanKalender();
  }

  // =============== EVENT LISTENERS ===============

  function aturEventListeners() {
    // Navigation
    if (elemen.tombolNext) {
      elemen.tombolNext.addEventListener("click", () => pindahBulan("next"));
    }
    if (elemen.tombolPrev) {
      elemen.tombolPrev.addEventListener("click", () => pindahBulan("prev"));
    }

    // Add button
    if (elemen.btnTambahAcara) {
      elemen.btnTambahAcara.addEventListener('click', () => {
        const today = new Date();
        bukaPopup(formatTanggalToISO(today));
        sembunyikanActivityActions();
      });
    }

    // Activity buttons
    if (elemen.btnEdit) {
      elemen.btnEdit.addEventListener('click', () => {
        if (data.acaraTerpilih) {
          console.log('Edit button clicked, data:', data.acaraTerpilih);
          bukaPopup(data.acaraTerpilih.tanggal, data.acaraTerpilih);
          sembunyikanActivityActions();
        } else {
          alert("Tidak ada acara yang dipilih untuk diedit.");
        }
      });
    }

    if (elemen.btnHapus) {
      elemen.btnHapus.addEventListener('click', () => {
        if (data.acaraTerpilih) {
          hapusAcara(data.acaraTerpilih.id);
          sembunyikanActivityActions();
        }
      });
    }

    // Calendar clicks
    if (elemen.tampilanTanggal) {
      elemen.tampilanTanggal.addEventListener("click", (e) => {
        const kotakHari = e.target.closest(".tgl");
        const acaraElement = e.target.closest(".acr");
        
        // Jika klik pada acara, jangan buka popup tambah acara baru
        if (acaraElement) {
          return; // Event sudah dihandle oleh acara element
        }
        
        // Jika klik pada kotak hari (bukan acara), buka popup tambah acara baru
        if (kotakHari) {
          const tanggal = kotakHari.dataset.tanggal;
          if (tanggal) {
            bukaPopup(tanggal);
            sembunyikanActivityActions();
          }
        }
      });
    }

    // Event list clicks
    const container = elemen.containerAcara || elemen.daftarAcara;
    if (container) {
      container.addEventListener("click", (e) => {
        const selectBtn = e.target.closest('.select-btn');
        const detailBtn = e.target.closest('.detail-btn');
        const hapusBtn = e.target.closest('.hapus');
        const elemenAcara = e.target.closest(".acara");
        
        if (!elemenAcara) return;

        const idAcara = elemenAcara.dataset.idAcara;
        const acara = data.daftarAcara.find((a) => a.id == idAcara);

        if (hapusBtn) {
          e.stopPropagation();
          if (acara) hapusAcara(acara.id);
        } else if (selectBtn) {
          e.stopPropagation();
          
          // Reset semua selection
          document.querySelectorAll('.acara').forEach(item => {
            item.classList.remove('selected');
            item.style.background = '#f9f9f9';
            const actions = item.querySelector('.acara-actions');
            if(actions) actions.style.opacity = '0';
          });
          
          // Set selection untuk item yang dipilih
          elemenAcara.classList.add('selected');
          elemenAcara.style.background = '#e3f2fd';
          const actions = elemenAcara.querySelector('.acara-actions');
          if(actions) actions.style.opacity = '1';
          
          if (acara) tampilkanActivityActions(acara);
          
        } else if (detailBtn) {
          e.stopPropagation();
          if (acara) bukaSideModalDetail(acara);
        } else if (!e.target.classList.contains("hapus")) {
          if (acara) bukaSideModalDetail(acara);
        }
      });
    }

    // Modal buttons
    if (elemen.tombolTutup) {
      elemen.tombolTutup.addEventListener("click", tutupPopup);
    }

    if (elemen.tombolSimpan) {
      elemen.tombolSimpan.addEventListener("click", async (e) => {
        if (!elemen.inputJudul || !elemen.inputJudul.value.trim()) {
          alert("Judul acara harus diisi!");
          return;
        }

        const prioritas = getPrioritasDipilih();

        if (elemen.tombolSimpan.dataset.editId) {
          console.log('Updating acara with ID:', elemen.tombolSimpan.dataset.editId);
          const berhasil = await updateAcara(
            elemen.tombolSimpan.dataset.editId,
            elemen.inputJudul.value.trim(),
            elemen.inputDeskripsi ? elemen.inputDeskripsi.value.trim() : "",
            prioritas
          );

          if (berhasil) {
            tutupPopup();
            ambilAcaraDariDatabase();
            sembunyikanActivityActions();
          }
        } else {
          await simpanAcara();
        }
      });
    }

    // Side modal buttons
    if (elemen.btnTutupSideModal) {
        elemen.btnTutupSideModal.addEventListener("click", () => {
          tutupSideModalDetail();
          data.acaraTerpilih = null;
        });
    }

    if (elemen.btnEditSideAcara) {
        elemen.btnEditSideAcara.addEventListener("click", () => {
            console.log('Side modal edit clicked, acaraTerpilih:', data.acaraTerpilih);
            
            if (data.acaraTerpilih) {
                const acaraUntukEdit = { ...data.acaraTerpilih };
                console.log('Data acara yang akan diedit:', acaraUntukEdit);
                
                if (elemen.sideModal) {
                    elemen.sideModal.classList.remove("open");
                }
                document.body.classList.remove("side-modal-open-overlay");
                
                setTimeout(() => {
                  if (acaraUntukEdit && acaraUntukEdit.tanggal) {
                    bukaPopup(acaraUntukEdit.tanggal, acaraUntukEdit);
                  } else {
                    console.error('Data acara tidak valid:', acaraUntukEdit);
                    alert("Data acara tidak valid. Silakan coba lagi.");
                  }
                }, 100);
            } else {
                alert("Tidak ada acara yang dipilih untuk diedit.");
            }
        });
    }

    if (elemen.btnHapusSideAcara) {
        elemen.btnHapusSideAcara.addEventListener("click", () => {
            if (data.acaraTerpilih) {
                const idAcara = data.acaraTerpilih.id;
                data.acaraTerpilih = null;
                hapusAcara(idAcara);
            } else {
                alert("Tidak ada acara yang dipilih untuk dihapus.");
            }
        });
    }

    // Click outside to close
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.acara') && !e.target.closest('.activity-actions') && !e.target.closest('.side-modal') && !e.target.closest('.modal')) {
        sembunyikanActivityActions();
      }
    });

    // Close modals on outside click
    if (elemen.popup) {
      elemen.popup.addEventListener("click", (e) => {
        if (e.target === elemen.popup) tutupPopup();
      });
    }

    // Close side modal on outside click
    document.addEventListener('click', (e) => {
      if (elemen.sideModal && elemen.sideModal.classList.contains('open')) {
        if (!e.target.closest('.side-modal') && !e.target.closest('.detail-btn') && !e.target.closest('.acara') && !e.target.closest('.acr')) {
          tutupSideModalDetail();
          data.acaraTerpilih = null;
        }
      }
    });
  }

  // =============== INISIALISASI ===============

  function mulaiKalender() {
    inisialisasiPrioritas();
    inisialisasiTenggat(); 
    inisialisasiFilter();
    aturEventListeners();
    ambilAcaraDariDatabase();
  }

  mulaiKalender();
});