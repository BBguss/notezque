document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const nextbtn = document.querySelector('.next');
    const tampilBulanTahun = document.querySelector('.tanggal');
    const prevbtn = document.querySelector('.prev');
    const kontainerHari = document.querySelector('.harian');
    const popupAcara = document.getElementById('eventPopup');
    const judulAcara = document.getElementById('eventTitle');
    const deskripsiAcara = document.getElementById('eventDesc');
    const tutupPopup = document.querySelector('.close-popup');

    const namaBulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    let sekarang = new Date();
    let bulanSekarang = sekarang.getMonth();
    let tahunSekarang = sekarang.getFullYear();

    // Function to fetch events from database
    async function ambilAcara() {
        try {
            const response = await fetch(`../dashboard/get_events.php?bulan=${bulanSekarang + 1}&tahun=${tahunSekarang}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            
            const data = await response.json();
            
            const acaraFormat = {};
            if (data && typeof data === 'object') {
                for (const tanggalStr in data) {
                    const bagianTanggal = tanggalStr.split('-');
                    if (bagianTanggal.length === 3) {
                        const hari = parseInt(bagianTanggal[2]);
                        acaraFormat[hari] = data[tanggalStr];
                    }
                }
            }
            
            return acaraFormat;
        } catch (error) {
            console.error('Error:', error);
            return {};
        }
    }

function tampilkanTooltip(element) {
    sembunyikanTooltip();
    
    // Create and position new tooltip
    const tooltip = document.createElement('div');
    tooltip.className = 'event-tooltip';
    tooltip.textContent = element.dataset.judul || '';
    document.body.appendChild(tooltip);
    
    const dotRect = element.getBoundingClientRect();
    tooltip.style.left = `${dotRect.left + window.scrollX}px`;
    tooltip.style.top = `${dotRect.top + window.scrollY - 30}px`;
}

function sembunyikanTooltip() {
    const tooltip = document.querySelector('.event-tooltip');
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
        
        kontainerHari.innerHTML = '';
        
        // Previous month days
        for (let i = offsetHari; i > 0; i--) {
            const tgl = hariTerakhirBulanLalu.getDate() - i + 1;
            const elemenHari = document.createElement('div');
            elemenHari.className = 'hari hariSebelum';
            elemenHari.textContent = tgl;
            kontainerHari.appendChild(elemenHari);
        }
        
        // Current month days
        for (let tgl = 1; tgl <= totalHari; tgl++) {
            const elemenHari = document.createElement('div');
            
            const isHariIni = tgl === sekarang.getDate() && 
                            bulanSekarang === sekarang.getMonth() && 
                            tahunSekarang === sekarang.getFullYear();
            
            const adaAcara = acara[tgl] && acara[tgl].length > 0;
            
            elemenHari.className = `hari ${isHariIni ? 'hari-ini' : ''}`;
            elemenHari.textContent = tgl;
            elemenHari.dataset.tgl = tgl;
            
            if (adaAcara) {
                const titikContainer = document.createElement('div');
                titikContainer.className = 'titik-container';
                
                const jumlahTitik = Math.min(acara[tgl].length, 3);
                const warnaTitik = ['#ea4335', '#4285f4', '#34a853'];
                
                for (let i = 0; i < jumlahTitik; i++) {
                    const titikAcara = document.createElement('span');
                    titikAcara.className = 'titik-event';
                    titikAcara.style.backgroundColor = warnaTitik[i];
                    
                    if (acara[tgl][i] && acara[tgl][i].judul) {
                        titikAcara.dataset.judul = acara[tgl][i].judul;
                    }
                    
                    titikAcara.addEventListener('mouseenter', (e) => tampilkanTooltip(titikAcara, e));
                    titikAcara.addEventListener('mouseleave', sembunyikanTooltip);
                    titikAcara.addEventListener('mousemove', (e) => e.stopPropagation());
                    
                    titikContainer.appendChild(titikAcara);
                }
                
                elemenHari.appendChild(titikContainer);
            }
            
            kontainerHari.appendChild(elemenHari);
        }
        
        // Next month days
        const sisaHari = 42 - (offsetHari + totalHari);
        for (let tgl = 1; tgl <= sisaHari; tgl++) {
            const elemenHari = document.createElement('div');
            elemenHari.className = 'hari hariSetelah';
            elemenHari.textContent = tgl;
            kontainerHari.appendChild(elemenHari);
        }
    }

    // Event listeners for month navigation
    nextbtn.addEventListener('click', () => {
        bulanSekarang = (bulanSekarang + 1) % 12;
        if (bulanSekarang === 0) tahunSekarang++;
        renderKalender();
    });

    prevbtn.addEventListener('click', () => {
        bulanSekarang = (bulanSekarang - 1 + 12) % 12;
        if (bulanSekarang === 11) tahunSekarang--;
        renderKalender();
    });

    // Initial render
    renderKalender();
});