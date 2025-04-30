// ATTR SIDE NAV

const sideNav = document.querySelector('.sideNav-db');
const buka = document.getElementById('tombol');
const main = document.getElementById('main');
const tutup = document.getElementById('batal');
const footer = document.getElementById('footer')

buka.addEventListener("click", () => {
    sideNav.style.width = "200px";
    main.style.marginLeft = "200px";
    footer.style.marginLeft = "200px";
    footer.style.transition = "all .5s ease";
});

tutup.addEventListener("click", () => {
    main.style.marginLeft = "0";
    footer.style.marginLeft = "0";
});
