    // Function to open the modal
    function openModal() {
        document.getElementById('modal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    // Function to handle form submission
    function submitForm(event) {
        event.preventDefault(); // Mencegah form submit default

        // Ambil input dari form
        const imageInput = document.getElementById('image');
        const subjectInput = document.getElementById('subject');
        const dosenInput = document.getElementById('dosen');
        
        const imageFile = imageInput.files[0];
        const subject = subjectInput.value;
        const dosen = dosenInput.value;
        
        // Membaca gambar dan menampilkan di result container
        const reader = new FileReader();
        reader.onload = function(e) {
            const resultContainer = document.getElementById('result');

            // Membuat elemen hasil
            const resultCard = document.createElement('div');
            resultCard.classList.add('result-card');

            // Menambahkan gambar ke resultCard
            const img = document.createElement('img');
            img.src = e.target.result; // Gambar yang dibaca
            resultCard.appendChild(img);

            // Menambahkan nama mata kuliah ke resultCard
            const p = document.createElement('h3');
            p.textContent = subject;
            resultCard.appendChild(p);
            
            // Menambahkan nama mata kuliah ke resultCard
            const p1 = document.createElement('p');
            p1.textContent = dosen;
            resultCard.appendChild(p1);

            // Menambahkan resultCard ke container
            resultContainer.appendChild(resultCard);
        };

        // Membaca file gambar
        reader.readAsDataURL(imageFile);

        // Menutup modal setelah submit
        closeModal();

        // Reset form
        document.getElementById('form').reset();
    }