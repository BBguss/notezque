/**
 * ==============================================
 * SISTEM KALENDER AKTIVITAS - DENGAN FORMAT MM/DD/YY
 * ==============================================
 */

// Data yang digunakan dalam kalender
var tanggalHariIni = new Date();
var bulanSekarang = tanggalHariIni.getMonth();
var tahunSekarang = tanggalHariIni.getFullYear();
var daftarAktivitas = window.phpEvents || [];
var idAktivitasYangDiedit = null;

// Nama-nama bulan dalam bahasa Indonesia
var namaBulan = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

// Nama-nama hari dalam bahasa Indonesia
var namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// Fungsi utama untuk menjalankan kalender
function jalankanSistemKalender() {
    ambilElemenHTML();
    buatTombolKalender();
    tambahkanEventListener();
    tampilkanKalender();
    tampilkanDaftarAktivitas();
}

// Fungsi untuk mengambil elemen-elemen HTML yang diperlukan
function ambilElemenHTML() {
    // Elemen kalender - sesuaikan dengan CSS
    window.tempatTanggal = document.querySelector('.tglBln');
    window.judulBulanTahun = document.querySelector('.bln-thn');
    window.containerAktivitas = document.querySelector('.acara-container');
    
    // Elemen-elemen modal
    window.modalUtama = document.getElementById('modal');
    window.modalDetail = document.getElementById('sideModalDetail');
    
    // Tombol-tombol
    window.tombolTambah = document.getElementById('btnTambahAcara');
    window.badgeJumlahEvent = document.getElementById('eventBadge');
    window.tombolBatal = document.querySelector('.btl');
    window.tombolSimpan = document.querySelector('.save');
    window.sortFilter = document.getElementById('sortFilter');
    
    // Form dan input
    window.formEvent = document.getElementById('eventForm');
    window.inputJudul = document.getElementById('title');
    window.inputDeskripsi = document.getElementById('desk');
    window.inputTanggal = document.getElementById('tanggal');
    window.inputWaktu = document.getElementById('waktu');
    window.inputIdAcara = document.getElementById('id_acara');
}

// Fungsi untuk membuat tombol navigasi dan interaksi kalender
function buatTombolKalender() {
    // Tombol navigasi bulan
    var prevBtn = document.querySelector('.prev');
    var nextBtn = document.querySelector('.next');
    
    if (prevBtn) prevBtn.onclick = bulanSebelumnya;
    if (nextBtn) nextBtn.onclick = bulanSelanjutnya;
    
    // Tombol tambah acara
    if (window.tombolTambah) {
        window.tombolTambah.onclick = bukaModalTambahAcara;
    }
}

// Fungsi untuk menambahkan event listener
function tambahkanEventListener() {
    // Klik pada tanggal di kalender
    if (window.tempatTanggal) {
        window.tempatTanggal.addEventListener('click', function(e) {
            var targetTanggal = e.target.closest('.tgl');
            if (targetTanggal && !targetTanggal.classList.contains('tglSblm') && !targetTanggal.classList.contains('tglStlh')) {
                var tanggal = targetTanggal.dataset.tanggal;
                if (tanggal) {
                    bukaAktivitasTanggal(tanggal);
                }
            }
        });
    }

    // Tombol ESC untuk tutup modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (window.modalUtama && window.modalUtama.classList.contains('modal-active')) {
                tutupModal();
            }
            if (window.modalDetail && window.modalDetail.style.display === 'block') {
                closeSideModal();
            }
        }
    });
    
    // Tombol batal di modal
    if (window.tombolBatal) {
        window.tombolBatal.addEventListener('click', function() {
            tutupModal();
        });
    }
    
    // Tombol simpan di modal
    if (window.tombolSimpan) {
        window.tombolSimpan.addEventListener('click', function() {
            simpanAktivitas();
        });
    }
    
    // Form submit
    if (window.formEvent) {
        window.formEvent.addEventListener('submit', function(e) {
            e.preventDefault();
            simpanAktivitas();
        });
    }
    
    // Filter dan sort
    if (window.sortFilter) {
        window.sortFilter.addEventListener('change', function() {
            tampilkanDaftarAktivitas();
        });
    }
    
    // Tombol reset filter
    var clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (window.sortFilter) window.sortFilter.value = 'tanggal_asc';
            tampilkanDaftarAktivitas();
        });
    }
    
    // Tombol close pada modal detail
    var closeDetailBtn = document.querySelector('.side-modal-close');
    if (closeDetailBtn) {
        closeDetailBtn.addEventListener('click', closeSideModal);
    }
    
    // Tombol edit dan hapus di modal detail
    var editDetailBtn = document.getElementById('editSideAcaraBtn');
    var hapusDetailBtn = document.getElementById('hapusSideAcaraBtn');
    
    if (editDetailBtn) editDetailBtn.onclick = editFromSide;
    if (hapusDetailBtn) hapusDetailBtn.onclick = deleteFromSide;
    
    // Overlay modal
    window.modalUtama?.addEventListener('click', function(e) {
        if (e.target === this) {
            tutupModal();
        }
    });
}

