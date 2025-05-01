// ================================================
//              SISTEM KALENDER SEDERHANA
// ================================================

// Tunggu sampai halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    
    // Ambil semua elemen HTML yang diperlukan
    const elemen = {
        tombolNext: document.querySelector('.next'),
        tombolPrev: document.querySelector('.prev'),
        tampilanBulanTahun: document.querySelector('.bln-thn'),
        tampilanTanggal: document.querySelector('.tglBln'),
        daftarAcara: document.querySelector('.side-listAcara'),
        popup: document.getElementById('modal'),
        form: document.querySelector('.form'),
        tombolTutup: document.getElementById('closeBtn'),
        tombolSimpan: document.getElementById('save'),
        inputJudul: document.getElementById('title'),
        inputDeskripsi: document.getElementById('desk'),
        inputWaktu: document.getElementById('tenggat'),
        modalTitle: document.querySelector('.modalTitle'),
        updateText: document.querySelector('.update')
    };

    // Data yang akan digunakan
    const data = {
        tanggalSekarang: new Date(),
        tanggalDipilih: null,       
        daftarAcara: [],            
        namaBulan: [                 
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ],
        warnaAcara: [           
            '#ea4335', // Merah Google
            '#4285f4', // Biru Google
            '#1e90ff', // Biru Laut
            '#8a2be2', // Ungu Terang
            '#ff1493', // Pink Cerah
            '#b22222', // Merah Gelap
            '#ff4500', // Oranye Merah
            '#32cd32', // Hijau Cerah
            '#40e0d0', // Biru Laut Muda
            '#d2691e'  // Coklat Oranye
        ]
    };

    // Fungsi untuk mengambil acara dari database
    async function ambilAcaraDariDatabase() {
        try {
            // Kirim request ke server
            const response = await fetch('get_events.php');
            
            // Cek jika ada error
            if (!response.ok) {
                throw new Error('Gagal mengambil data acara');
            }
            
            // Ubah response ke format JSON
            const acara = await response.json();
            
            // Format data acara
            data.daftarAcara = acara.map(acara => ({
                id: acara.id_acara,
                judul: acara.judul_acara,
                deskripsi: acara.desc_acara,
                tanggal: formatTanggal(acara.waktu_acara),
                waktu: formatWaktu(acara.waktu_acara),
                warna: data.warnaAcara[Math.floor(Math.random() * data.warnaAcara.length)]
            }));
            
            // Tampilkan kalender dan daftar acara
            tampilkanKalender();
            tampilkanDaftarAcara();
            
        } catch (error) {
            alert('Maaf, ada masalah saat mengambil data acara. Silakan coba lagi.');
        }
    }

    // Fungsi untuk mengubah format tanggal
    function formatTanggal(waktuDatabase) {
        const date = new Date(waktuDatabase);
        const tahun = date.getFullYear();
        const bulan = String(date.getMonth() + 1).padStart(2, '0');
        const tanggal = String(date.getDate()).padStart(2, '0');
        return `${tahun}-${bulan}-${tanggal}`;
    }

    // Fungsi untuk mengubah format waktu
    function formatWaktu(waktuDatabase) {
        const date = new Date(waktuDatabase);
        return date.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    // Fungsi untuk menampilkan kalender
    function tampilkanKalender() {
        elemen.tampilanTanggal.innerHTML = '';
        
        const tahun = data.tanggalSekarang.getFullYear();
        const bulan = data.tanggalSekarang.getMonth();
        const hariPertama = new Date(tahun, bulan, 1);
        const hariTerakhir = new Date(tahun, bulan + 1, 0);
        const jumlahHari = hariTerakhir.getDate();
        const hariAwal = hariPertama.getDay();
        const hariTerakhirBulanLalu = new Date(tahun, bulan, 0).getDate();

        // Tampilkan bulan dan tahun
        elemen.tampilanBulanTahun.textContent = 
            `${data.namaBulan[bulan]} ${tahun}`;

        let hitungHari = 1;
        let hitungHariBulanDepan = 1;

        // Buat 42 kotak hari (6 minggu)
        for (let i = 0; i < 42; i++) {
            const kotakHari = document.createElement('div');
            kotakHari.className = 'tgl';

            // Hari dari bulan sebelumnya
            if (i < hariAwal) {
                const hariBulanLalu = hariTerakhirBulanLalu - (hariAwal - i - 1);
                kotakHari.textContent = hariBulanLalu;
                kotakHari.classList.add('tglSblm');
                kotakHari.dataset.tanggal = `${tahun}-${bulan + 1}-${hariBulanLalu}`;
            } 
            // Hari bulan ini
            else if (hitungHari <= jumlahHari) {
                kotakHari.textContent = hitungHari;
                const tanggalFormat = 
                    `${tahun}-${String(bulan + 1).padStart(2, '0')}-${String(hitungHari).padStart(2, '0')}`;
                kotakHari.dataset.tanggal = tanggalFormat;

                // Tandai hari ini
                if (hitungHari === new Date().getDate() && 
                    bulan === new Date().getMonth() && 
                    tahun === new Date().getFullYear()) {
                    kotakHari.classList.add('tglSkrg');
                }

                // Tampilkan acara untuk hari ini
                tampilkanAcaraPerHari(kotakHari, tanggalFormat);
                hitungHari++;
            } 
            // Hari bulan depan
            else {
                kotakHari.textContent = hitungHariBulanDepan;
                kotakHari.classList.add('tglStlh');
                kotakHari.dataset.tanggal = `${tahun}-${bulan + 2}-${hitungHariBulanDepan}`;
                hitungHariBulanDepan++;
            }

            elemen.tampilanTanggal.appendChild(kotakHari);
        }
    }

    // Fungsi untuk menampilkan acara di setiap hari
    function tampilkanAcaraPerHari(kotakHari, tanggal) {
        const acaraHariIni = data.daftarAcara.filter(acara => acara.tanggal === tanggal);
        
        acaraHariIni.forEach(acara => {
            const elemenAcara = document.createElement('div');
            elemenAcara.className = 'acara';
            elemenAcara.innerHTML = `<h4>${acara.judul}</h4><p>${acara.waktu}</p>`;
            elemenAcara.style.backgroundColor = acara.warna;
            elemenAcara.dataset.idAcara = acara.id;
            kotakHari.appendChild(elemenAcara);
        });
    }

    // Fungsi untuk menampilkan daftar acara di sidebar
    function tampilkanDaftarAcara() {
        elemen.daftarAcara.innerHTML = '<h2 class="side-title" style="text-align: center">List acara</h2>';

        if (data.daftarAcara.length === 0) {
            elemen.daftarAcara.innerHTML +=  
                '<p class="no-events" style="text-align: center">Belum ada acara</p>';
            return;
        }

        // Urutkan acara berdasarkan tanggal dan waktu
        const acaraTerurut = [...data.daftarAcara].sort((a, b) => {
            const bandingTanggal = new Date(a.tanggal) - new Date(b.tanggal);
            if (bandingTanggal !== 0) return bandingTanggal;
            return a.waktu.localeCompare(b.waktu);
        });

        // Buat elemen untuk setiap acara
        acaraTerurut.forEach(acara => {
            const elemenAcara = document.createElement('div');
            elemenAcara.className = 'acara';
            elemenAcara.style.backgroundColor = acara.warna;
            elemenAcara.dataset.idAcara = acara.id;
            elemenAcara.innerHTML = `
                <h4>${acara.judul}</h4>
                <p class="event-desc">${acara.deskripsi || 'Tidak ada deskripsi'}</p>
                <p class="event-date">${formatTanggalTampilan(acara.tanggal)} pukul ${acara.waktu}</p>
                <iconify-icon class="hapus" icon="mdi:trash-can-empty" width="24" height="24"></iconify-icon>
            `;
            elemen.daftarAcara.appendChild(elemenAcara);
        });
    }

    // Fungsi untuk mengubah format tanggal menjadi lebih mudah dibaca
    function formatTanggalTampilan(tanggalStr) {
        const [tahun, bulan, tanggal] = tanggalStr.split('-');
        const date = new Date(tahun, bulan - 1, tanggal);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function bukaPopup(tanggal, acara = null) {
        // Set tanggal yang dipilih dan tampilkan popup
        data.tanggalDipilih = tanggal;
        elemen.popup.style.display = 'flex';
        elemen.popup.classList.add('modal-active');

        // Cari atau buat elemen update text
        let updateText = elemen.popup.querySelector('.update-text');

        if (acara) {
            elemen.modalTitle.style.display = 'none';
            updateText.textContent = 'Update';
            updateText.style.display = 'block';
            
            // Isi form dengan data acara
            elemen.inputJudul.value = acara.judul;
            elemen.inputDeskripsi.value = acara.deskripsi;
            elemen.inputWaktu.value = acara.waktu.substring(0,5); // ambil jam:menit
            elemen.tombolSimpan.dataset.editId = acara.id;
        } else {
            // Mode tambah acara baru
            elemen.modalTitle.style.display = 'block';
            updateText.style.display = 'none';
            bersihkanForm();
            delete elemen.tombolSimpan.dataset.editId;
        }

        elemen.inputJudul.focus();
    }


    // Fungsi untuk menutup popup
    function tutupPopup() {
        elemen.popup.style.display = 'none';
        elemen.popup.classList.remove('modal-active');
        bersihkanForm();
    }

    // Fungsi untuk mengosongkan form
    function bersihkanForm() {
        elemen.inputJudul.value = '';
        elemen.inputDeskripsi.value = '';
        elemen.inputWaktu.value = '';
    }

    // Fungsi untuk berpindah bulan
    function pindahBulan(arah) {
        data.tanggalSekarang.setMonth(
            data.tanggalSekarang.getMonth() + (arah === 'prev' ? -1 : 1)
        );
        tampilkanKalender();
    }

    // Fungsi untuk menyimpan acara baru
    async function simpanAcara() {
        if (!elemen.inputJudul.value.trim() || !elemen.inputWaktu.value) {
            alert('Judul dan waktu acara harus diisi!');
            return;
        }

        const waktuAcara = `${data.tanggalDipilih} ${elemen.inputWaktu.value}:00`;
        
        try {
            const response = await fetch('add_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    judul_acara: elemen.inputJudul.value.trim(),
                    desc_acara: elemen.inputDeskripsi.value.trim(),
                    waktu_acara: waktuAcara
                })
            });

            if (!response.ok) {
                throw new Error('Gagal menyimpan acara');
            }

            tutupPopup();
            ambilAcaraDariDatabase();
            
        } catch (error) {
            alert('Maaf, ada masalah saat menyimpan acara. Silakan coba lagi.');
        }
    }

    // Fungsi untuk menghapus acara
    async function hapusAcara(idAcara) {
        if (confirm('Apakah Anda yakin ingin menghapus acara ini?')) {
            try {
                const response = await fetch('delete_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: idAcara })
                });

                if (!response.ok) {
                    throw new Error('Gagal menghapus acara');
                }

                ambilAcaraDariDatabase();
                
            } catch (error) {
                alert('Maaf, ada masalah saat menghapus acara. Silakan coba lagi.');
            }
        }
    }

    async function updateAcara(idAcara, judul, deskripsi, waktuAcara) {
        try {
            const response = await fetch('edit_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    id: idAcara, 
                    judul_acara: judul,
                    desc_acara: deskripsi,
                    waktu_acara: waktuAcara
                })
            });

            if (!response.ok) {
                throw new Error('Gagal mengupdate acara');
            }

        } catch (error) {
            alert('Maaf, ada masalah saat mengupdate acara. Silakan coba lagi.');
        }
    }


    // Fungsi untuk mengatur event listener
    function aturEventListeners() {
        // Tombol next dan previous bulan
        elemen.tombolNext.addEventListener('click', () => pindahBulan('next'));
        elemen.tombolPrev.addEventListener('click', () => pindahBulan('prev'));

        // Klik pada tanggal di kalender
        elemen.tampilanTanggal.addEventListener('click', (e) => {
            const kotakHari = e.target.closest('.tgl');
            if (kotakHari && !e.target.classList.contains('acara')) {
                bukaPopup(kotakHari.dataset.tanggal);
            }
        });

        elemen.daftarAcara.addEventListener('click', (e) => {
            const elemenAcara = e.target.closest('.acara');
            if (elemenAcara && !e.target.classList.contains('hapus')) {
                const idAcara = elemenAcara.dataset.idAcara;
                const acara = data.daftarAcara.find(a => a.id == idAcara);
                if (acara) {
                    bukaPopup(acara.tanggal, acara);
                }
            }
        });

        // Tombol tutup dan simpan di popup
        elemen.tombolTutup.addEventListener('click', tutupPopup);
        elemen.tombolSimpan.addEventListener('click', async (e) => {
        if (elemen.tombolSimpan.dataset.editId) {
            const idAcara = elemen.tombolSimpan.dataset.editId;
            const judul = elemen.inputJudul.value.trim();
            const deskripsi = elemen.inputDeskripsi.value.trim();
            const waktuAcara = `${data.tanggalDipilih} ${elemen.inputWaktu.value}:00`;

            await updateAcara(idAcara, judul, deskripsi, waktuAcara);
            tutupPopup();
            ambilAcaraDariDatabase();
        } else {
            simpanAcara();
        }
    });

        // Klik di luar popup untuk menutup
        elemen.popup.addEventListener('click', (e) => {
            if (e.target === elemen.popup) {
                tutupPopup();
            }
        });

        // Tombol hapus acara
        elemen.daftarAcara.addEventListener('click', (e) => {
            if (e.target.classList.contains('hapus')) {
                const elemenAcara = e.target.closest('.acara');
                if (elemenAcara) {
                    hapusAcara(elemenAcara.dataset.idAcara);
                }
            }
        });
    }

    // Fungsi untuk memulai kalender
    function mulaiKalender() {
        aturEventListeners();
        ambilAcaraDariDatabase();
    }

    // Jalankan kalender
    mulaiKalender();
});