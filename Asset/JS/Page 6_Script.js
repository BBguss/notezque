// =================================================
//                  Sistem Kalender
// =================================================
document.addEventListener('DOMContentLoaded', function() {
    // Elemen DOM dari HTML
    const elements = {
        nextBtn: document.querySelector('.next'),
        prevBtn: document.querySelector('.prev'),
        monthYearEl: document.querySelector('.bln-thn'),
        daysEl: document.querySelector('.tglBln'),
        sideListEl: document.querySelector('.side-listAcara'),
        modalEl: document.getElementById('modal'),
        formEl: document.querySelector('.form'),
        closeBtn: document.getElementById('closeBtn'),
        saveBtn: document.getElementById('save'),
        eventTitle: document.getElementById('title'),
        eventDesc: document.getElementById('desk'),
        eventTime: document.getElementById('tenggat'),
        bukaDetail: document.querySelector('.bukaDetail'),
        tutupDetail: document.querySelector('.tutupDetail')
    };

    // State kalender
    const state = {
        currentDate: new Date(),
        selectedDate: null,
        events: JSON.parse(localStorage.getItem('calendarEvents')) || [],
        monthNames: [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ],
        eventColors: [
            '#295F98', '#114B5F', '#1A936F', '#47663B', '#1A1A19', 
            '#003161', '#697565', '#1E201E', '#4C3BCF'
        ]
    };

    // Inisialisasi kalender
    function initCalendar() {
        renderCalendar();
        renderEventList();
        setupEventListeners();
        adjustCalendarCellHeights();
    }

    // Render kalender
    function renderCalendar() {
        elements.daysEl.innerHTML = '';
        const year = state.currentDate.getFullYear();
        const month = state.currentDate.getMonth();
        
        elements.monthYearEl.textContent = `${state.monthNames[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startDay = firstDay.getDay();
        const prevLastDay = new Date(year, month, 0).getDate();

        let dayCount = 1;
        let nextMonthDay = 1;

        // Render hari-hari dalam minggu
        for (let i = 0; i < 42; i++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'tgl';

            if (i < startDay) {
                // Hari dari bulan sebelumnya
                const prevDay = prevLastDay - (startDay - i - 1);
                dayEl.textContent = prevDay;
                dayEl.classList.add('tglSblm');
                dayEl.dataset.date = `${year}-${month + 1}-${prevDay}`;
            } 
            else if (dayCount <= daysInMonth) {
                // Hari di bulan ini
                dayEl.textContent = dayCount;
                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                dayEl.dataset.date = formattedDate;

                // Tandai hari ini
                if (dayCount === new Date().getDate() && 
                    month === new Date().getMonth() && 
                    year === new Date().getFullYear()) {
                    dayEl.classList.add('tglSkrg');
                }

                renderDayEvents(dayEl, formattedDate);
                dayCount++;
            } 
            else {
                // Hari dari bulan berikutnya
                dayEl.textContent = nextMonthDay;
                dayEl.classList.add('tglStlh');
                dayEl.dataset.date = `${year}-${month + 2}-${nextMonthDay}`;
                nextMonthDay++;
            }

            elements.daysEl.appendChild(dayEl);
        }
    }

    // Render acara untuk hari tertentu
    function renderDayEvents(dayEl, date) {
        const dayEvents = state.events.filter(event => event.date === date);
        dayEvents.forEach(event => {
            const eventEl = document.createElement('div');
            eventEl.className = 'acara';
            eventEl.innerHTML = `<h4>${event.title}</h4><p>${event.time}</p>`;
            eventEl.style.backgroundColor = event.color;
            eventEl.dataset.eventId = event.id;
            dayEl.appendChild(eventEl);
        });
    }

    // Render daftar acara di sidebar
    function renderEventList() {
        

        if (state.events.length === 0) {
            elements.sideListEl.innerHTML += '<p class="no-events" style="text-align: center">Tidak ada acara</p>';
            return;
        }

        const sortedEvents = [...state.events].sort((a, b) => {
            const dateCompare = new Date(a.date) - new Date(b.date);
            if (dateCompare !== 0) return dateCompare;
            return a.time.localeCompare(b.time);
        });

        sortedEvents.forEach(event => {
            const eventEl = document.createElement('div');
            eventEl.className = 'acara';
            eventEl.style.backgroundColor = event.color;
            eventEl.dataset.eventId = event.id;
            
            eventEl.innerHTML = `
                <h4>${event.title}</h4>
                <p class="event-desc">${event.desc || 'Tidak ada deskripsi'}</p>
                <p class="event-date">${formatEventDate(event.date)} pukul ${event.time}</p>
                <iconify-icon class="hapus" icon="mdi:trash-can-empty" width="24" height="24"></iconify-icon>
            `;

            elements.sideListEl.appendChild(eventEl);
        });
    }

    // Format tanggal untuk ditampilkan
    function formatEventDate(dateStr) {
        const [year, month, day] = dateStr.split('-');
        const date = new Date(year, month - 1, day);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    }

    // Navigasi bulan
    function navigateMonth(direction) {
        state.currentDate.setMonth(state.currentDate.getMonth() + (direction === 'prev' ? -1 : 1));
        renderCalendar();
    }

    // Buka modal
    function openModal(date) {
        state.selectedDate = date;
        elements.modalEl.style.display = 'flex';
        modal.classList.add('modal-active');
        elements.eventTitle.focus();
    }

    // Tutup modal
    function closeModal() {
        elements.modalEl.style.display = 'none';
        clearForm();
    }

    // Bersihkan form
    function clearForm() {
        elements.eventTitle.value = '';
        elements.eventDesc.value = '';
        elements.eventTime.value = '';
    }

    // Simpan acara
    function saveEvent() {
        if (!elements.eventTitle.value.trim() || !elements.eventTime.value) {
            alert('Harap isi judul dan waktu acara');
            return;
        }

        const newEvent = {
            id: Date.now().toString(),
            date: state.selectedDate,
            title: elements.eventTitle.value.trim(),
            desc: elements.eventDesc.value.trim(),
            time: elements.eventTime.value,
            color: state.eventColors[Math.floor(Math.random() * state.eventColors.length)]
        };

        state.events.push(newEvent);
        localStorage.setItem('calendarEvents', JSON.stringify(state.events));
        closeModal();
        renderCalendar();
        renderEventList();
    }

    // Hapus acara
    function deleteEvent(eventId) {
        if (confirm('Apakah Anda yakin ingin menghapus acara ini?')) {
            state.events = state.events.filter(event => event.id !== eventId);
            localStorage.setItem('calendarEvents', JSON.stringify(state.events));
            renderCalendar();
            renderEventList();
        }
    }

    // Toggle sidebar
    function toggleSidebar(show) {
        if (show) {
            elements.sideListEl.style.right = '0';
            elements.bukaDetail.style.right = '0px';
            elements.tutupDetail.style.right = '0px';
        } else {
            elements.sideListEl.style.right = '-32%';
            elements.bukaDetail.style.right = '0';
            elements.tutupDetail.style.right = '0';
        }
    }

    // Sesuaikan tinggi cell kalender
    function adjustCalendarCellHeights() {
        const calendarDays = document.querySelectorAll('.tgl');
        const cellHeight = window.innerWidth < 525 ? '100px' : '130px';
        calendarDays.forEach(day => day.style.height = cellHeight);
    }

    // Setup event listeners
    function setupEventListeners() {
        // Navigasi bulan
        elements.nextBtn.addEventListener('click', () => navigateMonth('next'));
        elements.prevBtn.addEventListener('click', () => navigateMonth('prev'));

        // Pilih tanggal
        elements.daysEl.addEventListener('click', (e) => {
            const dayEl = e.target.closest('.tgl');
            if (dayEl && !e.target.classList.contains('acara')) {
                openModal(dayEl.dataset.date);
            }
        });

        // Kontrol modal
        elements.closeBtn.addEventListener('click', closeModal);
        elements.saveBtn.addEventListener('click', saveEvent);

        // Tutup modal saat klik di luar
        elements.modalEl.addEventListener('click', (e) => {
            if (e.target === elements.modalEl) {
                closeModal();
            }
        });

        // Hapus acara
        elements.sideListEl.addEventListener('click', (e) => {
            if (e.target.classList.contains('hapus')) {
                const eventEl = e.target.closest('.acara');
                if (eventEl) {
                    deleteEvent(eventEl.dataset.eventId);
                }
            }
        });

        // Toggle sidebar (sesuai dengan HTML Anda)
        if (elements.bukaDetail && elements.tutupDetail) {
            elements.bukaDetail.addEventListener('click', () => {
                elements.sideListEl.style.right = '0';
                elements.bukaDetail.style.right = '0px';
                elements.tutupDetail.style.right = '0px';
            });
            
            elements.tutupDetail.addEventListener('click', () => {
                elements.sideListEl.style.right = '-32%';
                elements.bukaDetail.style.right = '0';
                elements.tutupDetail.style.right = '0';
            });
        }

        // Navigasi keyboard
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && elements.modalEl.style.display === 'flex') {
                closeModal();
            }
        });

        // Responsive adjustments
        window.addEventListener('resize', adjustCalendarCellHeights);
    }

    // Jalankan kalender
    initCalendar();
});