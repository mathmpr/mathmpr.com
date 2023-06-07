class MediaLibrary {

    static totalPages = 0;
    static currentPage = 0;
    static offset = 0;
    static limit = 18;
    static content = $('#media-library .library .content');
    static selectionsElement = $('#media-library .selections');
    static container = $('#media-library');
    static scroll = $('#media-library .modal-body');
    static fetching = false;
    static options = {};
    /**
     * @type {boolean|function}
     */
    static onClose = false;

    /**
     * @type {boolean|function}
     */
    static onDismiss = false;

    static reset = () => {
        MediaLibrary.confirm = false;
        MediaLibrary.content.html('');
        MediaLibrary.selectionsElement.hide();
    };

    static open(options = {}, onClose = false, onDismiss) {
        if (typeof options !== 'object') {
            options = {};
        }
        MediaLibrary.onClose = false;
        MediaLibrary.options = options;
        MediaLibrary.init();
        if (typeof onClose === 'function') {
            MediaLibrary.onClose = onClose;
        }
        if (typeof onDismiss === 'function') {
            MediaLibrary.onDismiss = onDismiss;
        }
        MediaLibrary.container.modal('show');
    }

    static init() {
        let reachEnd = () => {
            if (MediaLibrary.scroll.get(0).scrollTop >= (MediaLibrary.scroll.get(0).scrollHeight - MediaLibrary.scroll.get(0).offsetHeight) && MediaLibrary.scroll.is(':visible')) {
                if (!MediaLibrary.fetching && MediaLibrary.currentPage < MediaLibrary.totalPages) {
                    MediaLibrary.fetching = true;
                    getMedias(MediaLibrary.currentPage * MediaLibrary.limit)
                }
            }
        }

        MediaLibrary.scroll.unbind('scroll');
        MediaLibrary.scroll.on('scroll', (event) => {
            reachEnd();
        });

        MediaLibrary.scroll.unbind('scroll');
        MediaLibrary.scroll.on('mousewheel', () => {
            reachEnd();
        });

        MediaLibrary.container.find('.modal-footer .btn-primary').unbind('click');
        MediaLibrary.container.find('.modal-footer .btn-primary').on('click', () => {
            MediaLibrary.confirm = true;
            MediaLibrary.container.find('.modal-footer .btn-secondary').trigger('click');
        });

        function getMedias(offset = 0) {
            $.ajax({
                type: 'GET',
                url: '/api/' + window.lang + '/media-library',
                cache: false,
                data: {
                    offset: offset,
                    limit: MediaLibrary.limit,
                    types: MediaLibrary.options.types || []
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
                },
                success: function (response) {
                    if (response.success) {
                        MediaLibrary.totalPages = response['total_pages'];
                        MediaLibrary.currentPage = response['page'];
                        response.data.forEach((media) => {
                            handleSuccessResponse(media);
                        });
                        MediaLibrary.fetching = false;
                        reachEnd();
                    }
                }
            });
        }

        MediaLibrary.container.unbind('shown.bs.modal');
        MediaLibrary.container.on('shown.bs.modal', () => {
            getMedias(MediaLibrary.offset);
        });

        MediaLibrary.container.unbind('hide.bs.modal');
        MediaLibrary.container.on('hide.bs.modal', () => {
            if (MediaLibrary.confirm) {
                let selections = []
                MediaLibrary.content.find('.selected').each((index, el) => {
                    selections.push($(el).closest('.col-2').get(0)['selected']);
                });
                if (MediaLibrary.onClose && typeof MediaLibrary.onClose === 'function') {
                    MediaLibrary.onClose(selections);
                }
            } else if (MediaLibrary.onDismiss && typeof MediaLibrary.onDismiss === 'function') {
                MediaLibrary.onDismiss();
            }
            MediaLibrary.reset();
        });

        let addNew = $('#media-library .add_new_button');
        let addNewContainer = $('#media-library .add_new');

        addNew.unbind('click');
        addNew.on('click', () => {
            addNew.toggleClass('opened');
            addNewContainer.toggleClass('open');
            addNewContainer.slideToggle(200);
            if (addNew.hasClass('opened')) {
                addNew.text(trans('backend.plugins.media_library.hide_new'));
            } else {
                addNew.text(trans('backend.plugins.media_library.add_new'));
            }
        });

        let uploadFile = $("#media-library #file .btn-primary");

        uploadFile.unbind('click');
        uploadFile.on('click', () => {
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
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
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
                        setTimeout(() => {
                            item.fadeOut(500, () => {
                                let results = item.closest('.results');
                                item.remove();
                                if (results.find('.item').length === 0 && MediaLibrary.offset > 0) {
                                    getMedias(MediaLibrary.offset);
                                }
                            });
                            handleSuccessResponse(response['media_library'], 'prepend');
                        }, 1500);
                    } else {
                        item.find('.progress-bar span').addClass('bg-danger');
                    }
                }
            });
        }

        let fileInput = $("#media-library input[type='file']");

        fileInput.unbind('change');
        fileInput.on('change', (event) => {

            let read = (key) => {

                if (!event.target.files[key]) {
                    $(event.target).val('');
                    return;
                }

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

        let types = {
            youtube: (url) => {
                if (url != null || url !== '') {
                    let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                    let match = url.match(regExp);
                    if (match && match[2].length === 11) {
                        return match[2];
                    } else {
                        return false;
                    }
                }
                return false;
            },
            vimeo: (url) => {
                let id = url.split('vimeo.com/')
                    .pop()
                    .split('/')
                    .shift()
                    .split('?')
                    .shift();

                if (id.length > 8 && url.indexOf('vimeo.com/' + id) > -1) {
                    return id;
                } else {
                    return false;
                }
            },
            spotify: (url) => {
                let id = url.split('spotify.com/track/')
                    .pop()
                    .split('/')
                    .shift()
                    .split('?')
                    .shift();
                if (url.indexOf('spotify.com/track/' + id) > -1) {
                    return id;
                } else {
                    return false;
                }
            }
        };

        for (let type in types) {
            let validador = types[type];
            let sendForm = $('#media-library #' + type + ' .btn-primary');

            sendForm.unbind('click');
            sendForm.on('click', () => {
                let url = $('#' + type + '-url');
                let match = validador(url.val());
                let error = $('#media-library #' + type + ' .error');
                let success = $('#media-library #' + type + ' .success');
                error.hide();
                if (!match) {
                    error.show().html(trans('backend.plugins.media_library.invalid_' + type + '_url'))
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/' + window.lang + '/media-library',
                    data: {
                        url: match,
                        type: type,
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
                    },
                    cache: false,
                    success: function (response) {
                        success.show().html(trans('backend.plugins.media_library.add_success'));
                        setTimeout(() => {
                            success.fadeOut(500);
                        }, 2000);
                        url.val('');
                        if (response.success) {
                            handleSuccessResponse(response['media_library'], 'prepend');
                        }
                    }
                });
            });
        }

        let common = $('<div class="col-2"></div>');

        let handlers = {
            audio: (response, template = '#audio-media') => {
                let clone = common.clone();
                let element = $(document.querySelector(template).content.firstElementChild.cloneNode(true))
                element.find('source').attr('src', response.local);
                element.find('source').attr('type', response.mime);
                element.find('span').append(response.name);
                return clone.append(element);
            },
            image: (response, template = '#image-media') => {
                let clone = common.clone();
                let element = $(document.querySelector(template).content.firstElementChild.cloneNode(true))
                element.find('img').attr('src', response.local);
                element.find('img').attr('alt', response.name);
                element.find('span').append(response.name);
                return clone.append(element);
            },
            video: (response, template = '#video-media') => {
                return handlers['audio'](response, template);
            },
            vimeo: (response, template = '#vimeo-iframe') => {
                let clone = common.clone();
                let iframe = $(document.querySelector(template).content.firstElementChild.cloneNode(true));
                iframe.src = iframe.find('iframe').attr('src', iframe.find('iframe').attr('src').replace('%s', response.local));
                return clone.append(iframe);
            },
            youtube: (response, template = '#youtube-iframe') => {
                return handlers['vimeo'](response, template);
            },
            spotify: (response, template = '#spotify-iframe') => {
                return handlers['vimeo'](response, template);
            },
            general: (response, template = '#general-media') => {
                let clone = common.clone();
                let element = $(document.querySelector(template).content.firstElementChild.cloneNode(true))
                element.find('i').attr('data-mime-type', response.mime);
                element.find('span').append(response.name);
                return clone.append(element);
            }
        }

        function handleSuccessResponse(response, command = 'append') {
            let type = response.type;
            if (!handlers[response.type]) {
                type = 'general';
            }
            let media = handlers[type](response);
            if (command === 'append') {
                MediaLibrary.content.append(media);
            } else {
                MediaLibrary.content.prepend(media);
            }
            media.find('.btn.select').unbind('click');
            media.find('.btn.select').unbind('click').on('click', (event) => {
                let selector = '#media-library .library .selected';
                let selects = $(selector).length;
                if (MediaLibrary.options.max && (selects >= MediaLibrary.options.max)) {
                    delete $(selector + ':first-child').closest('.col-2').get(0)['selected'];
                    $(selector + ':first-child').removeClass('selected');
                }
                let el = $(event.target);
                if (event.target.tagName !== 'BUTTON') {
                    el = $(event.target).closest('button');
                }
                el.closest('div').toggleClass('selected');
                if (el.closest('div').hasClass('selected')) {
                    el.find('b').text(trans('backend.selected'));
                    media.get(0)['selected'] = response;
                } else {
                    el.find('b').text(trans('backend.select'));
                    delete media.get(0)['selected'];
                }
                selects = $(selector).length;
                if (selects > 0) {
                    MediaLibrary.selectionsElement.show().find('.number').text(selects);
                } else {
                    MediaLibrary.selectionsElement.hide();
                }
            });
        }
    }
}

module.exports = MediaLibrary;