// Fungsi untuk membuat tampilan kalender
function tampilkanKalender() {
    if (!window.tempatTanggal || !window.judulBulanTahun) return;
    
    // Kosongkan tempat tanggal
    window.tempatTanggal.innerHTML = '';
    
    var tahun = tahunSekarang;
    var bulan = bulanSekarang;
    
    // Buat tanggal pertama bulan ini
    var tanggalPertama = new Date(tahun, bulan, 1);
    var tanggalTerakhir = new Date(tahun, bulan + 1, 0);
    var jumlahHari = tanggalTerakhir.getDate();
    var hariPertama = tanggalPertama.getDay();
    
    // Set judul bulan dan tahun dalam format MM/DD/YY style
    var tahunPendek = tahun.toString().substr(-2);
    window.judulBulanTahun.textContent = namaBulan[bulan] + ' ' + tahunPendek;
    
    // Tambahkan tanggal dari bulan sebelumnya
    var hariTerakhirBulanSebelum = new Date(tahun, bulan, 0).getDate();
    for (var i = hariPertama; i > 0; i--) {
        var kotakHari = document.createElement('div');
        kotakHari.className = 'tgl tglSblm';
        kotakHari.textContent = hariTerakhirBulanSebelum - i + 1;
        window.tempatTanggal.appendChild(kotakHari);
    }
    
    // Tambahkan tanggal-tanggal bulan ini
    var hariIni = new Date();
    for (var i = 1; i <= jumlahHari; i++) {
        var kotakHari = document.createElement('div');
        kotakHari.className = 'tgl';
        kotakHari.textContent = i;
        
        // Set atribut data-tanggal dengan format MM/DD/YY
        var tanggalFormat = formatTanggalMMDDYY(tahun, bulan + 1, i);
        kotakHari.dataset.tanggal = tanggalFormat;
        
        // Tandai jika hari ini
        if (tahun === hariIni.getFullYear() && bulan === hariIni.getMonth() && i === hariIni.getDate()) {
            kotakHari.classList.add('tglSkrg');
        }
        
        // Tambahkan indikator aktivitas
        tambahIndikatorAktivitas(kotakHari, tanggalFormat);
        
        window.tempatTanggal.appendChild(kotakHari);
    }
    
    // Tambahkan tanggal awal bulan depan
    var totalKotak = window.tempatTanggal.children.length;
    var sisaKotak = 42 - totalKotak; // 6 baris x 7 kolom = 42 kotak
    
    for (var i = 1; i <= sisaKotak; i++) {
        var kotakHari = document.createElement('div');
        kotakHari.className = 'tgl tglStlh';
        kotakHari.textContent = i;
        window.tempatTanggal.appendChild(kotakHari);
    }
}

// Fungsi untuk format tanggal ke MM/DD/YY
function formatTanggalMMDDYY(tahun, bulan, hari) {
    // Format MM/DD/YY
    hari = hari < 10 ? '0' + hari : hari;
    bulan = bulan < 10 ? '0' + bulan : bulan;
    var tahunPendek = tahun.toString().substr(-2);
    
    return bulan + '/' + hari + '/' + tahunPendek;
}

// PERBAIKAN 1: Fungsi konversi format yang benar
function konversiKeFormatDatabase(tanggalMMDDYY) {
    if (!tanggalMMDDYY) return '';
    
    var parts = tanggalMMDDYY.split('/');
    if (parts.length !== 3) return '';
    
    var bulan = parts[0];
    var hari = parts[1]; 
    var tahunPendek = parts[2];
    
    // Konversi tahun 2 digit ke 4 digit (asumsi 20xx)
    var tahunPenuh = '20' + tahunPendek;
    
    // Return format YYYY-MM-DD untuk database
    return tahunPenuh + '-' + bulan + '-' + hari;
}

// PERBAIKAN 2: Fungsi konversi dari database yang benar
function konversiDariDatabase(tanggalDatabase) {
    if (!tanggalDatabase) return '';
    
    var parts = tanggalDatabase.split(' ')[0].split('-'); // Ambil bagian tanggal saja
    if (parts.length !== 3) return '';
    
    var tahun = parts[0];
    var bulan = parts[1];
    var hari = parts[2];
    
    var tahunPendek = tahun.substr(-2);
    
    // Return format MM/DD/YY untuk tampilan
    return bulan + '/' + hari + '/' + tahunPendek;
}

// Fungsi untuk menambah indikator aktivitas pada kotak tanggal
function tambahIndikatorAktivitas(kotakHari, tanggalMMDDYY) {
    // Filter aktivitas untuk tanggal ini
    var aktivitasHariIni = [];
    
    for (var i = 0; i < daftarAktivitas.length; i++) {
        var aktivitas = daftarAktivitas[i];
        // Konversi waktu_acara database ke format MM/DD/YY untuk perbandingan
        var tanggalAktivitas = konversiDariDatabase(aktivitas.waktu_acara);
        if (tanggalAktivitas === tanggalMMDDYY) {
            aktivitasHariIni.push(aktivitas);
        }
    }
    
    // Tampilkan maksimal 2 indikator
    var maksIndikator = 2;
    
    for (var i = 0; i < Math.min(aktivitasHariIni.length, maksIndikator); i++) {
        var indikator = document.createElement('div');
        indikator.className = 'acr';
        indikator.style.backgroundColor = '#0ea5e9';
        
        // Potong judul jika terlalu panjang
        var judul = aktivitasHariIni[i].judul_acara;
        var judulPendek = judul.length > 10 ? judul.substring(0, 10) + '...' : judul;
        
        indikator.textContent = judulPendek;
        indikator.title = judul;
        kotakHari.appendChild(indikator);
    }
    
    // Jika ada lebih banyak aktivitas, tampilkan "+X lagi"
    if (aktivitasHariIni.length > maksIndikator) {
        var indikatorLebih = document.createElement('div');
        indikatorLebih.className = 'acr more-acr';
        indikatorLebih.textContent = '+' + (aktivitasHariIni.length - maksIndikator) + ' lagi';
        indikatorLebih.style.backgroundColor = '#6b7280';
        kotakHari.appendChild(indikatorLebih);
    }
}

