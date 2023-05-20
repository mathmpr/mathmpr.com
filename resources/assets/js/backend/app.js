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

    $(() => {

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

        $('#media-library').on('shown.bs.modal', () => {
            $.ajax({
                type: 'GET',
                url: '/api/' + window.lang + '/media-library',

                cache: false,
                success: function (response) {
                    console.log(response);
                }
            });
        });

        $("#media-library #file .btn-primary").on('click', () => {

            let items = $("#media-library #file .results .item");
            if (items.length) {

                items.each((i, item) => {
                    let file = $(item).find('.info').get(0).file;
                    if (file) {
                        upload_file(file, $(item));
                    }
                });
            }

        });

        let upload_file = (file, item) => {

            let data = new FormData();
            data.append('upload', file);

            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = ((evt.loaded / evt.total) * 100);
                            item.find('.progress-bar span').width(percentComplete + '%');
                            item.find('.progress-bar div').on('click', () => {
                                item.find('.info').get(0).file = null;
                                item.find('.progress-bar span').width(100 + '%');
                                item.find('.progress-bar div').removeClass('can-abort').html('Aborted').css('color', '#fff');
                                xhr.abort();
                            });
                        }
                    }, false);
                    return xhr;
                },
                type: 'POST',
                url: '/api/' + window.lang + '/media-library',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    item.find('.progress-bar').addClass('uploading');
                    item.find('.progress-bar span').width('0%');
                },
                error: function () {
                    item.find('.progress-bar span').addClass('bg-danger');
                },
                success: function (response) {
                    if (response.success) {
                        item.get(0).file = null;
                        item.find('.progress-bar span').addClass('bg-success');
                        item.find('.progress-bar div').unbind('click');
                        item.find('.progress-bar div').removeClass('can-abort').html('<i class="fa-solid fa-check"></i>').css('color', '#fff');
                    } else {
                        item.find('.progress-bar span').addClass('bg-danger');
                    }
                }
            });
        }

        $("#media-library input[type='file']").on('change', (event) => {
            console.log(event);
            let read = (key) => {

                if (!event.target.files[key]) return;

                let mime = getMimeType(event.target.files[key].name.split('.').pop());

                if (mime.indexOf('image') > -1 || mime.indexOf('video') > -1) {
                    let reader = new FileReader();
                    reader.onload = function (reader_event) {
                        read_end(event.target.files[key], reader_event.target.result);
                        key++;
                        read(key);
                    };
                    if (event.target.files[key]) reader.readAsDataURL(event.target.files[key]);
                } else {
                    read_end(event.target.files[key]);
                    key++;
                    read(key);
                }
            }

            let fill_details = (file, object, size, width, height) => {
                width = width || null;
                height = height || null;

                let info = $("<div class='info'></div>");

                if (width) {
                    info.append("<p>Width: <b>" + width + "px</b></p>");
                    info.append("<p>Height: <b>" + height + "px</b></p>");
                }
                info.append("<p>Size: <b>" + (size / 1024 / 1024).toFixed(2) + "MB</b></p>");
                info.get(0).file = file;

                let item = $('<div class="item"></div>');
                item.append($('<div class="wrapper"></div>'));
                item.find('.wrapper').append(info).append(object);
                item.append($('<div class="progress-bar"></div>'))
                item.find('.progress-bar').append("<span></span>").append('<div class="can-abort">Abort</div>');

                $(event.target).closest('.tab-pane').find('.results').append(item);
            }

            let read_end = (file, base64) => {
                base64 = base64 || null;

                let object;

                if (file.type.indexOf('image') > -1) {
                    object = $('<img src="' + base64 + '" alt="' + file.type + '"/>');
                    object.on('load', () => {
                        fill_details(file, object, file.size, object.get(0).naturalWidth, object.get(0).naturalHeight);
                    });
                    return;
                }

                if (file.type.indexOf('video') > -1) {
                    object = $('<video muted autoplay loop><source src="' + base64 + '" type="' + file.type + '"></video>');
                    object.on('loadeddata', () => {
                        fill_details(file, object, file.size, object.get(0).videoWidth, object.get(0).videoHeight);
                        object.get(0).play();
                    });
                    return;
                }

                object = $('<div data-mime-type="' + getMimeType(file.name.split('.').pop()) + '" class="file-type"></div>');
                fill_details(file, object, file.size);

            }

            if (event.target.files.length) {
                read(0);
            }

        });

        $("#media-library #youtube .btn-primary").on('click', () => {
            let url = $('#youtube-url');
            $.ajax({
                type: 'POST',
                url: '/api/' + window.lang + '/media-library',
                data: {
                    url: url.val()
                },
                cache: false,
                success: function (response) {
                    if (response.success) {

                    }
                }
            });
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


