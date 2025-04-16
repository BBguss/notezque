// =================================================
//                     kalender
// =================================================

const next = document.querySelector('.next');
const bulanTh = document.querySelector('.bln-thn');
const prev = document.querySelector('.prev');
const weeks = document.querySelector('.week');
const tglBln = document.querySelector('.tglBln');
const sideListAcara = document.querySelector('.side-listAcara');

let d = new Date();
let hariAwal = d.getDate();
let bulan = d.getMonth();
let tahun = d.getFullYear();

const listBulan = [
   "January",
   "February",
   "March",
   "April",
   "May",
   "June",
   "July",
   "August",
   "September",
   "October",
   "November",
   "December",
];


// ===========================================
//             Local Storage
// ===========================================

function hapusPenyimpananLokal() {
    localStorage.removeItem('listDataAcara');
}

function penyimpananLokal() {
    const acaraList = JSON.parse(localStorage.getItem("listDataAcara")) || [];

    acaraList.forEach(acara => {
        // nambahin elemen baru ke Acara
        const acaraItem = document.createElement('div');
        acaraItem.classList.add("acara");

        acaraItem.innerHTML = `
            <h4>${acara.judul}</h4>
            <p>${acara.waktu}</p>
        `;

        acaraItem.style.backgroundColor = acara.warna;

        const targetTgl = document.querySelector(`.tgl[data-date="${acara.tanggal}"]`);
        if (targetTgl) {
            targetTgl.appendChild(acaraItem);
        }

        // Nah ini ke sidebarnya
        const sidebarItem = document.createElement('div');
        sidebarItem.classList.add("acara");

        sidebarItem.innerHTML = `
            <h4>${acara.judul}</h4>
            <p>Deskripsi Acara: ${acara.deskripsi}</p>
            <p>Waktu: ${acara.waktu}</p>
            <iconify-icon class="hapus" icon="mdi:trash-can-empty" width="32" height="32"></iconify-icon>
        `;
        sidebarItem.style.backgroundColor = acara.warna;

        sideListAcara.appendChild(sidebarItem);
    });
}

function tambahAcaraKeSideList(acara) {
    const sidebarItem = document.createElement('div');
    sidebarItem.classList.add("acara");

    sidebarItem.innerHTML = `
        <h4>Judul Acara: ${acara.judul}</h4>
        <p>Deskripsi Acara: ${acara.deskripsi}</p>
        <p>Waktu: ${acara.waktu}</p>
        <iconify-icon class="hapus" icon="mdi:trash-can-empty" width="32" height="32"></iconify-icon>
    `;
    sidebarItem.style.backgroundColor = acara.warna;

    sideListAcara.appendChild(sidebarItem);
}

sideListAcara.addEventListener('click', (event) => {
    if (event.target.classList.contains("hapus")) {
        const acaraItem = event.target.parentElement;
        const judulAcara = acaraItem.querySelector('h4').innerText;

        const peringatan = confirm('Beneran Mau di Hapus??');
        if (peringatan) {
            // Hapus dari sidebar
            acaraItem.remove();

            let acaraList = JSON.parse(localStorage.getItem("listDataAcara")) || [];
            acaraList = acaraList.filter(acara => acara.judul !== judulAcara);
            localStorage.setItem("listDataAcara", JSON.stringify(acaraList));
            
        } else {
            alert('Ga jadi dihapus ternyata');
        }
    }
});



    //Kalendernya
