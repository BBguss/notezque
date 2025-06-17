/**
 * ==============================================
 * SISTEM KALENDER AKTIVITAS - DENGAN FORMAT MM/DD/YY
 * ==============================================
 */

// ==================== VARIABEL GLOBAL ====================
var tanggalHariIni = new Date();
var bulanSekarang = tanggalHariIni.getMonth();
var tahunSekarang = tanggalHariIni.getFullYear();
var daftarAktivitas = window.phpEvents || [];
var idAktivitasYangDiedit = null;

// Nama-nama bulan dan hari dalam bahasa Indonesia
var namaBulan = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
var namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// ==================== INISIALISASI ====================
function jalankanSistemKalender() {
    ambilElemenHTML();
    buatTombolKalender();
    tambahkanEventListener();
    tampilkanKalender();
    debugDataAktivitas();
}

function ambilElemenHTML() {
    // Elemen kalender
    window.tempatTanggal = document.querySelector('.tglBln');
    window.judulBulanTahun = document.querySelector('.bln-thn');
    window.containerAktivitas = document.querySelector('.acara-container');
    
    // Elemen modal
    window.modalUtama = document.getElementById('modal');
    window.modalDetail = document.getElementById('sideModalDetail');
    
    // Tombol dan input
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

// ==================== SETUP EVENT LISTENERS ====================
function buatTombolKalender() {
    var prevBtn = document.querySelector('.prev');
    var nextBtn = document.querySelector('.next');
    
    if (prevBtn) prevBtn.onclick = bulanSebelumnya;
    if (nextBtn) nextBtn.onclick = bulanSelanjutnya;
    if (window.tombolTambah) window.tombolTambah.onclick = bukaModalTambahAcara;
}

function tambahkanEventListener() {
    // Klik pada tanggal di kalender
    if (window.tempatTanggal) {
        window.tempatTanggal.addEventListener('click', function(e) {
            var targetTanggal = e.target.closest('.tgl');
            if (targetTanggal && !targetTanggal.classList.contains('tglSblm') && !targetTanggal.classList.contains('tglStlh')) {
                var tanggal = targetTanggal.dataset.tanggal;
                if (tanggal) bukaModalTambahAcara(tanggal);
            }
        });
    }

    // Keyboard dan modal events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (window.modalUtama && window.modalUtama.classList.contains('modal-active')) tutupModal();
            if (window.modalDetail && window.modalDetail.style.display === 'block') closeSideModal();
        }
    });
    
    // Form events
    if (window.tombolBatal) window.tombolBatal.addEventListener('click', tutupModal);
    if (window.tombolSimpan) window.tombolSimpan.addEventListener('click', simpanAktivitas);
    if (window.formEvent) {
        window.formEvent.addEventListener('submit', function(e) {
            e.preventDefault();
            simpanAktivitas();
        });
    }
    
    // Filter events dengan reload halaman
    if (window.sortFilter) {
        window.sortFilter.addEventListener('change', function() {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            currentUrl.searchParams.set('page', '1');
            window.location.href = currentUrl.toString();
        });
    }
    
    var clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('sort');
            currentUrl.searchParams.set('page', '1');
            window.location.href = currentUrl.toString();
        });
    }
    
    // Modal detail events
    var closeDetailBtn = document.querySelector('.side-modal-close');
    if (closeDetailBtn) closeDetailBtn.addEventListener('click', closeSideModal);
    
    var editDetailBtn = document.getElementById('editSideAcaraBtn');
    var hapusDetailBtn = document.getElementById('hapusSideAcaraBtn');
    if (editDetailBtn) editDetailBtn.onclick = editFromSide;
    if (hapusDetailBtn) hapusDetailBtn.onclick = deleteFromSide;
    
    // Modal overlay
    if (window.modalUtama) {
        window.modalUtama.addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    }
}

