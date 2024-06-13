class Module {

    block;
    element;
    result;
    id;
    data;
    content;
    order;

    constructor(element, options = {}) {
        this.block = $(element).closest('.block');
        this.element = element;
        if (options[toTrace(this.constructor.name)]) {
            this.setData(options[toTrace(this.constructor.name)]);
        }

        if ("order" in options) {
            this.order = options.order;
        }

        this.block.find('ul li.delete').on('click', (event) => {
            let self = $(event.delegateTarget);
            if (self.hasClass('really-delete')) {
                this.#delete();
            }
            self.addClass('really-delete');
        }).on('mouseout', (event) => {
            if (!$(event.relatedTarget).closest('li').is(event.delegateTarget)) {
                $(event.delegateTarget).removeClass('really-delete');
            }
        });
    }

    setData(data) {
        this.data = data;
        if (this.data.content) {
            this.content = JSON.parse(this.data.content)
        }
    }

    save(objectToSave = {}, afterSave = false, update = false) {
        let order = this.order;
        apiCall({
            url: editorUrl + '/' + window.editorObjectSlug + '/content',
            method: update ? 'PUT' : 'POST',
            data: {
                object: JSON.stringify(objectToSave),
                type: this.constructor.name,
                order: order
            }
        }).then((response) => {
            this.block.attr('data-id', response.data.content.id);
            this.block.attr('data-order', response.data.content.order);
            if (typeof afterSave === 'function') {
                afterSave(response);
            }
        });
    }

    deleteSelf() {
        this.#delete();
    }

    #delete() {
        apiCall({
            url: editorUrl + '/' + editorObjectSlug + '/content',
            method: 'DELETE',
            data: {
                id: this.block.attr('data-id')
            }
        }).then(() => {
            this.block.animate({
                opacity: 0
            }, 400, () => {
                this.block.remove();
            });
        });
    }
}

class Code extends Module {

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
        super(element);
        this.custom_options = Object.assign(this.custom_options, options);

        this.render();

