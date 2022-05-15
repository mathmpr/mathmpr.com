//import {CodeMirror} from "codemirror/src/edit/CodeMirror";

require('../bootstrap');
window.Cookies = require('../cookie');
window.jQuery = require('jquery');
window.$ = window.jQuery;
require('jquery-ui/ui/widgets/draggable');
require('jquery-ui/ui/widgets/sortable');
require('bootstrap/dist/js/bootstrap.min')

CodeMirror = require('codemirror/lib/codemirror')

require('codemirror/mode/javascript/javascript');
require('codemirror/mode/xml/xml');
require('codemirror/mode/css/css');
require('codemirror/mode/clike/clike');
require('codemirror/mode/sql/sql');
require('codemirror/mode/perl/perl');
require('codemirror/mode/markdown/markdown');
require('codemirror/mode/python/python');
require('codemirror/mode/yaml/yaml');
require('codemirror/mode/stylus/stylus');
require('codemirror/mode/sass/sass');
require('codemirror/mode/htmlmixed/htmlmixed');
require('codemirror/mode/php/php');
require('codemirror/mode/twig/twig');

require('codemirror/addon/edit/closebrackets')

window.EditorJS = require('@editorjs/editorjs');
window.Link = require('@editorjs/link');
window.List = require('@editorjs/list');
window.Header = require('@editorjs/header');
window.Underline = require('@editorjs/underline');
window.Table = require('@editorjs/table');
window.Quote = require('@editorjs/quote');
window.Image = require('@editorjs/image');
window.Delimiter = require('@editorjs/delimiter');

window.CodeMirror = CodeMirror;
window._mime = require('mime-db');

window.getMimeType = (extension) => {
    for (let i in _mime) {
        if (_mime[i].extensions) {
            for (let j in _mime[i].extensions) {
                if (_mime[i].extensions[j] === extension) return i;
            }
        }
    }
    return false;
};

let domReady = () => {

    document.querySelector('header span.toggle').addEventListener('click', () => {
        document.querySelector('header').classList.toggle('open');
    });

}

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    domReady();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        domReady();
    });
}


