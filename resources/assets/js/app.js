require('./bootstrap');
window.Cookies = require('./cookie');

document.addEventListener('DOMContentLoaded', () => {

    document.querySelector('#readable').addEventListener('click', (event) => {
        if (event.target.classList.contains('fa-moon')) {
            Cookies.remove('skin');
            Cookies.set("skin", "dark");
            event.target.classList.remove('fa-moon');
            event.target.classList.add('fa-sun');
            document.querySelector('body').classList.add('dark');
        } else {
            Cookies.remove('skin');
            Cookies.set("skin", "default");
            event.target.classList.remove('fa-sun');
            event.target.classList.add('fa-moon');
            document.querySelector('body').classList.remove('dark');
        }
    });

});
