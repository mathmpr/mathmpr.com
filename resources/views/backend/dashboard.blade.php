@extends('backend.dom')

@section('head')
    <link rel="stylesheet" href="css/backend.css">
    <link rel="stylesheet" href="css/backend/dashboard.css">
@endsection

@section('scripts')

    <script class="app">

        class Code {

            element;
            code;
            options = {};
            custom_options = {};
            mode = 'javascript';
            old_mode;

            languages = {
                javascript: {
                    value: "/* code here */\n"
                },
                php: {
                    value: "<" + "?php\n/* code here */\n"
                },
                xml: {
                    value: '<' + '?xml version="1.0" encoding="UTF-8"?>' + "\n"
                },
                css: {
                    value: "/* code here */\n* {\n\t\n}\n"
                },
                clike: {
                    value: "#include <stdio.h>\n"
                },
                sql: {
                    value: "SELECT 1 + 1\n"
                },
                perl: {
                    value: "print(\"perl\");\n"
                },
                markdown: {
                    value: "# MD Title\n"
                },
                python: {
                    value: "/* code here */\n"
                },
                yaml: {
                    value: "key: 'value'"
                },
                stylus: {
                    value: "/* code here */\n* {\n\t\n}\n"
                },
                sass: {
                    value: "/* code here */\n* {\n\t\n}\n"
                },
                htmlmixed: {
                    value: "<\html>\n\n<\/html>"
                },
                twig: {
                    value: "<\html>\n\n<\/html>"
                }
            }

            defaults = {
                indentUnit: 0,
                smartIndent: true,
                indentWithTabs: true,
                lineWrapping: true,
                styleActiveLine: true,
                matchBrackets: false,
                autoCloseBrackets: true,
                startOpen: true,
                lint: false,
                lineNumbers: true,
                theme: "night",
                mode: "javascript"
            }

            constructor(element, options) {
                this.element = element;
                this.custom_options = Object.assign(this.custom_options, options);

                this.render();

                this.element.querySelector('.change-mode').addEventListener('change', () => {
                    this.old_mode = this.mode;
                    this.mode = event.target.value;
                    this.render();
                });
            }

            applyDefaults(language) {
                language = language || false;
                for (let i in this.defaults) {

                    let pass = this.languages[language] ? Object.keys(this.languages[language]).indexOf(i) < 0 : true;

                    if (pass && Object.keys(this.custom_options).indexOf(i) < 0 && Object.keys(CodeMirror.defaults).indexOf(i) > -1) {
                        this.options[i] = is_callable(this.defaults[i]) ? this.defaults[i]() : this.defaults[i];
                    }
                }
                for (let i in this.custom_options) {
                    if (Object.keys(CodeMirror.defaults).indexOf(i) > -1) {
                        this.options[i] = this.custom_options[i];
                    }
                }
            }

            setExtraOptions(language) {
                language = language || false;
                if (this.languages[language]) {
                    for (let i in this.languages[language]) {
                        if (Object.keys(this.custom_options).indexOf(i) < 0 && Object.keys(CodeMirror.defaults).indexOf(i) > -1) {
                            this.options[i] = is_callable(this.languages[language][i]) ? this.languages[language][i]() : this.languages[language][i];
                        }
                    }
                }
            }

            render() {
                this.options = {};

                let value = null;
                if (this.code) {
                    value = this.code.getValue();
                }

                let code = this.element.querySelector('.code');

                code.innerHTML = '';

                this.setExtraOptions(this.mode);
                this.applyDefaults(this.mode);

                if (value) {
                    if (this.old_mode && this.languages[this.old_mode]) {
                        if (value !== this.languages[this.old_mode].value) {
                            this.options['value'] = value;
                        }
                    }
                }

                this.options.mode = this.mode;
                this.code = CodeMirror(code, this.options);
            }

            value(value) {
                value = value || false;
                if (value && this.code) {
                    this.code.setValue(value);
                }
                if (this.code) return this.code.getValue();
                return '';
            }

        }

        class Text {

            element;
            editor;
            options = {};
            custom_options = {};

            defaults = {
                tools: {
                    header: {
                        class: Header
                    },
                    list: {
                        class: List
                    },
                    quote: {
                        class: Quote
                    },
                    link: {
                        class: Link
                    },
                    underline: {
                        class: Underline
                    },
                    table: {
                        class: Table
                    },
                    image: {
                        class: Image,
                        config: {
                            endpoints: {
                                byFile: '{{ URL::to('/') }}/api/media-library'
                            }
                        }
                    },
                    delimiter: {
                        class: Delimiter
                    },
                },
                data: {}
            }

            constructor(element, options) {
                this.element = element;
                this.custom_options = Object.assign(this.custom_options, options);

                this.render();
            }

            applyDefaults() {
                for (let i in this.defaults) {
                    if (Object.keys(this.custom_options).indexOf(i) < 0) {
                        this.options[i] = is_callable(this.defaults[i]) ? this.defaults[i]() : this.defaults[i];
                    }
                }
                for (let i in this.custom_options) {
                    if (Object.keys(CodeMirror.defaults).indexOf(i) > -1) {
                        this.options[i] = this.custom_options[i];
                    }
                }
            }

            render() {
                this.options = {};

                let editor = this.element.querySelector('.editor');

                editor.innerHTML = '';

                this.applyDefaults();

                this.options.holder = editor;

                this.editor = new EditorJS(this.options);
            }

            value(value) {
                value = value || false;
                if (value && this.code) {
                    this.code.setValue(value);
                }
                if (this.code) return this.code.getValue();
                return '';
            }

        }

        $(function () {

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
        });

    </script>

    <script class="app">

        class Cropper {

            target;
            image;
            cropper;
            info;
            frame_w = 0;
            frame_h = 0;

            options = {
                frame: '600x600',
                maxFrameWidth: 300,
                zoomStep: 3,
                target: null,
                image: null,
            }

            process() {

                if (this.image.length) {
                    let y = this.image[0].offsetTop;
                    let x = this.image[0].offsetLeft;
                    this.info.find('.x').html(x);
                    this.info.find('.y').html(y);

                    let zoom = (parseInt(this.image.attr('data-zoom')) / 100);

                    let dest_w;
                    let dest_h;

                    if (this.image.width() > this.image.height()) {
                        dest_w = this.frame_w * zoom;
                        dest_h = ((this.frame_w / this.image[0].naturalWidth) * this.image[0].naturalHeight) * zoom;
                    } else {
                        dest_h = this.frame_h * zoom;
                        dest_w = ((this.frame_h / this.image[0].naturalHeight) * this.image[0].naturalWidth) * zoom;
                    }

                    let resized_x = Math.round(x * (this.frame_w / this.image.parent().width()));
                    let resized_y = Math.round(y * (this.frame_h / this.image.parent().height()));

                    if (x < 0) {
                        x = -x;
                        resized_x = Math.round(x * (this.frame_w / this.image.parent().width()));
                        resized_x = -resized_x;
                        x = -x;
                    }

                    if (y < 0) {
                        y = -y;
                        resized_y = Math.round(y * (this.frame_h / this.image.parent().height()));
                        resized_y = -resized_y;
                        y = -y;
                    }

                    this.info.find('.xr').html(resized_x);
                    this.info.find('.yr').html(resized_y);

                    return {
                        image: this.image,
                        frame_w: this.frame_w,
                        frame_h: this.frame_h,
                        y,
                        x,
                        zoom,
                        dest_w,
                        dest_h,
                        resized_x,
                        resized_y
                    }
                }
                return false;
            }

            setFrame(frame) {
                frame = frame || false;
                if (frame) {
                    frame = frame.toLowerCase().split('x');
                    if (frame.length === 1) {
                        console.log('frame format is EX: 200x400')
                        return false;
                    }

                    frame = frame.map((e) => {
                        return parseInt(e.trim());
                    });

                    [this.frame_w, this.frame_h] = frame;
                }
                return -1;
            }

            determineCropperSize() {
                if (this.frame_w > this.options.maxFrameWidth) {
                    let divider = this.frame_w / this.options.maxFrameWidth;
                    let height = this.frame_h / divider;
                    this.cropper.css({
                        width: this.options.maxFrameWidth,
                        height
                    });
                }
            }

            setImage(image) {
                image = image || false;
                let img = image

                if (typeof img === 'string') {
                    let _img;
                    try {
                        _img = $(img);
                    } catch (e) {
                        _img = {
                            length: 0
                        };
                    }
                    if (_img.length) {
                        img = _img.clone();
                    } else {
                        img = $('<img src="' + img + '" alt="cropper">');
                    }
                }

                if (typeof img === 'number') {
                    console.log('image have to be a string or object');
                    return false;
                }

                if (!img) return false;

                this.image = img;

                this.image.attr('data-zoom', 100);

                this.info.find('.z_size').html('100%');

                this.cropper.html(this.image);

                this.image.on('load', () => {
                    let height = this.image[0].naturalHeight;
                    let width = this.image[0].naturalWidth;
                    this.info.find(".o_size").html(width + ' x ' + height);
                    this.info.find(".p_size").html(this.cropper.width() + ' x ' + this.cropper.height());
                    this.image.addClass('height').removeClass('width');
                    if (width > height) {
                        this.image.addClass('width').removeClass('height');
                    }
                    this.process();
                });

                this.image.draggable({
                    drag: () => {
                        this.process();
                    }
                });

                this.process();

                return true;
            }

            constructor(options) {
                Object.assign(this.options, options);
                if (!this.options.target || $(this.options.target).length < 1) {
                    console.log('target for cropper not set or not exists in DOM')
                    return;
                }

                let template = $('#cropper');
                let target = $(this.options.target);
                if (!template.length) {
                    console.log('template required for init cropper')
                    return;
                }

                if (!this.options.image) {
                    console.log('image not set or not found');
                    return;
                }

                if (!this.setFrame(this.options.frame)) {
                    console.log('frame not set or is in wrong format. Correct format is EX: 200x400');
                    return;
                }

                this.target = target;

                target.html(template.get(0).content.firstElementChild.cloneNode(true));

                this.cropper = target.find('.cropper');
                this.info = target.find('.cropper-information');

                this.determineCropperSize();

                if (!this.setImage(this.options.image)) {
                    return;
                }

                this.info.find('.d_size').html(this.frame_w + ' x ' + this.frame_h);

                this.cropper.on('mousewheel', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    if (this.image.length) {
                        let zoom = this.image.attr('data-zoom');
                        if (!zoom) {
                            this.image.attr('data-zoom', 100);
                        }
                        zoom = parseInt(this.image.attr('data-zoom'));
                        if (event.originalEvent.deltaY > 0) {
                            zoom -= this.options.zoomStep;
                        } else {
                            zoom += this.options.zoomStep;
                        }
                        if (zoom < 0) {
                            zoom = 1;
                        }
                        this.image.attr('data-zoom', zoom);

                        let obj = {};
                        if (this.image.hasClass('width')) obj.width = zoom + '%';
                        if (this.image.hasClass('height')) obj.height = zoom + '%';

                        this.info.find('.z_size').html(zoom + '%');

                        this.image.css(obj);

                        this.process();
                    }
                });

                this.target.find("button.crop").on('click', () => {
                    let o = this.process();
                    if (o) {
                        let result = this.target.find(".result");
                        result.css({
                            width: o.frame_w,
                            height: o.frame_h,
                            overflow: 'hidden',
                            position: 'relative',
                            border: '1px solid',
                        });
                        let image = o.image.clone();
                        image.css({
                            width: o.dest_w,
                            height: o.dest_h,
                            top: o.resized_y,
                            left: o.resized_x,
                            position: 'absolute',
                            border: '1px solid'
                        });
                        result.html(image);
                    }
                });

                if (!this.image[0].offsetParent) {
                    let t_i = setInterval(() => {
                        if (this.image[0].offsetParent) {
                            this.process();
                            clearInterval(t_i);
                        }
                    }, 50);
                }

            }


        }


        $(() => {

            /*
            let c = new Cropper({
                target: '#to-crop',
                image: '{{ asset('storage/Banner.webp') }}',
                frame: '900x600',
                maxFrameWidth: 450
            });
             */

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
                    url: '{{ URL::to('/') }}/api/media-library',
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


            $("#media-library [name='file']").on('change', (event) => {

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


                return;


                if (event.target.files.length) {

                }
            });
        });

    </script>