// ==================== FUNGSI KONVERSI TANGGAL ====================
function formatTanggalMMDDYY(tahun, bulan, hari) {
    hari = hari < 10 ? '0' + hari : hari;
    bulan = bulan < 10 ? '0' + bulan : bulan;
    var tahunPendek = tahun.toString().substr(-2);
    return bulan + '/' + hari + '/' + tahunPendek;
}

function konversiKeFormatDatabase(tanggalMMDDYY) {
    if (!tanggalMMDDYY) return '';
    
    var parts = tanggalMMDDYY.split('/');
    if (parts.length !== 3) return '';
    
    var bulan = parts[0];
    var hari = parts[1]; 
    var tahunPendek = parts[2];
    var tahunPenuh = '20' + tahunPendek;
    
    return tahunPenuh + '-' + bulan + '-' + hari;
}

function konversiDariDatabase(tanggalDatabase) {
    if (!tanggalDatabase) return '';
    
    var parts = tanggalDatabase.split(' ')[0].split('-');
    if (parts.length !== 3) return '';
    
    var tahun = parts[0];
    var bulan = parts[1];
    var hari = parts[2];
    var tahunPendek = tahun.substr(-2);
    
    return bulan + '/' + hari + '/' + tahunPendek;
}

function formatTanggalTampilanMMDDYY(tanggalObj) {
    var hari = tanggalObj.getDate();
    var bulan = tanggalObj.getMonth();
    var tahun = tanggalObj.getFullYear();
    var tahunPendek = tahun.toString().substr(-2);
    
    return (tanggalObj.getMonth() + 1).toString().padStart(2, '0') + '/' + 
           hari.toString().padStart(2, '0') + '/' + tahunPendek + 
           ' (' + namaBulan[bulan] + ')';
}

// ==================== TAMPILAN KALENDER ====================
function tampilkanKalender() {
    if (!window.tempatTanggal || !window.judulBulanTahun) return;
    
    window.tempatTanggal.innerHTML = '';
    
    var tahun = tahunSekarang;
    var bulan = bulanSekarang;
    var tanggalPertama = new Date(tahun, bulan, 1);
    var tanggalTerakhir = new Date(tahun, bulan + 1, 0);
    var jumlahHari = tanggalTerakhir.getDate();
    var hariPertama = tanggalPertama.getDay();
    
    // Set judul bulan dan tahun
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
        
        var tanggalFormat = formatTanggalMMDDYY(tahun, bulan + 1, i);
        kotakHari.dataset.tanggal = tanggalFormat;
        
        // Tandai jika hari ini
        if (tahun === hariIni.getFullYear() && bulan === hariIni.getMonth() && i === hariIni.getDate()) {
            kotakHari.classList.add('tglSkrg');
        }
        
        tambahIndikatorAktivitas(kotakHari, tanggalFormat);
        window.tempatTanggal.appendChild(kotakHari);
    }
    
    // Tambahkan tanggal awal bulan depan
    var totalKotak = window.tempatTanggal.children.length;
    var sisaKotak = 42 - totalKotak;
    
    for (var i = 1; i <= sisaKotak; i++) {
        var kotakHari = document.createElement('div');
        kotakHari.className = 'tgl tglStlh';
        kotakHari.textContent = i;
        window.tempatTanggal.appendChild(kotakHari);
    }
}

function tambahIndikatorAktivitas(kotakHari, tanggalMMDDYY) {
    var aktivitasHariIni = [];
    
    for (var i = 0; i < daftarAktivitas.length; i++) {
        var aktivitas = daftarAktivitas[i];
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
        
        var judul = aktivitasHariIni[i].judul_acara;
        var judulPendek = judul.length > 10 ? judul.substring(0, 10) + '...' : judul;
        
        indikator.textContent = judulPendek;
        indikator.title = judul;
        kotakHari.appendChild(indikator);
    }
    
    // Tampilkan "+X lagi" jika ada lebih banyak
    if (aktivitasHariIni.length > maksIndikator) {
        var indikatorLebih = document.createElement('div');
        indikatorLebih.className = 'acr more-acr';
        indikatorLebih.textContent = '+' + (aktivitasHariIni.length - maksIndikator) + ' lagi';
        indikatorLebih.style.backgroundColor = '#6b7280';
        kotakHari.appendChild(indikatorLebih);
    }
}