// Fungsi untuk menampilkan daftar aktivitas
function tampilkanDaftarAktivitas() {
    if (!window.containerAktivitas) return;
    
    // Hitung total aktivitas bulan ini
    var totalAktivitasBulanIni = 0;
    var aktivitasBulanIni = [];
    
    console.log("Menampilkan aktivitas bulan:", namaBulan[bulanSekarang], tahunSekarang);
    
    // Loop semua aktivitas dan filter untuk bulan ini
    for (var i = 0; i < daftarAktivitas.length; i++) {
        var aktivitas = daftarAktivitas[i];
        if (!aktivitas.waktu_acara) continue;
        
        // Parse tanggal dari aktivitas (format YYYY-MM-DD HH:MM:SS dari database)
        var tanggalAktivitas = new Date(aktivitas.waktu_acara);
        
        // Cek apakah aktivitas di bulan dan tahun saat ini
        if (tanggalAktivitas.getMonth() === bulanSekarang && tanggalAktivitas.getFullYear() === tahunSekarang) {
            totalAktivitasBulanIni++;
            aktivitasBulanIni.push(aktivitas);
        }
    }
    
    // Update badge jumlah event
    if (window.badgeJumlahEvent) {
        window.badgeJumlahEvent.textContent = totalAktivitasBulanIni;
        window.badgeJumlahEvent.style.display = totalAktivitasBulanIni > 0 ? 'flex' : 'none';
    }
    
    // Reset dan tampilkan daftar aktivitas bulan ini
    window.containerAktivitas.innerHTML = '';
    
    if (aktivitasBulanIni.length === 0) {
        var pesanKosong = document.createElement('div');
        pesanKosong.className = 'no-events';
        var tahunPendek = tahunSekarang.toString().substr(-2);
        pesanKosong.innerHTML = `
            <iconify-icon icon="mdi:calendar-outline" width="64" height="64"></iconify-icon>
            <h4>Tidak ada acara</h4>
            <p>Belum ada acara yang dijadwalkan untuk bulan ${namaBulan[bulanSekarang]} ${tahunPendek}</p>
        `;
        window.containerAktivitas.appendChild(pesanKosong);
    } else {
        // Sort berdasarkan filter
        if (window.sortFilter) {
            var sortValue = window.sortFilter.value;
            
            if (sortValue === 'tanggal_desc') {
                aktivitasBulanIni.sort(function(a, b) {
                    return new Date(b.waktu_acara) - new Date(a.waktu_acara);
                });
            } else if (sortValue === 'judul') {
                aktivitasBulanIni.sort(function(a, b) {
                    return a.judul_acara.localeCompare(b.judul_acara);
                });
            } else { // default: tanggal_asc
                aktivitasBulanIni.sort(function(a, b) {
                    return new Date(a.waktu_acara) - new Date(b.waktu_acara);
                });
            }
        }
        
        for (var j = 0; j < aktivitasBulanIni.length; j++) {
            var item = aktivitasBulanIni[j];
            var tanggalObj = new Date(item.waktu_acara);
            
            // Format tanggal dan waktu untuk tampilan dalam MM/DD/YY
            var tanggalTampilan = formatTanggalTampilanMMDDYY(tanggalObj);
            var jamTampilan = tanggalObj.getHours().toString().padStart(2, '0') + ':' + tanggalObj.getMinutes().toString().padStart(2, '0');
            var waktuTampilan = jamTampilan === '00:00' ? 'Sepanjang hari' : jamTampilan;
            
            var itemEl = document.createElement('div');
            itemEl.className = 'acara';
            itemEl.innerHTML = `
                <div class="acara-actions">
                    <button class="action-btn detail-btn" data-id="${item.id_acara}" title="Lihat Detail">
                        <iconify-icon icon="mdi:eye" width="16" height="16"></iconify-icon>
                    </button>
                    <button class="action-btn edit-btn" data-id="${item.id_acara}" title="Edit">
                        <iconify-icon icon="mdi:pencil" width="16" height="16"></iconify-icon>
                    </button>
                    <button class="action-btn hapus-btn" data-id="${item.id_acara}" title="Hapus">
                        <iconify-icon icon="mdi:trash-can-outline" width="16" height="16"></iconify-icon>
                    </button>
                </div>
                <div class="event-date">
                    <iconify-icon icon="mdi:calendar" width="14" height="14"></iconify-icon>
                    ${tanggalTampilan} - ${waktuTampilan}
                </div>
                <h4>${item.judul_acara}</h4>
                <div class="event-desc">${item.desc_acara || 'Tidak ada deskripsi'}</div>
            `;
            
            window.containerAktivitas.appendChild(itemEl);
        }
        
        // Tambahkan event listener untuk tombol-tombol
        var tombolDetail = window.containerAktivitas.querySelectorAll('.detail-btn');
        var tombolEdit = window.containerAktivitas.querySelectorAll('.edit-btn');
        var tombolHapus = window.containerAktivitas.querySelectorAll('.hapus-btn');
        
        for (var k = 0; k < tombolDetail.length; k++) {
            tombolDetail[k].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                bukaDetailAktivitas(idAcara);
            });
        }
        
        for (var l = 0; l < tombolEdit.length; l++) {
            tombolEdit[l].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                editAktivitas(idAcara);
            });
        }
        
        for (var m = 0; m < tombolHapus.length; m++) {
            tombolHapus[m].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                konfirmasiHapusAktivitas(idAcara);
            });
        }
    }
}

// Fungsi untuk format tanggal tampilan dalam MM/DD/YY dengan nama bulan
function formatTanggalTampilanMMDDYY(tanggalObj) {
    var hari = tanggalObj.getDate();
    var bulan = tanggalObj.getMonth();
    var tahun = tanggalObj.getFullYear();
    var tahunPendek = tahun.toString().substr(-2);
    
    return (tanggalObj.getMonth() + 1).toString().padStart(2, '0') + '/' + 
           hari.toString().padStart(2, '0') + '/' + tahunPendek + 
           ' (' + namaBulan[bulan] + ')';
}

// Fungsi untuk navigasi ke bulan sebelumnya
function bulanSebelumnya() {
    bulanSekarang--;
    
    // Jika bulan menjadi -1 (Desember tahun sebelumnya)
    if (bulanSekarang < 0) {
        bulanSekarang = 11;
        tahunSekarang--;
    }
    
    tampilkanKalender();
    tampilkanDaftarAktivitas();
}

// Fungsi untuk navigasi ke bulan selanjutnya
function bulanSelanjutnya() {
    bulanSekarang++;
    
    // Jika bulan menjadi 12 (Januari tahun berikutnya)
    if (bulanSekarang > 11) {
        bulanSekarang = 0;
        tahunSekarang++;
    }
    
    tampilkanKalender();
    tampilkanDaftarAktivitas();
}

