const next = document.querySelector('.next');
const bulanTh = document.querySelector('.tanggal');
const prev = document.querySelector('.prev');
const weeks = document.querySelector('.mingguan');
const tglBln = document.querySelector('.harian');

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

function isiKalender() {
    const BlnTglAwal = new Date(tahun, bulan, 1); 
    const BlnTglAkhir = new Date(tahun, bulan + 1, 0);  
    const prevLastDay = new Date(tahun, bulan, 0);  
    const prevDays = prevLastDay.getDate();  
    const tglAkhir = BlnTglAkhir.getDate();  
    const tglAwal = BlnTglAwal.getDay();  
    const hariStlh = 7-BlnTglAkhir.getDay()-1;  

    bulanTh.innerHTML = listBulan[bulan] + " " + tahun;

    let tgl = "";

    for (let x = tglAwal; x > 0; x--) {
        tgl += `<div class="hari hariSebelum" data-date="${prevDays - x + 1}">${prevDays - x + 1}</div>`;
    }

    for (let i = 1; i <= tglAkhir; i++) {
        if (i === new Date().getDate() && 
        tahun === new Date().getFullYear() && 
        bulan === new Date().getMonth()) {
            tgl += `<div class="hariini">${i}</div>`;
        } else {
            tgl += `<div class="hari" data-date="${i}">${i}</div>`;
        }
    }

    for (let j = 1; j <= hariStlh; j++) {
        tgl += `<div class="hari hariSetelah" data-date="${j}">${j}</div>`;
    }
    tglBln.innerHTML = tgl;
};

isiKalender();

next.addEventListener('click', () => {
    bulan++;
    if (bulan > 11) {
        bulan = 0;
        tahun++;
    }
    isiKalender();
});

prev.addEventListener('click', () => {
    bulan--;
    if (bulan < 0) {
        bulan = 11;
        tahun--;
    }
    isiKalender();
});

const cancelBtns = document.querySelectorAll('.cancel-btn');

cancelBtns.forEach(cancelBtn => {
    cancelBtn.addEventListener('click', (event) => {
        const tmatkul = event.target.parentElement;
        tmatkul.remove();
    });
});

