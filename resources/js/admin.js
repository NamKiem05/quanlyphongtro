const heaDer = document.querySelector('header');
const sideBar = document.querySelector('aside')
const mainContent = document.querySelector('.main');
const tagMenu = document.querySelector('#tagMenu');

tagMenu.addEventListener('click' , ()=>{
    heaDer.classList.toggle('active');
    sideBar.classList.toggle('active');
    mainContent.classList.toggle('active');
} )