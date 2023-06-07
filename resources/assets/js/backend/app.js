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

window.apiCall = async (options = {}) => {
    return backendCall({
        ...options,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
        },
    });
}

window.backendCall = (options = {}) => {
    return new Promise((resolve, reject) => {
        $.ajax({
            ...options,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
            },
            success: (response) => {
                resolve(response);
            },
            error: (error) => {
                resolve(error);
            }
        });
    });
}

let domReady = async () => {

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

    let editor = $("#mathmpr-editor");

    if (editor.length > 0) {

        let addEvents = () => {
            editor.find(".module-selector li").draggable({
                helper: function () {
                    let div = $('<div class="block"></div>');
                    div.append($(this).clone(true));
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

            editor.find(".overflow").sortable({
                placeholder: 'block-placeholder',
                handle: '.sort',
                start: function (event, ui) {
                    if (ui.item.hasClass('rendered')) {
                        ui.item.addClass('sorting');
                    }
                },
                stop: function (event, ui) {
                    ui.item.removeClass('sorting');
                    let item = ui.item;
                    let index = Array.from(item.get(0).parentNode.children).indexOf(item.get(0));
                    let itemId = parseInt(item.attr('data-id'));
                    let oldIndex = parseInt(item.attr('data-order'));
                    apiCall({
                        url: window.editorUrl + '/' + window.editorObjectSlug + '/reorder',
                        method: 'POST',
                        data: {
                            content_id: item.attr('data-id'),
                            post_id: window.editorObjectId,
                            old_index: item.attr('data-order'),
                            new_index: index,
                        }
                    }).then(() => {
                        item.parent().find('.block').each((i, el) => {
                            el = $(el);
                            let currentIndex = parseInt(el.attr('data-order'));
                            let currentId = parseInt(el.attr('data-id'));
                            if (itemId !== currentId) {
                                if (index > oldIndex) {
                                    if (currentIndex >= oldIndex && currentIndex <= index) {
                                        el.attr('data-order', currentIndex - 1);
                                    }
                                } else {
                                    if (currentIndex >= index && currentIndex <= oldIndex) {
                                        el.attr('data-order', currentIndex + 1);
                                    }
                                }
                            } else {
                                el.attr('data-order', index);
                            }
                        });
                    });
                },
                receive: function (event, ui) {
                    let module = ui.item[0].cloned.attr('data-module');
                    let cloned = ui.item[0].cloned;
                    let template = $('[data-module-name="' + module + '"]');
                    let options = ui.item[0].options ? ui.item[0].options : {};

                    cloned.removeClass('dragging');
                    cloned.addClass('rendered');
                    cloned.find('li').remove();

                    if (template.length > 0 && template[0].content && template[0].content.firstElementChild) {
                        let clone = template[0].content.firstElementChild.cloneNode(true);

                        cloned.append(document.querySelector('#controls').content.firstElementChild.cloneNode(true));
                        cloned.append(clone);
                        cloned.editor = eval("new " + capitalize(module) + "(clone, options)");
                    }
                }
            });
        }

        let id = editor.attr('data-id');
        let url = editor.attr('data-url');
        window.editorObjectSlug = null;
        if (url == null) {
            console.log('URL for editor Ajax requests is not defined.');
        } else {
            window.editorUrl = url;
            addEvents();
        }
        if (id != null) {
            window.editorObjectSlug = id;
        }

        let getPost = (slug) => {
            apiCall({
                url: url + '/' + slug,
                method: 'GET',
            }).then((response) => {
                if (response.status) {
                    window.editorObjectId = response.data.id;
                    for (let i in response.data.contents) {
                        let content = response.data.contents[i];
                        let object = JSON.parse(content.content);
                        let _class = content.type;
                        let module = toTrace(_class);
                        let options = {}
                        options[module] = object;

                        let block = $('<div class="block rendered" data-module="' + module + '"></div>');
                        block.attr('data-id', content.id);
                        block.attr('data-order', content.order);
                        let clone = $('[data-module-name="' + module + '"]')[0].content.firstElementChild.cloneNode(true);

                        block.append(document.querySelector('#controls').content.firstElementChild.cloneNode(true));
                        block.append(clone);
                        block.editor = eval("new " + capitalize(module) + "(clone, options)");

                        editor.find(".overflow").prepend(block)
                    }
                }
            });
        }

        if (!window.editorObjectSlug) {
            apiCall({
                url: url,
                method: 'POST',
            }).then((response) => {
                if (response.status) {
                    history.replaceState({}, "", "/" + lang + "/dashboard/posts/" + response.data.slug + "/edit");
                    getPost(response.data.slug);
                }
            });
        } else {
            getPost(window.editorObjectSlug);
        }
    }

    window.MediaLibrary = require('./plugins/media-library');
    window.CropperModal = require('./plugins/cropper');

}

await (async () => {
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        await domReady();
    } else {
        document.addEventListener('DOMContentLoaded', async () => {
            await domReady();
        });
    }
})();