// ==================== NAVIGASI BULAN ====================
function bulanSebelumnya() {
    bulanSekarang--;
    if (bulanSekarang < 0) {
        bulanSekarang = 11;
        tahunSekarang--;
    }
    tampilkanKalender();
}

function bulanSelanjutnya() {
    bulanSekarang++;
    if (bulanSekarang > 11) {
        bulanSekarang = 0;
        tahunSekarang++;
    }
    tampilkanKalender();
}

// ==================== PAGINATION ====================
function changePage(direction) {
    var currentUrl = new URL(window.location.href);
    var currentPage = parseInt(currentUrl.searchParams.get('page') || '1');
    var newPage = currentPage + direction;
    
    if (newPage < 1) newPage = 1;
    
    currentUrl.searchParams.set('page', newPage);
    window.location.href = currentUrl.toString();
}

// ==================== MODAL MANAGEMENT ====================
function bukaModalTambahAcara(tanggalMMDDYY) {
    if (!window.modalUtama) {
        console.log('Modal tidak ditemukan');
        return;
    }
    
    resetForm();
    
    // Set tanggal jika ada
    if (tanggalMMDDYY && window.inputTanggal) {
        if (typeof tanggalMMDDYY === 'string') {
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
    
    setTimeout(function() {
        if (window.inputJudul) window.inputJudul.focus();
    }, 100);
    
    // Update judul modal
    var judulModal = window.modalUtama.querySelector('.modalTitle');
    var textUpdate = window.modalUtama.querySelector('.update-text');
    
    if (judulModal) {
        judulModal.textContent = 'Buat Acara Baru';
        judulModal.style.display = 'block';
    }
    if (textUpdate) textUpdate.style.display = 'none';
}

function tutupModal() {
    if (!window.modalUtama) return;
    
    window.modalUtama.style.display = 'none';
    window.modalUtama.classList.remove('modal-active');
    document.body.style.overflow = 'auto';
    resetForm();
}

function resetForm() {
    if (window.formEvent) window.formEvent.reset();
    
    idAktivitasYangDiedit = null;
    
    if (window.inputJudul) window.inputJudul.value = '';
    if (window.inputDeskripsi) window.inputDeskripsi.value = '';
    if (window.inputIdAcara) window.inputIdAcara.value = '';
    
    // Set tanggal hari ini
    if (window.inputTanggal) {
        var hariIni = new Date();
        var tahun = hariIni.getFullYear();
        var bulan = (hariIni.getMonth() + 1).toString().padStart(2, '0');
        var hari = hariIni.getDate().toString().padStart(2, '0');
        window.inputTanggal.value = tahun + '-' + bulan + '-' + hari;
    }
    
    if (window.inputWaktu) window.inputWaktu.value = '';
    
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

// ==================== CRUD OPERATIONS ====================
function simpanAktivitas() {
    console.log('Mulai menyimpan aktivitas');
    
    if (!window.formEvent || !window.inputJudul || !window.inputTanggal) {
        console.log('Form tidak lengkap');
        alert('Error: Form tidak lengkap!');
        return;
    }
    
    var judul = window.inputJudul.value.trim();
    var tanggal = window.inputTanggal.value.trim();
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
    
    if (!/^\d{4}-\d{2}-\d{2}$/.test(tanggal)) {
        alert('Format tanggal salah. Pilih tanggal dari calendar.');
        window.inputTanggal.focus();
        return;
    }
    
    if (waktu && waktu !== '00:00') {
        if (!/^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(waktu)) {
            alert('Format waktu salah. Gunakan format HH:MM');
            window.inputWaktu.focus();
            return;
        }
    }
    
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
        
        if (window.inputTanggal) {
            var tahun = tanggalObj.getFullYear();
            var bulan = (tanggalObj.getMonth() + 1).toString().padStart(2, '0');
            var hari = tanggalObj.getDate().toString().padStart(2, '0');
            window.inputTanggal.value = tahun + '-' + bulan + '-' + hari;
        }
        
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
        formHapus.action = '';
        
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

// ==================== MODAL DETAIL ====================
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
    if (judulDetail) judulDetail.textContent = aktivitas.judul_acara;
    
    var deskripsiDetail = window.modalDetail.querySelector('#sideDetailDeskripsi');
    if (deskripsiDetail) deskripsiDetail.textContent = aktivitas.desc_acara || 'Tidak ada deskripsi';
    
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
    
    // Set data-id untuk tombol
    var editBtn = window.modalDetail.querySelector('#editSideAcaraBtn');
    var hapusBtn = window.modalDetail.querySelector('#hapusSideAcaraBtn');
    
    if (editBtn) editBtn.setAttribute('data-id', aktivitas.id_acara);
    if (hapusBtn) hapusBtn.setAttribute('data-id', aktivitas.id_acara);
    
    // Tampilkan modal
    window.modalDetail.style.display = 'block';
    window.modalDetail.classList.add('open');
}

function closeSideModal() {
    if (window.modalDetail) {
        window.modalDetail.style.display = 'none';
        window.modalDetail.classList.remove('open');
    }
}

function editFromSide() {
    var btnEdit = document.getElementById('editSideAcaraBtn');
    if (btnEdit) {
        var idAcara = btnEdit.getAttribute('data-id');
        closeSideModal();
        editAktivitas(idAcara);
    }
}

function deleteFromSide() {
    var btnHapus = document.getElementById('hapusSideAcaraBtn');
    if (btnHapus) {
        var idAcara = btnHapus.getAttribute('data-id');
        closeSideModal();
        konfirmasiHapusAktivitas(idAcara);
    }
}

// ==================== REMINDER FUNCTIONS ====================
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

// ==================== UTILITY FUNCTIONS ====================
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

function debugDataAktivitas() {
    console.log('=== DEBUG DATA AKTIVITAS ===');
    console.log('Total aktivitas:', daftarAktivitas.length);
    console.log('Data aktivitas:', daftarAktivitas);
    console.log('Bulan sekarang:', bulanSekarang);
    console.log('Tahun sekarang:', tahunSekarang);
    
    if (daftarAktivitas.length > 0) {
        var contoh = daftarAktivitas[0];
        console.log('Contoh data:', contoh);
        console.log('Waktu acara:', contoh.waktu_acara);
        console.log('Konversi ke MM/DD/YY:', konversiDariDatabase(contoh.waktu_acara));
    }
    console.log('=== END DEBUG ===');
}

// ==================== GLOBAL WINDOW FUNCTIONS ====================
window.openModal = bukaModalTambahAcara;
window.closeModal = tutupModal;
window.saveEvent = simpanAktivitas;
window.prevMonth = bulanSebelumnya;
window.nextMonth = bulanSelanjutnya;

// ==================== DOCUMENT READY ====================
document.addEventListener('DOMContentLoaded', function() {
    // Setup reminder handlers
    var reminderEnabled = document.getElementById('reminder_enabled');
    var reminderTemplate = document.getElementById('reminder_template');
    
    if (reminderEnabled) reminderEnabled.addEventListener('change', toggleReminderOptions);
    if (reminderTemplate) reminderTemplate.addEventListener('change', toggleCustomReminder);
    
    // Auto-hide alerts
    hideAlert();
    
    // Jalankan sistem kalender
    jalankanSistemKalender();
});