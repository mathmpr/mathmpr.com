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

class Media {

    element;

    constructor(element) {
        console.log(element);
        this.element = element;
        this.render();
    }


    render() {
        MediaLibrary.open({
            max: 1,
            types: ['image']
        },(selections) => {
            console.log(selections);
        });
        this.options = {};
    }

}

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

module.exports = {
    Code,
    Text,
    Media,
    Cropper
}