function isiKalender() {
    const BlnTglAwal = new Date(tahun, bulan, 1); 
    const BlnTglAkhir = new Date(tahun, bulan + 1, 0);  
    const prevLastDay = new Date(tahun, bulan, 0);  
    const prevDays = prevLastDay.getDate();  
    const tglAkhir = BlnTglAkhir.getDate();  
    const tglAwal = BlnTglAwal.getDay();  
    const hariStlh = 7 - BlnTglAkhir.getDay()-1;  

    const modalform = document.querySelector('.modal');
    const forminput = document.querySelector("form");
    const keluartbl = document.getElementById("closeBtn");
    const simpan = document.querySelector('#save');

    bulanTh.innerHTML = listBulan[bulan] + " " + tahun;

    let tgl = "";

    for (let x = tglAwal; x > 0; x--) {
        tgl += `<div class="tgl tglSblm" data-date="${prevDays - x + 1}">${prevDays - x + 1}</div>`;
    }

    for (let i = 1; i <= tglAkhir; i++) {
        if (i === new Date().getDate() && 
        tahun === new Date().getFullYear() && 
        bulan === new Date().getMonth()) {
            tgl += `<div class="tgl tglSkrg" data-date="${i}">${i}</div>`;
        } else {
            tgl += `<div class="tgl" data-date="${i}">${i}</div>`;
        }
    }

    for (let j = 1; j <= hariStlh; j++) {
        tgl += `<div class="tgl tglStlh" data-date="${j}">${j}</div>`;
    }
    tglBln.innerHTML = tgl;
    penyimpananLokal();

    const allTgl = document.querySelectorAll('.tgl');
    const judulAcara = document.querySelector('#title');
    const deskripsiAcara = document.querySelector('#desk');
    const waktuAcara = document.querySelector('#tenggat');

    let tanggalDipilih = " ";

allTgl.forEach((pilihtgl) => {
    pilihtgl.addEventListener('click', (pilih) => {
        tanggalDipilih = pilihtgl.getAttribute('data-date');
        modalform.style.display = "block";
        forminput.style.top = "20px";

        // Close modal saat klik di luar area modal
        window.addEventListener("click", (keluar) => {
            if (keluar.target == modalform) {
                modalform.style.display = "none";
            }
        });

        keluartbl.addEventListener('click', (keluar) => {
            if (keluar.target == keluartbl) {
                modalform.style.display = "none";
            }
        });
    });
});

simpan.addEventListener("click", () => {
    const jdlAcr = judulAcara.value;
    const deskAcr = deskripsiAcara.value;
    const waktAcr = waktuAcara.value;

    if (jdlAcr && deskAcr && waktAcr) {
        const acaraItem = document.createElement('div');
        acaraItem.classList.add("acara");

        acaraItem.innerHTML = `
            <h4>${jdlAcr}</h4>
            <p>${waktAcr}</p>
        `;

        // Warna background random
        const warna = ['#295F98', '#114B5F', '#1A936F', '#47663B', '#1A1A19', '#003161', '#697565', '#1E201E', '#4C3BCF'];
        function warnaAcak() {
            const rumusAcak = Math.floor(Math.random() * warna.length);
            return warna[rumusAcak];
        }

        acaraItem.style.backgroundColor = warnaAcak();

        // Simpan data acara
        const dataAcara = {
            tanggal: tanggalDipilih,
            judul: jdlAcr,
            deskripsi: deskAcr,
            waktu: waktAcr,
            warna: acaraItem.style.backgroundColor
        };

        const listDataAcara = JSON.parse(localStorage.getItem('listDataAcara')) || [];
        listDataAcara.push(dataAcara);
        localStorage.setItem('listDataAcara', JSON.stringify(listDataAcara));

        const targetTgl = document.querySelector(`.tgl[data-date="${tanggalDipilih}"]`);
        if (judulAcara.value.trim() && deskripsiAcara.value.trim() && waktuAcara.value.trim()) {
            targetTgl.appendChild(acaraItem);
            modalform.style.display = "none";
        }

        tambahAcaraKeSideList(dataAcara);
        clear();
    } else {
        alert("Wajib di isi ya bro, tiga-tiganya");
    }
});

}

isiKalender();

function clear() {
    document.querySelector('#title').value="";
    document.querySelector('#desk').value="";
    document.querySelector('#tenggat').value="";
}

next.addEventListener('click', () => {
    bulan++;
    if (bulan > 11) {
        bulan = 0;
        tahun++;
    }
    hapusPenyimpananLokal();
    isiKalender();
});

prev.addEventListener('click', () => {
    bulan--;
    if (bulan < 0) {
        bulan = 11;
        tahun--;
    }
    hapusPenyimpananLokal();
    isiKalender();
});

//Buat buka-tutup side barnya 
const bukaDetail = document.querySelector('.bukaDetail'); 
const tutupDetail = document.querySelector('.tutupDetail');

tutupDetail.addEventListener("click", () => {
    sideListAcara.style.right = "-10px"; 
    bukaDetail.style.right = "0" 
    tutupDetail.style.right = "0"
});

bukaDetail.addEventListener("click", () => {
    sideListAcara.style.right = ""; 
    bukaDetail.style.right = "0px" 
    tutupDetail.style.right = "0px"
});