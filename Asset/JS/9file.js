const tambahFolder = document.querySelector(".tambahFolder");
const iTambah = document.querySelector(".i-tambah");
const tambahFd = document.querySelector(".cd-btn");
const folderTugas = document.querySelector('.folderTugas');
const tbhtgs = document.querySelector('#formtbhtgs');
const buatfd = document.querySelector('.buat');
const batalfd = document.querySelector('.batal');
const modalbuatFd = document.querySelector('.modal-tbhtgs');
const modal = document.querySelector('.modal');


tambahFd.addEventListener('click', ()=> {
    modalbuatFd.style.visibility = "visible";
});

buatfd.addEventListener("click", ()=> {
    const formtbhtgs = tbhtgs.value;

    if (formtbhtgs) {
    const tambahFdTugas = document.createElement('div');
    tambahFdTugas.classList.add('folder');

    tambahFdTugas.innerHTML=`
    <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
    <h4 class="namfolder">${formtbhtgs}</h4>
    <div class="dropdown">
        <i class="dropdown-button" style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="24" height="24"></iconify-icon></i>
            <div class="dropdown-content">
                <a href="#" onclick="hapus()">Hapus</a>
                <a href="#" onclick="edit()">Edit</a>
            </div>
        </div>
    `;

    folderTugas.appendChild(tambahFdTugas);
    modalbuatFd.style.visibility = "hidden";

    formtbhtgs.value = " ";
    
    } else {
        alert("Wajid di isi yaa!!!")
    }
    
});

batalfd.addEventListener("click", () => {
    modalbuatFd.style.visibility = "hidden";
});


function hapus(event) {
    const folder = event.target.closest('.folder');
    folder.remove();
}

function edit(event) {
    const folder = event.target.closest('.folder');
    const folderName = folder.querySelector('.namfolder');
    const newName = prompt("Edit nama folder:", folderName.textContent);
    if (newName !== null && newName !== "") {
        folderName.textContent = newName;
    }
}

document.querySelectorAll('.folder').forEach(folder => {
    folder.querySelector('.dropdown-content a[href="#"]:nth-child(1)').addEventListener('click', function(event) {
        hapus(event);
    });
    folder.querySelector('.dropdown-content a[href="#"]:nth-child(2)').addEventListener('click', function(event) {
        edit(event);
    });
});

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
              if(event.detail) self.selectedTrigger = event.detail;
              showDialog(dialog);
              initDialogEvents(dialog);
          });
  
          // listen to the closeDialog event -> close dialog without a trigger button
          dialog.element.addEventListener('closeDialog', function(event){
              if(event.detail) self.selectedTrigger = event.detail;
              closeDialog(dialog);
          });
    };
  
    function showDialog(dialog) {
          dialog.element.classList.add(dialog.showClass);
      getFocusableElements(dialog);
          dialog.firstFocusable.focus();
          // wait for the end of transitions before moving focus
          dialog.element.addEventListener("transitionend", function cb(event) {
              dialog.firstFocusable.focus();
              dialog.element.removeEventListener("transitionend", cb);
          });
          emitDialogEvents(dialog, 'dialogIsOpen');
    };
  
    function closeDialog(dialog) {
          dialog.element.classList.remove(dialog.showClass);
          dialog.firstFocusable = null;
          dialog.lastFocusable = null;
          if(dialog.selectedTrigger) dialog.selectedTrigger.focus();
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
        }
        case 'keydown': {
          initKeyDown(this, event);
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


  const notStarted = document.getElementById("not-started");
  
  function jadwal() {
      const matkul = document.getElementById("matkul").value;
      const tugas = document.getElementById("tugas").value;
      const deadline1 = document.getElementById("deadline1").value;
      const deadline2 = document.getElementById("deadline2").value;
      const deskripsi = document.getElementById("deskripsi").value;
  
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
  
          notStarted.appendChild(task);
          clear();
      } else {
          alert("Harap isi jadwal kuliah!");
      }
  }
  
  function clear() {
      document.getElementById("matkul").value = "";
      document.getElementById("tugas").value = "";
      document.getElementById("deadline1").value = "";
      document.getElementById("deadline2").value = "";
      document.getElementById("deskripsi").value = "";
  }
  
  function deleted(button) {
      const task = button.parentElement;
      task.remove();
  }
