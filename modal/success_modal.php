<?php
/**
 * Modal Berhasil Aksi
 * 
 * @param string $title Judul modal
 * @param string $message Pesan yang ditampilkan
 * @param string $redirect_url URL untuk redirect (opsional)
 * @param int $auto_close_delay Waktu auto close dalam milidetik (0 untuk non-auto close)
 */
function showSuccessModal($title = "Berhasil!", $message = "Aksi berhasil dilakukan.", $redirect_url = null, $auto_close_delay = 3000)
{
    ?>
    <div id="successModal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-modal-header">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3><?= htmlspecialchars($title) ?></h3>
            </div>
            <div class="success-modal-body">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
            <div class="success-modal-footer">
                <button id="successModalClose" class="success-modal-button">Tutup</button>
            </div>
        </div>
    </div>

    <style>
        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .success-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .success-modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .success-modal.active .success-modal-content {
            transform: translateY(0);
        }

        .success-modal-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .success-icon {
            margin-bottom: 15px;
        }

        .success-modal-header h3 {
            margin: 0;
            color: #2d3748;
            font-size: 1.5rem;
        }

        .success-modal-body {
            padding: 20px;
            text-align: center;
        }

        .success-modal-body p {
            margin: 0;
            color: #4a5568;
            line-height: 1.6;
        }

        .success-modal-footer {
            padding: 15px 20px;
            display: flex;
            justify-content: center;
            border-top: 1px solid #eee;
        }

        .success-modal-button {
            background-color: #4BB543;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .success-modal-button:hover {
            background-color: #3fa237;
        }

        @media (max-width: 480px) {
            .success-modal-content {
                width: 95%;
            }
        }
    </style>

    <script>
        // Tampilkan modal
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('successModal');
            modal.classList.add('active');

            // Tutup modal ketika tombol close diklik
            document.getElementById('successModalClose').addEventListener('click', function () {
                closeModal();
            });

            // Auto close jika delay di-set
            <?php if ($auto_close_delay > 0): ?>
                setTimeout(function () {
                    closeModal();
                }, <?= $auto_close_delay ?>);
            <?php endif; ?>

            function closeModal() {
                modal.classList.remove('active');
                setTimeout(function () {
                    modal.remove();
                    <?php if ($redirect_url): ?>
                        window.location.href = '<?= $redirect_url ?>';
                    <?php endif; ?>
                }, 300);
            }
        });
    </script>
    <?php
}
?>