// PERBAIKAN: Fungsi untuk membuka modal tambah acara
function bukaModalTambahAcara(tanggalMMDDYY) {
    if (!window.modalUtama) {
        console.log('Modal tidak ditemukan');
        return;
    }
    
    // Reset form
    resetForm();
    
    // Set tanggal jika ada
    if (tanggalMMDDYY && window.inputTanggal) {
        if (typeof tanggalMMDDYY === 'string') {
            // Konversi MM/DD/YY ke format input (YYYY-MM-DD)
            var formatDatabase = konversiKeFormatDatabase(tanggalMMDDYY);
            if (formatDatabase) {
                window.inputTanggal.value = formatDatabase;
            }
        }
    }
    
    // Buka modal
    window.modalUtama.style.display = 'flex';
    window.modalUtama.classList.add('modal-active');
    document.body.style.overflow = 'hidden';
    
    // Focus ke judul
    setTimeout(function() {
        if (window.inputJudul) {
            window.inputJudul.focus();
        }
    }, 100);
    
    // Update judul modal
    var judulModal = window.modalUtama.querySelector('.modalTitle');
    var textUpdate = window.modalUtama.querySelector('.update-text');
    
    if (judulModal) {
        judulModal.textContent = 'Buat Acara Baru';
        judulModal.style.display = 'block';
    }
    
    if (textUpdate) {
        textUpdate.style.display = 'none';
    }
}

// Fungsi untuk menutup modal
function tutupModal() {
    if (!window.modalUtama) return;
    
    window.modalUtama.style.display = 'none';
    window.modalUtama.classList.remove('modal-active');
    document.body.style.overflow = 'auto';
    resetForm();
}

// PERBAIKAN 5: Fungsi reset form yang benar
function resetForm() {
    if (window.formEvent) {
        window.formEvent.reset();
    }
    
    idAktivitasYangDiedit = null;
    
    if (window.inputJudul) window.inputJudul.value = '';
    if (window.inputDeskripsi) window.inputDeskripsi.value = '';
    if (window.inputIdAcara) window.inputIdAcara.value = '';
    
    // Set tanggal hari ini dalam format YYYY-MM-DD untuk input
    if (window.inputTanggal) {
        var hariIni = new Date();
        var tahun = hariIni.getFullYear();
        var bulan = (hariIni.getMonth() + 1).toString().padStart(2, '0');
        var hari = hariIni.getDate().toString().padStart(2, '0');
        window.inputTanggal.value = tahun + '-' + bulan + '-' + hari;
    }
    
    // Kosongkan waktu
    if (window.inputWaktu) {
        window.inputWaktu.value = '';
    }
    
    // Reset reminder jika ada
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderOptions = document.getElementById('reminder_options');
    var reminderTemplate = document.getElementById('reminder_template');
    var customReminder = document.getElementById('custom_reminder');
    
    if (reminderEnabled) reminderEnabled.checked = false;
    if (reminderOptions) reminderOptions.classList.remove('active');
    if (reminderTemplate) reminderTemplate.value = '';
    if (customReminder) customReminder.classList.remove('active');
}

// PERBAIKAN 6: Fungsi buka modal yang benar
function bukaModalTambahAcara(tanggalMMDDYY) {
    if (!window.modalUtama) {
        console.log('Modal tidak ditemukan');
        return;
    }
    
    // Reset form
    resetForm();
    
    // Set tanggal jika ada
    if (tanggalMMDDYY && window.inputTanggal) {
        if (typeof tanggalMMDDYY === 'string') {
            // Konversi MM/DD/YY ke format input (YYYY-MM-DD)
            var formatDatabase = konversiKeFormatDatabase(tanggalMMDDYY);
            if (formatDatabase) {
                window.inputTanggal.value = formatDatabase;
            }
        }
    }
    
    // Buka modal
    window.modalUtama.style.display = 'flex';
    window.modalUtama.classList.add('modal-active');
    document.body.style.overflow = 'hidden';
    
    // Focus ke judul
    setTimeout(function() {
        if (window.inputJudul) {
            window.inputJudul.focus();
        }
    }, 100);
    
    // Update judul modal
    var judulModal = window.modalUtama.querySelector('.modalTitle');
    var textUpdate = window.modalUtama.querySelector('.update-text');
    
    if (judulModal) {
        judulModal.textContent = 'Buat Acara Baru';
        judulModal.style.display = 'block';
    }
    
    if (textUpdate) {
        textUpdate.style.display = 'none';
    }
}

// Fungsi untuk konversi DD-MM-YYYY ke MM-DD-YYYY untuk database
function konversiKeFormatDatabase(tanggalDDMMYYYY) {
    if (!tanggalDDMMYYYY) return '';
    
    var parts = tanggalDDMMYYYY.split('-');
    if (parts.length !== 3) return '';
    
    var hari = parts[0];
    var bulan = parts[1]; 
    var tahun = parts[2];
    
    // Return format MM-DD-YYYY untuk database
    return bulan + '-' + hari + '-' + tahun;
}

// Fungsi untuk konversi MM-DD-YYYY dari database ke DD-MM-YYYY untuk display
function konversiDariDatabase(tanggalDatabase) {
    if (!tanggalDatabase) return '';
    
    var parts = tanggalDatabase.split(' ')[0].split('-'); // Ambil bagian tanggal saja
    if (parts.length !== 3) return '';
    
    var tahun = parts[0];
    var bulan = parts[1];
    var hari = parts[2];
    
    var tahunPendek = tahun.substr(-2);
    
    // Return format MM/DD/YY untuk tampilan
    return bulan + '/' + hari + '/' + tahunPendek;
}

