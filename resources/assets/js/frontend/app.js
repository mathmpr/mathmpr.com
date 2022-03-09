require('../bootstrap');
window.Cookies = require('../cookie');
window.jQuery = require('jquery');
window.$ = window.jQuery;
require('owl.carousel');

makeid = (length) => {
    length = length || 8;
    let result = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}

let domReady = () => {

    let scripts = document.querySelector('#scripts');
    if (scripts) {
        scripts.content.querySelectorAll('*').forEach((el) => {
            let clone = el.cloneNode(true);
            if(clone.classList.contains('on-ready')) {
                document.head.appendChild(clone);
            }
        });
    }

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

    document.querySelector('#header .col span:last-child').addEventListener('click', (event) => {
        event.target.previousElementSibling.classList.toggle('show');
        if (event.target.previousElementSibling.classList.contains('show')) {
            document.querySelector('main').style.position = 'fixed';
            document.querySelector('main').style.width = '100%';
        } else {
            document.querySelector('main').style.position = 'relative';
        }
    });

    let resizeCard = () => {
        document.querySelectorAll('.home img + a').forEach((element) => {
            if (!element.previousElementSibling.onload) {
                if (element.previousElementSibling.height > 0) {
                    element.previousElementSibling.loaded = true;
                }
                element.previousElementSibling.onload = () => {
                    element.previousElementSibling.loaded = true;
                    element.style.height = element.previousElementSibling.height + 'px';
                }
            }
            if (element.previousElementSibling.loaded) {
                element.style.height = element.previousElementSibling.height + 'px';
                if (element.parentNode.lastElementChild.nodeName !== 'A') {
                    element.parentNode.lastElementChild.style.width = (element.previousElementSibling.width - 1) + 'px';
                    element.parentNode.lastElementChild.style.height = element.previousElementSibling.height + 'px';
                }
            }
        });
    };

    resizeCard();

    window.addEventListener('resize', () => {
        resizeCard();
    });

    let activeDiv;

    document.querySelector('body').addEventListener('mousemove', (event) => {
        if (activeDiv) {
            if (!event.target.closest('[data-src]')) {
                activeDiv.appendVideo();
                if (activeDiv.lastElementChild.nodeName !== 'A') {
                    activeDiv.video.pause();
                    activeDiv.lastElementChild.classList.toggle('show');
                }
                clearInterval(activeDiv.int);
                activeDiv = false;
            }
        }
    });

    document.querySelectorAll('[data-src]').forEach((div) => {

        div.int = 0;

        div.appendVideo = () => {
            if (div.video) {
                div.video.play();
            }
            // one second
            if (div.interest >= 2) {
                clearInterval(div.int);
                div.interest = 'end';
                let video = document.createElement('video');
                let src = document.createElement('source');
                video.autoplay = true;
                video.muted = true;
                video.controls = false;
                video.classList.add('video-js');
                video.setAttribute('preload', 'auto');
                video.setAttribute('data-setup', '{}');
                video.id = '_' + makeid();
                src.src = div.getAttribute('data-src');
                src.type = 'video/mp4';
                video.appendChild(src);
                div.appendChild(video);
                div.video = video;
                if (window.videojs) {
                    div.video = videojs(video.id);
                }
                div.lastElementChild.style.width = (div.firstElementChild.width - 1) + 'px';
                div.lastElementChild.style.height = div.firstElementChild.height + 'px';
                setTimeout(() => {
                    div.lastElementChild.classList.toggle('show');
                }, 200);
            }
        }

        div.addEventListener('mouseenter', () => {
            div.appendVideo();

            activeDiv = div;
            if (div.lastElementChild.nodeName !== 'A') {
                div.lastElementChild.classList.toggle('show');
            }
            div.int = setInterval(() => {
                if (div.interest === 'end') return;
                if (!div.interest) {
                    div.interest = 1;
                } else {
                    div.interest++;
                }
                div.appendVideo();
            }, 500);
        });
    });
}

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    domReady();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        domReady();
    });
}


