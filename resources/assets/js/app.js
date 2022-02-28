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

    document.querySelector('#header .search i ').addEventListener('click', (event) => {
        event.target.closest('.search').classList.toggle('show');
    });

    let resizeCard = () => {
        document.querySelectorAll('.home img + div').forEach((element) => {
            if (!element.previousElementSibling.onload) {
                element.previousElementSibling.onload = () => {
                    element.previousElementSibling.loaded = true;
                    element.style.height = element.previousElementSibling.height + 'px';
                }
            }
            if (element.previousElementSibling.loaded) {
                element.style.height = element.previousElementSibling.height + 'px';
            }
        });
    };

    resizeCard();

    window.addEventListener('resize', () => {
        resizeCard();
    });


});