// Fungsi untuk menambah indikator aktivitas pada kotak tanggal
function tambahIndikatorAktivitas(kotakHari, tanggalMMDDYY) {
    // Filter aktivitas untuk tanggal ini
    var aktivitasHariIni = [];
    
    for (var i = 0; i < daftarAktivitas.length; i++) {
        var aktivitas = daftarAktivitas[i];
        // Konversi waktu_acara database ke format MM/DD/YY untuk perbandingan
        var tanggalAktivitas = konversiDariDatabase(aktivitas.waktu_acara);
        if (tanggalAktivitas === tanggalMMDDYY) {
            aktivitasHariIni.push(aktivitas);
        }
    }
    
    // Tampilkan maksimal 2 indikator
    var maksIndikator = 2;
    
    for (var i = 0; i < Math.min(aktivitasHariIni.length, maksIndikator); i++) {
        var indikator = document.createElement('div');
        indikator.className = 'acr';
        indikator.style.backgroundColor = '#0ea5e9';
        
        // Potong judul jika terlalu panjang
        var judul = aktivitasHariIni[i].judul_acara;
        var judulPendek = judul.length > 10 ? judul.substring(0, 10) + '...' : judul;
        
        indikator.textContent = judulPendek;
        indikator.title = judul;
        kotakHari.appendChild(indikator);
    }
    
    // Jika ada lebih banyak aktivitas, tampilkan "+X lagi"
    if (aktivitasHariIni.length > maksIndikator) {
        var indikatorLebih = document.createElement('div');
        indikatorLebih.className = 'acr more-acr';
        indikatorLebih.textContent = '+' + (aktivitasHariIni.length - maksIndikator) + ' lagi';
        indikatorLebih.style.backgroundColor = '#6b7280';
        kotakHari.appendChild(indikatorLebih);
    }
}

// Fungsi untuk menampilkan daftar aktivitas
function tampilkanDaftarAktivitas() {
    if (!window.containerAktivitas) return;
    
    // Hitung total aktivitas bulan ini
    var totalAktivitasBulanIni = 0;
    var aktivitasBulanIni = [];
    
    console.log("Menampilkan aktivitas bulan:", namaBulan[bulanSekarang], tahunSekarang);
    
    // Loop semua aktivitas dan filter untuk bulan ini
    for (var i = 0; i < daftarAktivitas.length; i++) {
        var aktivitas = daftarAktivitas[i];
        if (!aktivitas.waktu_acara) continue;
        
        // Parse tanggal dari aktivitas (format YYYY-MM-DD HH:MM:SS dari database)
        var tanggalAktivitas = new Date(aktivitas.waktu_acara);
        
        // Cek apakah aktivitas di bulan dan tahun saat ini
        if (tanggalAktivitas.getMonth() === bulanSekarang && tanggalAktivitas.getFullYear() === tahunSekarang) {
            totalAktivitasBulanIni++;
            aktivitasBulanIni.push(aktivitas);
        }
    }
    
    // Update badge jumlah event
    if (window.badgeJumlahEvent) {
        window.badgeJumlahEvent.textContent = totalAktivitasBulanIni;
        window.badgeJumlahEvent.style.display = totalAktivitasBulanIni > 0 ? 'flex' : 'none';
    }
    
    // Reset dan tampilkan daftar aktivitas bulan ini
    window.containerAktivitas.innerHTML = '';
    
    if (aktivitasBulanIni.length === 0) {
        var pesanKosong = document.createElement('div');
        pesanKosong.className = 'no-events';
        var tahunPendek = tahunSekarang.toString().substr(-2);
        pesanKosong.innerHTML = `
            <iconify-icon icon="mdi:calendar-outline" width="64" height="64"></iconify-icon>
            <h4>Tidak ada acara</h4>
            <p>Belum ada acara yang dijadwalkan untuk bulan ${namaBulan[bulanSekarang]} ${tahunPendek}</p>
        `;
        window.containerAktivitas.appendChild(pesanKosong);
    } else {
        // Sort berdasarkan filter
        if (window.sortFilter) {
            var sortValue = window.sortFilter.value;
            
            if (sortValue === 'tanggal_desc') {
                aktivitasBulanIni.sort(function(a, b) {
                    return new Date(b.waktu_acara) - new Date(a.waktu_acara);
                });
            } else if (sortValue === 'judul') {
                aktivitasBulanIni.sort(function(a, b) {
                    return a.judul_acara.localeCompare(b.judul_acara);
                });
            } else { // default: tanggal_asc
                aktivitasBulanIni.sort(function(a, b) {
                    return new Date(a.waktu_acara) - new Date(b.waktu_acara);
                });
            }
        }
        
        for (var j = 0; j < aktivitasBulanIni.length; j++) {
            var item = aktivitasBulanIni[j];
            var tanggalObj = new Date(item.waktu_acara);
            
            // Format tanggal dan waktu untuk tampilan dalam MM/DD/YY
            var tanggalTampilan = formatTanggalTampilanMMDDYY(tanggalObj);
            var jamTampilan = tanggalObj.getHours().toString().padStart(2, '0') + ':' + tanggalObj.getMinutes().toString().padStart(2, '0');
            var waktuTampilan = jamTampilan === '00:00' ? 'Sepanjang hari' : jamTampilan;
            
            var itemEl = document.createElement('div');
            itemEl.className = 'acara';
            itemEl.innerHTML = `
                <div class="acara-actions">
                    <button class="action-btn detail-btn" data-id="${item.id_acara}" title="Lihat Detail">
                        <iconify-icon icon="mdi:eye" width="16" height="16"></iconify-icon>
                    </button>
                    <button class="action-btn edit-btn" data-id="${item.id_acara}" title="Edit">
                        <iconify-icon icon="mdi:pencil" width="16" height="16"></iconify-icon>
                    </button>
                    <button class="action-btn hapus-btn" data-id="${item.id_acara}" title="Hapus">
                        <iconify-icon icon="mdi:trash-can-outline" width="16" height="16"></iconify-icon>
                    </button>
                </div>
                <div class="event-date">
                    <iconify-icon icon="mdi:calendar" width="14" height="14"></iconify-icon>
                    ${tanggalTampilan} - ${waktuTampilan}
                </div>
                <h4>${item.judul_acara}</h4>
                <div class="event-desc">${item.desc_acara || 'Tidak ada deskripsi'}</div>
            `;
            
            window.containerAktivitas.appendChild(itemEl);
        }
        
        // Tambahkan event listener untuk tombol-tombol
        var tombolDetail = window.containerAktivitas.querySelectorAll('.detail-btn');
        var tombolEdit = window.containerAktivitas.querySelectorAll('.edit-btn');
        var tombolHapus = window.containerAktivitas.querySelectorAll('.hapus-btn');
        
        for (var k = 0; k < tombolDetail.length; k++) {
            tombolDetail[k].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                bukaDetailAktivitas(idAcara);
            });
        }
        
        for (var l = 0; l < tombolEdit.length; l++) {
            tombolEdit[l].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                editAktivitas(idAcara);
            });
        }
        
        for (var m = 0; m < tombolHapus.length; m++) {
            tombolHapus[m].addEventListener('click', function(e) {
                e.stopPropagation();
                var idAcara = this.getAttribute('data-id');
                konfirmasiHapusAktivitas(idAcara);
            });
        }
    }
}

