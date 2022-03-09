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
    console.log('ready');
}

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    domReady();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        domReady();
    });
}


