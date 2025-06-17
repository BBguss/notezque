/* filepath: c:\xampp\htdocs\Kelompok_3\asset\js\87tambahMateri.js */
(function() {
    var Dialog = function(element) {
        this.element = element;
        this.triggers = document.querySelectorAll('[aria-controls="'+this.element.getAttribute('id')+'"]');
        this.firstFocusable = null;
        this.lastFocusable = null;
        this.selectedTrigger = null;
        this.showClass = "dialog--is-visible";
        this.binding = false;
        initDialog(this);
    };

    function initDialog(dialog) {
        if ( dialog.triggers ) {
            for(var i = 0; i < dialog.triggers.length; i++) {
                dialog.triggers[i].addEventListener('click', function(event) {
                    event.preventDefault();
                    dialog.selectedTrigger = event.target;
                    showDialog(dialog);
                    initDialogEvents(dialog);
                });
            }
        }
        
        // listen to the openDialog event -> open dialog without a trigger button
        dialog.element.addEventListener('openDialog', function(event){
            if(event.detail) dialog.selectedTrigger = event.detail;
            showDialog(dialog);
            initDialogEvents(dialog);
        });

        // listen to the closeDialog event -> close dialog without a trigger button
        dialog.element.addEventListener('closeDialog', function(event){
            if(event.detail) dialog.selectedTrigger = event.detail;
            closeDialog(dialog);
        });
    };

    function showDialog(dialog) {
        dialog.element.classList.add(dialog.showClass);
        getFocusableElements(dialog);
        dialog.firstFocusable.focus();
        // wait for the end of transitions before moving focus
        dialog.element.addEventListener("transitionend", function cb(event) {
            if (dialog.firstFocusable) {
                dialog.firstFocusable.focus();
            }
            dialog.element.removeEventListener("transitionend", cb);
        });
        emitDialogEvents(dialog, 'dialogIsOpen');
    };

    function closeDialog(dialog) {
        dialog.element.classList.remove(dialog.showClass);
        dialog.firstFocusable = null;
        dialog.lastFocusable = null;
        if(dialog.selectedTrigger) dialog.selectedTrigger.focus();
        
        // Clear form when closing dialog
        const form = dialog.element.querySelector('form');
        if (form) {
            form.reset();
            // Remove image previews
            const previews = dialog.element.querySelectorAll('.image-preview');
            previews.forEach(preview => preview.remove());
        }
        
        // For edit dialog, redirect to clear URL parameters
        if (dialog.element.id === 'dialog-2') {
            // Remove edit parameter from URL
            const url = new URL(window.location);
            url.searchParams.delete('edit');
            window.history.replaceState({}, document.title, url.pathname + url.search);
            
            // Reload page to clear edit state
            setTimeout(() => {
                window.location.reload();
            }, 100);
        }
        
        //remove listeners
        cancelDialogEvents(dialog);
        emitDialogEvents(dialog, 'dialogIsClose');
    };
    
    function initDialogEvents(dialog) {
        //add event listeners
        dialog.binding = handleEvent.bind(dialog);
        dialog.element.addEventListener('keydown', dialog.binding);
        dialog.element.addEventListener('click', dialog.binding);
    };

    function cancelDialogEvents(dialog) {
        //remove event listeners
        dialog.element.removeEventListener('keydown', dialog.binding);
        dialog.element.removeEventListener('click', dialog.binding);
    };
    
    function handleEvent(event) {
        // handle events
        switch(event.type) {
            case 'click': {
                initClick(this, event);
                break;
            }
            case 'keydown': {
                initKeyDown(this, event);
                break;
            }
        }
    };
    
    function initKeyDown(dialog, event) {
        if( event.keyCode && event.keyCode == 27 || event.key && event.key == 'Escape' ) {
            //close dialog on esc
            closeDialog(dialog);
        } else if( event.keyCode && event.keyCode == 9 || event.key && event.key == 'Tab' ) {
            //trap focus inside dialog
            trapFocus(dialog, event);
        }
    };

    function initClick(dialog, event) {
        //close dialog when clicking on close button
        if( !event.target.closest('.js-dialog__close') ) return;
        event.preventDefault();
        closeDialog(dialog);
    };

    function trapFocus(dialog, event) {
        if( dialog.firstFocusable == document.activeElement && event.shiftKey) {
            //on Shift+Tab -> focus last focusable element when focus moves out of dialog
            event.preventDefault();
            dialog.lastFocusable.focus();
        }
        if( dialog.lastFocusable == document.activeElement && !event.shiftKey) {
            //on Tab -> focus first focusable element when focus moves out of dialog
            event.preventDefault();
            dialog.firstFocusable.focus();
        }
    };

    function getFocusableElements(dialog) {
        //get all focusable elements inside the dialog
        var allFocusable = dialog.element.querySelectorAll('[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex]:not([tabindex="-1"]), [contenteditable], audio[controls], video[controls], summary');
        getFirstVisible(dialog, allFocusable);
        getLastVisible(dialog, allFocusable);
    };

    function getFirstVisible(dialog, elements) {
        //get first visible focusable element inside the dialog
        for(var i = 0; i < elements.length; i++) {
            if( elements[i].offsetWidth || elements[i].offsetHeight || elements[i].getClientRects().length ) {
                dialog.firstFocusable = elements[i];
                return true;
            }
        }
    };

    function getLastVisible(dialog, elements) {
        //get last visible focusable element inside the dialog
        for(var i = elements.length - 1; i >= 0; i--) {
            if( elements[i].offsetWidth || elements[i].offsetHeight || elements[i].getClientRects().length ) {
                dialog.lastFocusable = elements[i];
                return true;
            }
        }
    };

    function emitDialogEvents(dialog, eventName) {
        var event = new CustomEvent(eventName, {detail: dialog.selectedTrigger});
        dialog.element.dispatchEvent(event);
    };

    //initialize the Dialog objects
    var dialogs = document.getElementsByClassName('js-dialog');
    if( dialogs.length > 0 ) {
        for( var i = 0; i < dialogs.length; i++) {
            (function(i){new Dialog(dialogs[i]);})(i);
        }
    }
}());