// Fungsi untuk format tanggal tampilan dalam MM/DD/YY dengan nama bulan
function formatTanggalTampilanMMDDYY(tanggalObj) {
    var hari = tanggalObj.getDate();
    var bulan = tanggalObj.getMonth();
    var tahun = tanggalObj.getFullYear();
    var tahunPendek = tahun.toString().substr(-2);
    
    return (tanggalObj.getMonth() + 1).toString().padStart(2, '0') + '/' + 
           hari.toString().padStart(2, '0') + '/' + tahunPendek + 
           ' (' + namaBulan[bulan] + ')';
}

// Fungsi untuk navigasi ke bulan sebelumnya
function bulanSebelumnya() {
    bulanSekarang--;
    
    // Jika bulan menjadi -1 (Desember tahun sebelumnya)
    if (bulanSekarang < 0) {
        bulanSekarang = 11;
        tahunSekarang--;
    }
    
    tampilkanKalender();
    tampilkanDaftarAktivitas();
}

// Fungsi untuk navigasi ke bulan selanjutnya
function bulanSelanjutnya() {
    bulanSekarang++;
    
    // Jika bulan menjadi 12 (Januari tahun berikutnya)
    if (bulanSekarang > 11) {
        bulanSekarang = 0;
        tahunSekarang++;
    }
    
    tampilkanKalender();
    tampilkanDaftarAktivitas();
}

// PERBAIKAN: Fungsi untuk membuka modal tambah acara
function bukaModalTambahAcara(tanggalMMDDYY) {
    if (!window.modalUtama) {
        console.log('Modal tidak ditemukan');
        return;
    }
    
    // Reset form
    resetForm();
    
    // Set tanggal jika ada
    if (tanggalMMDDYY && window.inputTanggal) {
        if (typeof tanggalMMDDYY === 'string') {
            // Konversi MM/DD/YY ke format input (YYYY-MM-DD)
            var formatDatabase = konversiKeFormatDatabase(tanggalMMDDYY);
            if (formatDatabase) {
                window.inputTanggal.value = formatDatabase;
            }
        }
    }
    
    // Buka modal
    window.modalUtama.style.display = 'flex';
    window.modalUtama.classList.add('modal-active');
    document.body.style.overflow = 'hidden';
    
    // Focus ke judul
    setTimeout(function() {
        if (window.inputJudul) {
            window.inputJudul.focus();
        }
    }, 100);
    
    // Update judul modal
    var judulModal = window.modalUtama.querySelector('.modalTitle');
    var textUpdate = window.modalUtama.querySelector('.update-text');
    
    if (judulModal) {
        judulModal.textContent = 'Buat Acara Baru';
        judulModal.style.display = 'block';
    }
    
    if (textUpdate) {
        textUpdate.style.display = 'none';
    }
}

// Fungsi untuk menutup modal
function tutupModal() {
    if (!window.modalUtama) return;
    
    window.modalUtama.style.display = 'none';
    window.modalUtama.classList.remove('modal-active');
    document.body.style.overflow = 'auto';
    resetForm();
}

// PERBAIKAN 3: Fungsi simpan aktivitas yang benar
function simpanAktivitas() {
    console.log('Mulai menyimpan aktivitas');
    
    if (!window.formEvent || !window.inputJudul || !window.inputTanggal) {
        console.log('Form tidak lengkap');
        alert('Error: Form tidak lengkap!');
        return;
    }
    
    var judul = window.inputJudul.value.trim();
    var tanggal = window.inputTanggal.value.trim(); // Format YYYY-MM-DD dari input HTML
    var waktu = window.inputWaktu?.value || '00:00';
    var deskripsi = window.inputDeskripsi?.value || '';
    
    // Validasi input
    if (!judul) {
        alert('Judul acara harus diisi!');
        window.inputJudul.focus();
        return;
    }
    
    if (!tanggal) {
        alert('Tanggal harus dipilih!');
        window.inputTanggal.focus();
        return;
    }
    
    // Validasi format tanggal (YYYY-MM-DD untuk input type="date")
    if (!/^\d{4}-\d{2}-\d{2}$/.test(tanggal)) {
        alert('Format tanggal salah. Pilih tanggal dari calendar.');
        window.inputTanggal.focus();
        return;
    }
    
    // Validasi format waktu jika diisi
    if (waktu && waktu !== '00:00') {
        if (!/^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(waktu)) {
            alert('Format waktu salah. Gunakan format HH:MM');
            window.inputWaktu.focus();
            return;
        }
    }
    
    // Gabung tanggal dan waktu jadi YYYY-MM-DD HH:MM:SS (format database)
    var waktuAcara = tanggal + ' ' + waktu + ':00';
    
    console.log('Data yang akan disimpan:', {
        judul: judul,
        tanggal: tanggal,
        waktu: waktu,
        waktuAcara: waktuAcara
    });
    
    // Validasi reminder jika aktif
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderTemplate = document.getElementById('reminder_template');
    var customReminder = document.getElementById('custom_reminder');
    
    if (reminderEnabled && reminderEnabled.checked && reminderTemplate) {
        if (!reminderTemplate.value) {
            alert("Pilih waktu pengingat atau matikan pengingat.");
            reminderTemplate.focus();
            return;
        }
        
        if (reminderTemplate.value === 'custom') {
            var customInput = customReminder ? customReminder.querySelector('input[name="custom_minutes"]') : null;
            if (!customInput || !customInput.value || parseInt(customInput.value) < 1 || parseInt(customInput.value) > 10080) {
                alert("Waktu pengingat kustom tidak valid (harus antara 1 menit sampai 7 hari)");
                if (customInput) customInput.focus();
                return;
            }
        }
    }
    
    // Hapus tombol action lama
    var tombolLama = window.formEvent.querySelectorAll('input[name="save"], input[name="edit"]');
    for (var i = 0; i < tombolLama.length; i++) {
        tombolLama[i].remove();
    }
    
    // Tambah tombol action baru
    var tombolAction = document.createElement('input');
    tombolAction.type = 'hidden';
    
    if (idAktivitasYangDiedit) {
        tombolAction.name = 'edit';
        tombolAction.value = '1';
        if (window.inputIdAcara) window.inputIdAcara.value = idAktivitasYangDiedit;
    } else {
        tombolAction.name = 'save';
        tombolAction.value = '1';
    }
    
    window.formEvent.appendChild(tombolAction);
    
    console.log('Kirim form ke server...');
    window.formEvent.submit();
}