        this.element.querySelector('.change-mode').addEventListener('change', (event) => {
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

class Text extends Module {

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
                        byFile: '/api/{{$lang}}/media-library'
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
        super(element);
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

class Media extends Module {

    media;
    saveOnChoose = true;

    options = {
        max: 1,
        min: 1,
        types: ['image']
    }

    constructor(element, options) {
        super(element, options);
        for (let i in options) {
            if (this.options[i]) {
                this.options[i] = options[i];
            }
        }

        this.setData();

        this.render();
    }

    #onChoose() {
        this.element.innerHTML = '';

        let div = $('#media-result').get(0).content.firstElementChild.cloneNode(true);
        this.result = div;

        if (this.saveOnChoose) {
            this.save(this.media, (response) => {
                this.setData(response.data.content);
                this.#defineTemplate();
            });
        } else {
            this.#defineTemplate();
        }

        this.element.append(div);
    }

    #defineTemplate() {
        let input = this.result.querySelector('#alt');
        input.id = '#alt_' + this.id;
        this.result.querySelector('*[for="alt"]').htmlFor = 'alt_' + this.id;
        this.result.querySelector('img').src = this.media.local;
        this.result.querySelector('.crop').addEventListener('click', (event) => {
            CropperModal.open({
                image: this.media.local,
                frame: '800x400',
                onFinish: (data) => {
                    this.save({
                        ...this.media,
                        media_id: this.id,
                        crop: data
                    }, (response) => {
                        this.setData(response.data.content);
                        this.#defineTemplate();
                    }, true);
                }
            });
        });
        input.addEventListener('blur', () => {
            alert(this.id);
        });
    }

    setData(data) {
        data = data || false;
        if (data) {
            super.setData(data);
        }
        if (this.content) {
            this.saveOnChoose = false;
            this.media = this.content;
            this.id = this.data.id;
        }
    }

    render() {

        if (!this.media) {
            MediaLibrary.open({
                max: this.options.max,
                types: this.options.types
            }, (selections) => {
                this.media = selections[0];
                this.#onChoose();
            }, () => {
                this.deleteSelf();
            });
        } else {
            this.#onChoose();
        }
    }

}

class Cropper {

    target;
    image;
    cropper;
    info;
    frame_w = 0;
    frame_h = 0;
    percent = 0;
    imageIsMoreLargeThanFrame = false;
    originalWidth = 0;
    originalHeight = 0;

    options = {
        frame: '600x600',
        maxFrameWidth: 300,
        zoomStep: 1,
        target: null,
        image: null,
        onFinish: null,
    }

    process() {

        const _x = this.info.find('.x').get(0);
        const _y = this.info.find('.y').get(0);
        const _w = this.info.find('.z_size').get(0);

        if (!_x.classList.contains('bind')) {
            _x.classList.add('bind');
            $(_x).on('keyup', (event) => {
                this.image.css({
                    left: event.target.value + 'px'
                });
                this.process();
            });
            $(_y).on('keyup', (event) => {
                this.image.css({
                    top: event.target.value + 'px'
                });
                this.process();
            });
            $(_w).on('keyup', (event) => {
                this.image.attr('data-zoom', event.target.value);
                let zoom = event.target.value;

                let obj = {};
                if (this.image.hasClass('width')) obj.width = zoom + '%';
                if (this.image.hasClass('height')) obj.height = zoom + '%';

                this.image.css(obj);

                this.process();
            });
            let els = [
                _x, _y, _w
            ];
            els.forEach((el) => {
                $(el).on('mousewheel', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    let value = parseInt(el.value);
                    if (event.originalEvent.deltaY > 0) {
                        value -= 1;
                    } else {
                        value += 1;
                    }
                    el.value = value;
                    $(el).trigger('keyup');
                });
            });
        }

        if (this.image.length) {

            let y = this.image[0].offsetTop - this.image[0].parentElement.offsetTop;
            let x = this.image[0].offsetLeft - this.image[0].parentElement.offsetLeft;
            _x.value = x;
            _y.value = y;

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

            this.definePercent();

            let o = {
                image: this.image,
                frame_w: this.frame_w,
                frame_h: this.frame_h,
                y,
                x,
                zoom,
                dest_w: Math.round(dest_w),
                dest_h: Math.round(dest_h),
                resized_x,
                resized_y
            };

            let result = this.target.find(".result");
            result.css({
                width: o.frame_w,
                height: o.frame_h,
                overflow: 'hidden',
                position: 'relative',
            });
            let image = o.image.clone();
            image.css({
                width: o.dest_w,
                height: o.dest_h,
                top: o.resized_y,
                left: o.resized_x,
                position: 'absolute',
            });
            result.html(image);

            o.image = o.image.attr('src').replace(window.location.origin, '');
            return o;
        }
        return false;
    }

    setFrame(frame) {
        frame = frame || false;
        if (frame) {
            frame = frame.toLowerCase().split('x');
            if (frame.length === 1) {
                console.warn('frame format is EX: 200x400')
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
        } else {
            this.cropper.css({
                width: this.frame_w,
                height: this.frame_h
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
            console.warn('image have to be a string or object');
            return false;
        }

        if (!img) return false;

        this.image = img;

        this.image.attr('data-zoom', 100);

        this.info.find('.z_size').get(0).value = 100;

        this.cropper.html(this.image);

        this.image.on('load', () => {
            this.definePercent();
            this.info.find(".o_size").html(this.originalWidth + ' x ' + this.originalHeight);
            this.info.find(".p_size").html(this.cropper.width() + ' x ' + this.cropper.height());
            this.image.addClass('height').removeClass('width');
            if (this.originalWidth > this.originalHeight) {
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

    definePercent() {

        let cw;
        let ch;
        let secondCall = false;
        if (!this.originalHeight || !this.originalWidth) {
            this.originalHeight = this.image[0].naturalHeight;
            this.originalWidth = this.image[0].naturalWidth;
            cw = this.cropper.width();
            ch = this.cropper.height();
        } else {
            cw = this.image.width();
            ch = this.image.height();
            secondCall = true;
        }

        let height = this.originalHeight;
        let width = this.originalWidth;

        if (height > width) {
            if (height < ch) {
                let diff = ch - height;
                this.percent = diff * 100 / height;
                this.imageIsMoreLargeThanFrame = false;
                if (this.percent < 100 || secondCall) {
                    this.percent += 100;
                }
            } else {
                let diff = height - ch;
                this.percent = diff * 100 / height;
                this.imageIsMoreLargeThanFrame = true;
            }
            this.image.height(ch);
        } else {
            if (width < cw) {
                let diff = cw - width;
                this.percent = diff * 100 / width;
                this.imageIsMoreLargeThanFrame = false;
                if (this.percent < 100 || secondCall) {
                    this.percent += 100;
                }
            } else {
                let diff = width - cw;
                this.percent = diff * 100 / width;
                this.imageIsMoreLargeThanFrame = true;
            }
            this.image.width(cw);
        }
    }

    constructor(options) {
        Object.assign(this.options, options);
        if (!this.options.target || $(this.options.target).length < 1) {
            console.warn('target for cropper not set or not exists in DOM')
            return;
        }

        let template = $('#cropper');
        let target = $(this.options.target);
        if (!template.length) {
            console.warn('template required for init cropper')
            return;
        }

        if (!this.options.image) {
            console.warn('image not set or not found');
            return;
        }

        if (!this.setFrame(this.options.frame)) {
            console.warn('frame not set or is in wrong format. Correct format is EX: 200x400');
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

                this.info.find('.z_size').get(0).value = zoom;

                this.image.css(obj);

                this.process();
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

        let finish = this.target.closest('.modal-dialog').find('.finish');
        finish.on('click', (event) => {
            finish.unbind('click');
            let o = this.process();
            if (this.options.onFinish && is_callable(this.options.onFinish) && o) {
                this.options.onFinish(o);
            }
            $(event.target).prev().trigger('click');
        })

    }

}

function setModuleOptions(editor, module, options) {
    editor = $(editor)
    if (editor.length && typeof options === 'object') {
        module = editor.find('*[data-module="' + module + '"]');
        if (module.length) {
            module.get(0).options = options;
        }
    }
}

module.exports = {
    Code,
    Text,
    Media,
    Cropper,
    setModuleOptions
}
