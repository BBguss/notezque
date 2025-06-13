/**
 * Kelola Konten JavaScript Module
 * Professional content management system
 */

class ContentManager {
    constructor() {
        this.modal = document.getElementById('contentModal');
        this.form = document.getElementById('contentForm');
        this.initializeEventListeners();
        this.initializeTooltips();
    }

    initializeEventListeners() {
        // Modal close events
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.style.display === 'block') {
                this.closeModal();
            }
        });

        // Click outside modal to close
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.closeModal();
            }
        });

        // Form validation
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                if (!this.validateForm()) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            this.form.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldValidation(input));
            });
        }

        // Auto-hide alerts
        this.initializeAlerts();
    }

    initializeTooltips() {
        // Initialize tooltips for action buttons
        document.querySelectorAll('[title]').forEach(element => {
            element.addEventListener('mouseenter', (e) => {
                this.showTooltip(e.target, e.target.getAttribute('title'));
            });
            element.addEventListener('mouseleave', () => {
                this.hideTooltip();
            });
        });
    }

    showTooltip(element, text) {
        const existing = document.querySelector('.custom-tooltip');
        if (existing) existing.remove();

        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 10000;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(-50%);
        `;

        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + rect.width / 2 + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';

        // Animation
        tooltip.style.opacity = '0';
        tooltip.style.transform = 'translateX(-50%) translateY(10px)';
        setTimeout(() => {
            tooltip.style.transition = 'all 0.2s ease';
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateX(-50%) translateY(0)';
        }, 10);
    }

    hideTooltip() {
        const tooltip = document.querySelector('.custom-tooltip');
        if (tooltip) {
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateX(-50%) translateY(10px)';
            setTimeout(() => tooltip.remove(), 200);
        }
    }

    openModal(mode, data = null) {
        if (!this.modal) return;

        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const modalIcon = title.querySelector('.modal-icon');

        // Reset form and validation
        this.form.reset();
        this.clearAllValidation();

        if (mode === 'add') {
            title.innerHTML = '<i class="fas fa-plus modal-icon"></i>Tambah Konten Baru';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            submitBtn.name = 'tambah';
            submitBtn.className = 'btn btn-primary';
            document.getElementById('contentId').value = '';
        } else if (mode === 'edit' && data) {
            title.innerHTML = '<i class="fas fa-edit modal-icon"></i>Edit Konten';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Perbarui';
            submitBtn.name = 'edit';
            submitBtn.className = 'btn btn-primary';

            // Fill form with data
            this.populateForm(data);
        }

        // Show modal with animation
        this.modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        // Focus first input
        setTimeout(() => {
            const firstInput = this.form.querySelector('.form-control');
            if (firstInput) firstInput.focus();
        }, 300);
    }

    closeModal() {
        if (!this.modal) return;

        const modalContent = this.modal.querySelector('.modal-content');
        
        // Animation
        modalContent.style.transform = 'translateY(-20px)';
        modalContent.style.opacity = '0';
        this.modal.style.opacity = '0';

        setTimeout(() => {
            this.modal.style.display = 'none';
            this.modal.style.opacity = '1';
            modalContent.style.transform = 'translateY(0)';
            modalContent.style.opacity = '1';
            document.body.style.overflow = 'auto';
        }, 300);

        this.clearAllValidation();
    }

    populateForm(data) {
        const fields = ['contentId', 'namaHalaman', 'section', 'deskripsi', 'gambar', 'keterangan'];
        const mapping = {
            'contentId': 'id_konten',
            'namaHalaman': 'nama_halaman',
            'section': 'section',
            'deskripsi': 'deskripsi',
            'gambar': 'gambar',
            'keterangan': 'keterangan'
        };

        fields.forEach(fieldId => {
            const element = document.getElementById(fieldId);
            const dataKey = mapping[fieldId];
            if (element && data[dataKey] !== undefined) {
                element.value = data[dataKey] || '';
            }
        });
    }

    validateForm() {
        const requiredFields = [
            { id: 'namaHalaman', name: 'Nama Halaman' }
        ];

        let isValid = true;

        requiredFields.forEach(field => {
            const input = document.getElementById(field.id);
            if (!input.value.trim()) {
                this.showFieldError(input, `${field.name} harus diisi`);
                isValid = false;
            } else {
                this.showFieldSuccess(input);
            }
        });

        // Validate image file if provided
        const gambarInput = document.getElementById('gambar');
        if (gambarInput.value.trim()) {
            const validExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp'];
            const hasValidExtension = validExtensions.some(ext => 
                gambarInput.value.toLowerCase().endsWith(ext)
            );
            
            if (!hasValidExtension) {
                this.showFieldError(gambarInput, 'Format file tidak valid (jpg, png, gif, svg, webp)');
                isValid = false;
            } else {
                this.showFieldSuccess(gambarInput);
            }
        }

        return isValid;
    }

    validateField(input) {
        const value = input.value.trim();
        
        if (input.hasAttribute('required') && !value) {
            this.showFieldError(input, 'Field ini harus diisi');
            return false;
        }

        if (input.id === 'gambar' && value) {
            const validExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp'];
            const hasValidExtension = validExtensions.some(ext => 
                value.toLowerCase().endsWith(ext)
            );
            
            if (!hasValidExtension) {
                this.showFieldError(input, 'Format file tidak valid');
                return false;
            }
        }

        this.showFieldSuccess(input);
        return true;
    }

    showFieldError(input, message) {
        this.clearFieldValidation(input);
        
        input.classList.add('is-invalid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i>${message}`;
        
        input.parentNode.appendChild(feedback);
    }

    showFieldSuccess(input) {
        this.clearFieldValidation(input);
        input.classList.add('is-valid');
    }

    clearFieldValidation(input) {
        input.classList.remove('is-invalid', 'is-valid');
        const feedback = input.parentNode.querySelector('.invalid-feedback, .valid-feedback');
        if (feedback) feedback.remove();
    }

    clearAllValidation() {
        if (!this.form) return;
        
        this.form.querySelectorAll('.form-control').forEach(input => {
            this.clearFieldValidation(input);
        });
    }

    initializeAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Add close button
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.className = 'btn-close';
            closeBtn.style.cssText = `
                background: none;
                border: none;
                font-size: 1rem;
                cursor: pointer;
                padding: 0;
                margin-left: auto;
                color: inherit;
                opacity: 0.7;
            `;
            closeBtn.onclick = () => this.hideAlert(alert);
            alert.appendChild(closeBtn);

            // Auto-hide after 5 seconds
            setTimeout(() => this.hideAlert(alert), 5000);
        });
    }

    hideAlert(alert) {
        if (!alert || !alert.parentNode) return;
        
        alert.style.transition = 'all 0.5s ease-out';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            if (alert.parentNode) alert.remove();
        }, 500);
    }

    confirmDelete(id, name) {
        return new Promise((resolve) => {
            const result = confirm(
                `Apakah Anda yakin ingin menghapus konten "${name}"?\n\nTindakan ini tidak dapat dibatalkan.`
            );
            resolve(result);
        });
    }

    // Search functionality
    initializeSearch() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Auto-submit search after 500ms
                    if (searchInput.value.length >= 3 || searchInput.value.length === 0) {
                        searchInput.closest('form').submit();
                    }
                }, 500);
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.contentManager = new ContentManager();
    
    // Initialize search
    window.contentManager.initializeSearch();
});

// Global functions for backward compatibility
function openModal(mode, data = null) {
    window.contentManager.openModal(mode, data);
}

function closeModal() {
    window.contentManager.closeModal();
}

function editContent(data) {
    window.contentManager.openModal('edit', data);
}

// Enhanced delete function
function deleteContent(id, name) {
    window.contentManager.confirmDelete(id, name).then(confirmed => {
        if (confirmed) {
            window.location.href = `?hapus=${id}`;
        }
    });
}

// Utility functions
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const msg = document.createElement('div');
        msg.textContent = 'Teks berhasil disalin!';
        msg.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 3000);
    });
}