// PERBAIKAN 4: Fungsi edit aktivitas yang benar
function editAktivitas(idAcara) {
    var aktivitas = null;
    for (var i = 0; i < daftarAktivitas.length; i++) {
        if (daftarAktivitas[i].id_acara == idAcara) {
            aktivitas = daftarAktivitas[i];
            break;
        }
    }
    
    if (!aktivitas) {
        alert("Data aktivitas tidak ditemukan!");
        return;
    }
    
    idAktivitasYangDiedit = idAcara;
    
    resetForm();
    
    if (window.inputJudul) window.inputJudul.value = aktivitas.judul_acara;
    if (window.inputDeskripsi) window.inputDeskripsi.value = aktivitas.desc_acara || '';
    if (window.inputIdAcara) window.inputIdAcara.value = idAcara;
    
    if (aktivitas.waktu_acara) {
        var tanggalObj = new Date(aktivitas.waktu_acara);
        
        // Set tanggal dalam format YYYY-MM-DD untuk input
        if (window.inputTanggal) {
            var tahun = tanggalObj.getFullYear();
            var bulan = (tanggalObj.getMonth() + 1).toString().padStart(2, '0');
            var hari = tanggalObj.getDate().toString().padStart(2, '0');
            window.inputTanggal.value = tahun + '-' + bulan + '-' + hari;
        }
        
        // Set waktu dalam format HH:MM
        if (window.inputWaktu) {
            var jam = tanggalObj.getHours().toString().padStart(2, '0');
            var menit = tanggalObj.getMinutes().toString().padStart(2, '0');
            var waktu = jam + ':' + menit;
            window.inputWaktu.value = (waktu === '00:00' ? '' : waktu);
        }
    }
    
    var judulModal = window.modalUtama?.querySelector('.modalTitle');
    var textUpdate = window.modalUtama?.querySelector('.update-text');
    
    if (judulModal) judulModal.style.display = 'none';
    if (textUpdate) {
        textUpdate.textContent = 'Edit Aktivitas';
        textUpdate.style.display = 'block';
    }
    
    if (window.modalUtama) {
        window.modalUtama.style.display = 'flex';
        window.modalUtama.classList.add('modal-active');
        document.body.style.overflow = 'hidden';
    }
    
    setTimeout(function() {
        if (window.inputJudul) window.inputJudul.focus();
    }, 100);
}

// Fungsi untuk membuka detail aktivitas
function bukaDetailAktivitas(idAcara) {
    var aktivitas = null;
    for (var i = 0; i < daftarAktivitas.length; i++) {
        if (daftarAktivitas[i].id_acara == idAcara) {
            aktivitas = daftarAktivitas[i];
            break;
        }
    }
    
    if (!aktivitas) {
        alert("Data aktivitas tidak ditemukan!");
        return;
    }
    
    // Jika modal detail tidak ditemukan, tampilkan dalam alert
    if (!window.modalDetail) {
        var tanggalObj = new Date(aktivitas.waktu_acara);
        var tanggalMMDDYY = konversiDariDatabase(aktivitas.waktu_acara);
        var jamTampilan = tanggalObj.getHours().toString().padStart(2, '0') + ':' + tanggalObj.getMinutes().toString().padStart(2, '0');
        var waktuTampilan = jamTampilan === '00:00' ? 'Sepanjang hari' : 'jam ' + jamTampilan;
        
        alert("Detail Aktivitas:\n\nJudul: " + aktivitas.judul_acara + 
              "\nTanggal: " + tanggalMMDDYY + ' ' + waktuTampilan + 
              "\nDeskripsi: " + (aktivitas.desc_acara || 'Tidak ada deskripsi'));
        return;
    }
    
    // Update konten modal detail
    var judulDetail = window.modalDetail.querySelector('#sideDetailJudul') || window.modalDetail.querySelector('h3');
    if (judulDetail) {
        judulDetail.textContent = aktivitas.judul_acara;
    }
    
    var deskripsiDetail = window.modalDetail.querySelector('#sideDetailDeskripsi');
    if (deskripsiDetail) {
        deskripsiDetail.textContent = aktivitas.desc_acara || 'Tidak ada deskripsi';
    }
    
    var tanggalDetail = window.modalDetail.querySelector('#sideDetailTanggalMulai');
    if (tanggalDetail) {
        var tanggalMMDDYY = konversiDariDatabase(aktivitas.waktu_acara);
        tanggalDetail.textContent = tanggalMMDDYY;
    }
    
    var waktuDetail = window.modalDetail.querySelector('#sideDetailTanggalBerakhir');
    if (waktuDetail) {
        var tanggalObj = new Date(aktivitas.waktu_acara);
        var jamTampilan = tanggalObj.getHours().toString().padStart(2, '0') + ':' + tanggalObj.getMinutes().toString().padStart(2, '0');
        waktuDetail.textContent = jamTampilan === '00:00' ? 'Sepanjang hari' : jamTampilan;
    }
    
    // Set data-id untuk tombol edit dan hapus
    var editBtn = window.modalDetail.querySelector('#editSideAcaraBtn');
    var hapusBtn = window.modalDetail.querySelector('#hapusSideAcaraBtn');
    
    if (editBtn) editBtn.setAttribute('data-id', aktivitas.id_acara);
    if (hapusBtn) hapusBtn.setAttribute('data-id', aktivitas.id_acara);
    
    // Tampilkan modal
    window.modalDetail.style.display = 'block';
    window.modalDetail.classList.add('open');
}