// Additional dialog management for edit modal
document.addEventListener('DOMContentLoaded', function() {
    // Handle close button for edit dialog specifically
    const editDialog = document.getElementById('dialog-2');
    if (editDialog) {
        const closeButtons = editDialog.querySelectorAll('.js-dialog__close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove edit parameter from URL and reload
                const url = new URL(window.location);
                url.searchParams.delete('edit');
                window.location.href = url.pathname + url.search;
            });
        });
    }
});

// Rest of your existing functions remain the same
function showFiles(folderId) {
    fetch(`get_files.php?id_folder=${folderId}`)
        .then(response => response.json())
        .then(files => {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';

            files.forEach(file => {
                const li = document.createElement('li');
                li.textContent = file.file_name;
                fileList.appendChild(li);
            });
        });
}

document.getElementById('add-file-form')?.addEventListener('submit', function(event) {
    event.preventDefault();

    const fileInput = document.getElementById('file-upload');
    const folderId = document.getElementById('folder-id').value;

    const formData = new FormData();
    formData.append('file_upload', fileInput.files[0]);
    formData.append('id_folder', folderId);

    fetch('add_file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('File berhasil diunggah!');
            location.reload();
        } else {
            alert('Gagal mengunggah file!');
        }
    });
});

function deleteFile(fileId) {
    if (confirm("Apakah Anda yakin ingin menghapus file ini?")) {
        fetch(`delete_file.php?action=delete&id_file=${fileId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('File berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Gagal menghapus file!');
                }
            });
    }
}

const notStarted = document.getElementById("not-started");

function jadwal() {
    const matkul = document.getElementById("matkul")?.value;
    const tugas = document.getElementById("tugas")?.value;
    const deadline1 = document.getElementById("deadline1")?.value;
    const deadline2 = document.getElementById("deadline2")?.value;
    const deskripsi = document.getElementById("deskripsi")?.value;

    if (matkul && tugas && deadline1 && deskripsi && deadline2) {
        const task = document.createElement("div");
        task.classList.add("task");
        task.innerHTML = `
        <div>
        <div class="task-header">
            <h3>${tugas}</h3>
        </div>
        <div class="deadline">
        <p><strong>Deadline :</strong> ${deadline1} [${deadline2}]</p>
        </div>
        <p><strong>Mata Kuliah :</strong> ${matkul}</p>
        <p><strong>Deskripsi Tugas :</strong> ${deskripsi}</p>
        </div>
        <iconify-icon class="delete-task" icon="material-symbols:cancel-outline-rounded" width="32" height="32" onclick="deleted(this)"></iconify-icon>
    `;

        if (notStarted) {
            notStarted.appendChild(task);
        }
        clear();
    } else {
        alert("Harap isi jadwal kuliah!");
    }
}

function clear() {
    const elements = ['matkul', 'tugas', 'deadline1', 'deadline2', 'deskripsi'];
    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.value = "";
    });
}

function deleted(button) {
    const task = button.parentElement;
    task.remove();
}