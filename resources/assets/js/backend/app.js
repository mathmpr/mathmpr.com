//import {CodeMirror} from "codemirror/src/edit/CodeMirror";

require('../bootstrap');
let commons = require('./commons');
for (let i in commons) {
    window[i] = commons[i];
}
window.Cookies = require('../cookie');
window.jQuery = require('jquery');
window.$ = window.jQuery;
require('jquery-ui/ui/widgets/draggable');
require('jquery-ui/ui/widgets/sortable');
require('bootstrap/dist/js/bootstrap.min')

window.CodeMirror = require('codemirror/lib/codemirror');

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
const {split} = require("lodash/string");

window.EditorJS = require('@editorjs/editorjs');
window.Link = require('@editorjs/link');
window.List = require('@editorjs/list');
window.Header = require('@editorjs/header');
window.Underline = require('@editorjs/underline');
window.Table = require('@editorjs/table');
window.Quote = require('@editorjs/quote');
window.Image = require('@editorjs/image');
window.Delimiter = require('@editorjs/delimiter');

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

    /**
     * menu control
     */
    $('header .main > ul li ul').each((index, el) => {
        $(el).parent().on('click', (event) => {
            event.preventDefault();
            if (!$(el).closest('li').hasClass('expanded')) {
                $(el).parent().find('i').removeClass('fa-chevron-right');
                $(el).parent().find('i').addClass('fa-chevron-down');
            } else {
                $(el).parent().find('i').removeClass('fa-chevron-down');
                $(el).parent().find('i').addClass('fa-chevron-right');
            }
            $(el).stop().slideToggle(150, () => {
                $(el).closest('li').toggleClass('expanded');
            });
            $(el).find('a').each((index, el) => {
                $(el).on('click', (event) => {
                    event.stopPropagation();
                })
            });
        });
    });

    window.trans = (key, translations = null) => {
        if (typeof key === 'string') {
            key = key.split('.');
        }
        if (translations == null && window.translations) {
            translations = window.translations;
        }
        if (translations) {
            let current = key.shift();
            if (translations[current]) {
                if (typeof translations === 'string') {
                    return translations[current];
                } else {
                    return window.trans(key, translations[current]);
                }
            }
            return translations;
        }
        return false;
    };

    $("#mathmpr-editor .module-selector li").draggable({
        helper: function () {
            let div = $('<div class="block"></div>');
            div.append($(this).clone());
            div.attr('data-module', $(this).attr('data-module'));
            return div;
        },
        appendTo: 'body',
        connectToSortable: ".overflow",
        start: function (event, ui) {
            this.cloned = $(ui.helper[0]);
            $(ui.helper[0]).addClass('dragging');
        },

        revert: function (event, ui) {
            $(this).data("uiDraggable").originalPosition = {
                top: $(this).offset().top,
                left: $(this).offset().left
            };
            return !event;
        }
    });

    $('.overflow').sortable({
        placeholder: 'block-placeholder',
        handle: '.sort',
        start: function (event, ui) {
            if (ui.item.hasClass('rendered')) {
                ui.item.addClass('sorting');
            }
        },
        stop: function (event, ui) {
            ui.item.removeClass('sorting');
        },
        receive: function (event, ui) {

            let module = ui.item[0].cloned.attr('data-module');
            let cloned = ui.item[0].cloned;
            let template = $('[data-module-name="' + module + '"]');

            cloned.removeClass('dragging');
            cloned.addClass('rendered');
            cloned.find('li').remove();

            if (template.length > 0 && template[0].content && template[0].content.firstElementChild) {
                let clone = template[0].content.firstElementChild.cloneNode(true);
                let options = {};

                cloned.append(document.querySelector('#controls').content.firstElementChild.cloneNode(true));
                cloned.append(clone);

                cloned.find('ul li.delete').on('click', (event) => {
                    let self = $(event.delegateTarget);
                    if (self.hasClass('really-delete')) {
                        cloned.animate({
                            opacity: 0
                        }, 400, () => {
                            cloned.remove();
                        });
                    }
                    self.addClass('really-delete');
                }).on('mouseout', (event) => {
                    if (!$(event.relatedTarget).closest('li').is(event.delegateTarget)) {
                        $(event.delegateTarget).removeClass('really-delete');
                    }
                });

                cloned.editor = eval("new " + capitalize(module) + "(clone, options)");

            }
        }
    });

    window.MediaLibrary = require('./plugins/media-library');


}

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    domReady();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        domReady();
    });
}