// Fungsi untuk menutup modal detail
function closeSideModal() {
    if (window.modalDetail) {
        window.modalDetail.style.display = 'none';
        window.modalDetail.classList.remove('open');
    }
}

// Fungsi untuk edit dari modal detail
function editFromSide() {
    var btnEdit = document.getElementById('editSideAcaraBtn');
    if (btnEdit) {
        var idAcara = btnEdit.getAttribute('data-id');
        closeSideModal();
        editAktivitas(idAcara);
    }
}

// Fungsi untuk hapus dari modal detail
function deleteFromSide() {
    var btnHapus = document.getElementById('hapusSideAcaraBtn');
    if (btnHapus) {
        var idAcara = btnHapus.getAttribute('data-id');
        closeSideModal();
        konfirmasiHapusAktivitas(idAcara);
    }
}

// Fungsi untuk konfirmasi hapus aktivitas
function konfirmasiHapusAktivitas(idAcara) {
    var aktivitas = null;
    for (var i = 0; i < daftarAktivitas.length; i++) {
        if (daftarAktivitas[i].id_acara == idAcara) {
            aktivitas = daftarAktivitas[i];
            break;
        }
    }
    
    if (!aktivitas) {
        alert("Data aktivitas tidak ditemukan!");
        return;
    }
    
    var tanggalMMDDYY = konversiDariDatabase(aktivitas.waktu_acara);
    
    if (confirm("Apakah Anda yakin ingin menghapus aktivitas \"" + aktivitas.judul_acara + "\" pada tanggal " + tanggalMMDDYY + "?")) {
        var formHapus = document.createElement('form');
        formHapus.method = 'POST';
        formHapus.action = ''; // Gunakan URL saat ini
        
        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id_acara';
        inputId.value = idAcara;
        
        var inputHapus = document.createElement('input');
        inputHapus.type = 'hidden';
        inputHapus.name = 'hapus';
        inputHapus.value = '1';
        
        formHapus.appendChild(inputId);
        formHapus.appendChild(inputHapus);
        document.body.appendChild(formHapus);
        formHapus.submit();
    }
}

// Global functions untuk dipanggil dari HTML
window.openModal = bukaModalTambahAcara;
window.closeModal = tutupModal;
window.saveEvent = simpanAktivitas;
window.prevMonth = bulanSebelumnya;
window.nextMonth = bulanSelanjutnya;

// Handler untuk pengingat jika ada
function toggleReminderOptions() {
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderOptions = document.getElementById('reminder_options');
    
    if (reminderEnabled && reminderOptions) {
        if (reminderEnabled.checked) {
            reminderOptions.classList.add('active');
        } else {
            reminderOptions.classList.remove('active');
        }
    }
}

// Handler untuk reminder kustom
function toggleCustomReminder() {
    var reminderTemplate = document.getElementById('reminder_template');
    var customReminder = document.getElementById('custom_reminder');
    
    if (reminderTemplate && customReminder) {
        if (reminderTemplate.value === 'custom') {
            customReminder.classList.add('active');
        } else {
            customReminder.classList.remove('active');
        }
    }
}

// Auto-hide alert messages
function hideAlert() {
    var alertElement = document.getElementById('alertMessage');
    if (alertElement) {
        alertElement.classList.add('show');
        setTimeout(function() {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translateX(100%)';
            setTimeout(function() {
                if (alertElement.parentNode) {
                    alertElement.parentNode.removeChild(alertElement);
                }
            }, 300);
        }, 5000);
    }
}

// Setup saat DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    // Setup reminder handlers
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderTemplate = document.getElementById('reminder_template');
    
    if (reminderEnabled) {
        reminderEnabled.addEventListener('change', toggleReminderOptions);
    }
    
    if (reminderTemplate) {
        reminderTemplate.addEventListener('change', toggleCustomReminder);
    }
    
    // Auto-hide alerts
    hideAlert();
    
    // Debug data
    debugDataAktivitas();
    
    // Jalankan sistem kalender
    jalankanSistemKalender();
});

// PERBAIKAN 8: Debug console log untuk troubleshooting
function debugDataAktivitas() {
    console.log('=== DEBUG DATA AKTIVITAS ===');
    console.log('Total aktivitas:', daftarAktivitas.length);
    console.log('Data aktivitas:', daftarAktivitas);
    console.log('Bulan sekarang:', bulanSekarang);
    console.log('Tahun sekarang:', tahunSekarang);
    
    // Test konversi tanggal
    if (daftarAktivitas.length > 0) {
        var contoh = daftarAktivitas[0];
        console.log('Contoh data:', contoh);
        console.log('Waktu acara:', contoh.waktu_acara);
        console.log('Konversi ke MM/DD/YY:', konversiDariDatabase(contoh.waktu_acara));
    }
    console.log('=== END DEBUG ===');
}

// Panggil debug saat load
document.addEventListener('DOMContentLoaded', function() {
    // Setup reminder handlers
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderTemplate = document.getElementById('reminder_template');
    
    if (reminderEnabled) {
        reminderEnabled.addEventListener('change', toggleReminderOptions);
    }
    
    if (reminderTemplate) {
        reminderTemplate.addEventListener('change', toggleCustomReminder);
    }
    
    // Auto-hide alerts
    hideAlert();
    
    // Debug data
    debugDataAktivitas();
    
    // Jalankan sistem kalender
    jalankanSistemKalender();
});