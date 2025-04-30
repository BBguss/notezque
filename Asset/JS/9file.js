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