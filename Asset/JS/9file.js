 // Modal Functions
 function openModal() {
    document.getElementById('fileModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('fileModal').classList.remove('show');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('fileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Confirm Delete
function confirmDelete(fileId, fileName) {
    if (confirm(`Yakin ingin menghapus file "${fileName}"?`)) {
        window.location.href = `?delete_id=${fileId}&folder_id=<?= $folder_id ?>`;
    }
}

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Add stagger animation to file cards
    const fileCards = document.querySelectorAll('.file-card');
    fileCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// File upload preview
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert('File terlalu besar! Maksimal 10MB.');
            e.target.value = '';
        }
    }
});