@endsection

@section('modals')
    <div class="modal fade" id="media-library" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="media-library" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Media Library</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="to-crop"></div>


                    <ul class="nav nav-pills" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#file" type="button"
                                    role="tab" aria-controls="file" aria-selected="true">Upload file
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#youtube" type="button"
                                    role="tab" aria-controls="youtube" aria-selected="false">Youtube
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vimeo" type="button"
                                    role="tab" aria-controls="vimeo" aria-selected="false">Vimeo
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="upload-tab">
                            <div class="form-group">
                                <input type="file" class="form-control" name="file" multiple>
                            </div>
                            <div class="results">
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="youtube" role="tabpanel" aria-labelledby="youtube-tab">
                            <div class="form-group">
                                <label for="youtube-url" class="form-label">Vídeo URL</label>
                                <input type="text" class="form-control" name="url" id="youtube-url">
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vimeo" role="tabpanel" aria-labelledby="vimeo-tab">
                            <div class="form-group">
                                <label for="vimeo-url" class="form-label">Vídeo URL</label>
                                <input type="text" class="form-control" name="url" id="vimeo-url">
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Finalizar seleção</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main')

    <template id="cropper">
        <div>
            <div class="cropper-wrapper">
                <div class="cropper"></div>
                <div class="cropper-information">
                    <div>
                        <p>Desired size: <b class="d_size">1000 x 1000</b></p>
                        <p>Image original size: <b class="o_size"></b></p>
                        <p>Preview size: <b class="p_size"></b></p>
                        <p>Zoom percentage: <b class="z_size"></b></p>
                        <p>X on preview: <b class="x"></b></p>
                        <p>Y on preview: <b class="y"></b></p>
                        <p>X with percentage: <b class="xr"></b></p>
                        <p>Y with percentage: <b class="yr"></b></p>
                    </div>
                    <p>
                        <button class="btn btn-primary crop">Crop</button>
                    </p>
                </div>
                <div class="result"></div>
            </div>
        </div>
    </template>

    <template id="controls">
        <ul class="controls">
            <li class="sort">
                <i class="fa-solid fa-right-left"></i>
            </li>
            <li class="delete">
                <i class="fa-solid fa-times"></i>
            </li>
        </ul>
    </template>

    <template data-module-name="code">
        <div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Language</span>
                </div>
                <select class="change-mode form-control">
                    <option value="javascript">Javascript</option>
                    <option value="xml">XML</option>
                    <option value="css">CSS</option>
                    <option value="php">PHP</option>
                    <option value="twig">Twig</option>
                    <option value="sql">SQL</option>
                    <option value="clike">C</option>
                    <option value="perl">Perl</option>
                    <option value="markdown">Markdown</option>
                    <option value="python">Python</option>
                    <option value="yaml">Yaml</option>
                    <option value="stylus">Stylus</option>
                    <option value="sass">SASS</option>
                    <option value="htmlmixed">HTML</option>
                </select>
            </div>
            <div class="code"></div>
        </div>
    </template>

    <template data-module-name="media">

    </template>

    <template data-module-name="text">
        <div>
            <div class="editor"></div>
        </div>
    </template>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#media-library">
        Launch demo modal
    </button>

    <div class="container">
        <div id="mathmpr-editor">
            <ul class="module-selector">
                <li data-module="code">
                    <i class="fa-solid fa-code"></i>
                </li>
                <li data-module="media">
                    <i class="fa-solid fa-photo-film"></i>
                </li>
                <li data-module="text">
                    <i class="fa-solid fa-font"></i>
                </li>
            </ul>
            <div class="overflow"></div>
        </div>

    </div>


@endsection

