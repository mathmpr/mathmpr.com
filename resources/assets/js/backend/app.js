require('../bootstrap');

window.marked = require('marked');
window.DOMPurify = require('dompurify');
if (!window.DOMPurify.sanitize && typeof window.DOMPurify === 'function') {
    window.DOMPurify = window.DOMPurify(window);
}
window.DOMPurify.addHook('uponSanitizeElement', (node, data) => {
    if (data.tagName !== 'iframe') {
        return;
    }

    let src = node.getAttribute('src') || '';
    let allowed = /^https:\/\/(www\.)?(youtube\.com\/embed\/|youtube-nocookie\.com\/embed\/)/.test(src);

    if (!allowed) {
        node.parentNode?.removeChild(node);
    }
});
window.hljs = require('highlight.js/lib/core');
window.hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash'));
window.hljs.registerLanguage('c', require('highlight.js/lib/languages/c'));
window.hljs.registerLanguage('cpp', require('highlight.js/lib/languages/cpp'));
window.hljs.registerLanguage('csharp', require('highlight.js/lib/languages/csharp'));
window.hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));
window.hljs.registerLanguage('go', require('highlight.js/lib/languages/go'));
window.hljs.registerLanguage('html', require('highlight.js/lib/languages/xml'));
window.hljs.registerLanguage('java', require('highlight.js/lib/languages/java'));
window.hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
window.hljs.registerLanguage('json', require('highlight.js/lib/languages/json'));
window.hljs.registerLanguage('kotlin', require('highlight.js/lib/languages/kotlin'));
window.hljs.registerLanguage('markdown', require('highlight.js/lib/languages/markdown'));
window.hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
window.hljs.registerLanguage('python', require('highlight.js/lib/languages/python'));
window.hljs.registerLanguage('ruby', require('highlight.js/lib/languages/ruby'));
window.hljs.registerLanguage('scss', require('highlight.js/lib/languages/scss'));
window.hljs.registerLanguage('shell', require('highlight.js/lib/languages/bash'));
window.hljs.registerLanguage('sql', require('highlight.js/lib/languages/sql'));
window.hljs.registerLanguage('typescript', require('highlight.js/lib/languages/typescript'));
window.hljs.registerLanguage('xml', require('highlight.js/lib/languages/xml'));
window.hljs.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));
window.Cookies = require('../cookie');
window.jQuery = require('jquery');
window.$ = window.jQuery;
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.min');

window.apiCall = async (options = {}) => {
    return backendCall({
        ...options,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.apiToken);
        },
    });
};

window.backendCall = (options = {}) => {
    return new Promise((resolve) => {
        let isFormData = options.data instanceof FormData;

        if (isFormData) {
            options.data.append('_token', window.csrfToken);
        } else if (options.data) {
            options.data._token = window.csrfToken;
        } else {
            options.data = {
                _token: window.csrfToken
            };
        }

        $.ajax({
            ...options,
            processData: isFormData ? false : options.processData,
            contentType: isFormData ? false : options.contentType,
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
};

let domReady = () => {
    let headerToggle = document.querySelector('header span.toggle');
    if (headerToggle) {
        headerToggle.addEventListener('click', () => {
            document.querySelector('header').classList.toggle('open');
        });
    }

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
                });
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
                }
                return window.trans(key, translations[current]);
            }
            return translations;
        }
        return false;
    };

    let t = (key, fallback, replacements = {}) => {
        let value = window.trans(key);
        if (!value || typeof value !== 'string') {
            value = fallback;
        }

        Object.keys(replacements).forEach((name) => {
            value = value.replace(':' + name, replacements[name]);
        });

        return value;
    };

    let localizedUrl = (lang, path = window.location.pathname) => {
        let parts = path.split('/');
        let locales = $('#backend-language option, #node-language option')
            .map((index, option) => option.value)
            .get();

        if (locales.includes(parts[1])) {
            parts[1] = lang;
            return parts.join('') ? parts.join('/') + window.location.search : '/' + lang + window.location.search;
        }

        return '/' + lang + (path.startsWith('/') ? path : '/' + path) + window.location.search;
    };

    let navigateToLang = (lang) => {
        let editor = $('#mathmpr-editor');

        if (editor.length) {
            let identifier = window.editorObjectId || window.editorObjectSlug;
            let path = identifier
                ? '/dashboard/nodes/' + identifier + '/edit'
                : '/dashboard/nodes/create';

            window.location.assign('/' + lang + path);
            return;
        }

        window.location.assign(localizedUrl(lang));
    };

    $('#backend-language').on('change', (event) => {
        navigateToLang(event.currentTarget.value);
    });

    $('#backend-theme-toggle').on('click', (event) => {
        let button = $(event.currentTarget);
        let icon = button.find('i');
        let isDark = $('body').hasClass('dark');

        if (isDark) {
            Cookies.remove('skin');
            Cookies.set('skin', 'default');
            $('body').removeClass('dark');
            icon.removeClass('fa-sun').addClass('fa-moon');
            return;
        }

        Cookies.remove('skin');
        Cookies.set('skin', 'dark');
        $('body').addClass('dark');
        icon.removeClass('fa-moon').addClass('fa-sun');
    });

    let deleteModalElement = document.getElementById('nodeDeleteModal');
    if (deleteModalElement) {
        document.body.appendChild(deleteModalElement);

        let deleteModal = window.bootstrap.Modal.getOrCreateInstance(deleteModalElement);
        let deletingNode = null;
        let deleteConfirm = $('#node-delete-confirm');
        let deleteError = $('#node-delete-error');
        let deleteConfirmLabel = deleteConfirm.html();

        $('.node-delete-trigger').on('click', (event) => {
            let button = $(event.currentTarget);
            deletingNode = {
                id: button.attr('data-node-id'),
                title: button.attr('data-node-title'),
                url: button.attr('data-node-url')
            };

            $('#node-delete-title').text(deletingNode.title || '');
            deleteError.hide();
            deleteConfirm.prop('disabled', false).html(deleteConfirmLabel);
            deleteModal.show();
        });

        $('.node-delete-cancel').on('click', () => {
            deleteModal.hide();
        });

        deleteConfirm.on('click', async () => {
            if (!deletingNode) {
                return;
            }

            deleteError.hide();
            deleteConfirm
                .prop('disabled', true)
                .html('<i class="fa-solid fa-circle-notch fa-spin"></i> ' + t('backend.nodes.delete_modal.deleting', 'Excluindo...'));

            let response = await apiCall({
                url: deletingNode.url,
                method: 'DELETE'
            });

            if (!response.status) {
                deleteConfirm.prop('disabled', false).html(deleteConfirmLabel);
                deleteError.show();
                return;
            }

            $('[data-node-row="' + deletingNode.id + '"]').remove();
            deleteModal.hide();
            deletingNode = null;
        });
    }

    $('.node-duplicate-trigger').on('click', async (event) => {
        let button = $(event.currentTarget);
        let originalLabel = button.html();

        button
            .prop('disabled', true)
            .html('<i class="fa-solid fa-circle-notch fa-spin"></i> ' + t('backend.nodes.duplicating', 'Duplicando...'));

        let response = await apiCall({
            url: button.attr('data-node-url'),
            method: 'POST',
            data: {
                lang: window.lang || 'pt'
            }
        });

        if (!response.status || !response.data) {
            button
                .prop('disabled', false)
                .html('<i class="fa-solid fa-triangle-exclamation"></i> ' + t('backend.nodes.duplicate_error', 'Não foi possível duplicar o node.'));
            setTimeout(() => {
                button.html(originalLabel);
            }, 1800);
            return;
        }

        let identifier = response.data.slug || response.data.id;
        window.location.assign('/' + (window.lang || 'pt') + '/dashboard/nodes/' + identifier + '/edit');
    });

    let editor = $("#mathmpr-editor");

    if (editor.length > 0) {
        let titleInput = $('#title');
        let titleDisplay = $('#node-title-display');
        let titleShell = $('#node-title-shell');
        let languageSelect = $('#node-language');
        let descriptionInput = $('#description');
        let coverInput = $('#cover_image');
        let coverFileInput = $('#node-cover-input');
        let coverDropzone = $('#node-cover-dropzone');
        let coverPreview = $('#node-cover-preview');
        let coverError = $('#node-cover-error');
        let coverPlaceholderText = $('.node-cover-placeholder-text');
        let contentInput = $('#content');
        let attachmentInput = $('#markdown-attachment-input');
        let markdownPreview = $('#markdown-preview');
        let saveButton = $('#node-save');
        let url = editor.attr('data-url');
        let currentLang = window.lang || languageSelect.val() || 'pt';

        window.editorObjectSlug = editor.attr('data-id') || null;

        let setCurrentLang = (lang) => {
            currentLang = lang;
            window.lang = lang;
            url = '/api/' + currentLang + '/nodes';
            editor.attr('data-url', url);
            languageSelect.val(currentLang);
        };

        setCurrentLang(currentLang);

        let updateTitleDisplay = () => {
            let title = titleInput.val() ? titleInput.val().trim() : '';
            titleDisplay.text(title || t('backend.editor.untitled', 'Sem titulo'));
            titleDisplay.toggleClass('is-empty', title.length === 0);
        };

        let editTitle = () => {
            titleShell.addClass('is-editing');
            requestAnimationFrame(() => {
                titleInput.trigger('focus');
                if (titleInput.get(0)) {
                    titleInput.get(0).select();
                }
            });
        };

        let setNodeData = (node) => {
            if (!node) {
                return;
            }

            window.editorObjectId = node.id;
            titleInput.val(node.title || '');
            descriptionInput.val(node.description || '');
            coverInput.val(node.cover_image || '');
            contentInput.val(node.content || '');
            updateCoverPreview(node.cover_image || '');
            if (node.slug && node.slug !== window.editorObjectSlug) {
                window.editorObjectSlug = node.slug;
                history.replaceState({}, "", "/" + currentLang + "/dashboard/nodes/" + node.slug + "/edit");
            }
            updateTitleDisplay();
            renderMarkdownPreview();
        };

        let setSaveState = (state) => {
            if (!saveButton.length) {
                return;
            }

            if (state === 'saving') {
                saveButton.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin"></i> ' + t('backend.editor.saving', 'Salvando'));
                return;
            }

            if (state === 'saved') {
                saveButton.prop('disabled', false).html('<i class="fa-solid fa-check"></i> ' + t('backend.editor.saved', 'Salvo'));
                setTimeout(() => {
                    saveButton.html('<i class="fa-solid fa-floppy-disk"></i> ' + t('backend.editor.save', 'Salvar'));
                }, 1200);
                return;
            }

            if (state === 'error') {
                saveButton.prop('disabled', false).html('<i class="fa-solid fa-triangle-exclamation"></i> ' + t('backend.editor.error', 'Erro'));
                setTimeout(() => {
                    saveButton.html('<i class="fa-solid fa-floppy-disk"></i> ' + t('backend.editor.save', 'Salvar'));
                }, 1600);
                return;
            }

            saveButton.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> ' + t('backend.editor.save', 'Salvar'));
        };

        let updateCoverPreview = (url) => {
            if (!coverDropzone.length) {
                return;
            }

            if (url) {
                coverPreview.attr('src', url);
                coverDropzone.addClass('has-image');
                coverPlaceholderText.text(t('backend.editor.change_cover', 'Trocar capa'));
                return;
            }

            coverPreview.removeAttr('src');
            coverDropzone.removeClass('has-image');
            coverPlaceholderText.text(t('backend.editor.upload_cover', 'Subir capa'));
        };

        let setCoverError = (message = '') => {
            if (!coverError.length) {
                return;
            }

            coverError.text(message);
            coverError.toggle(Boolean(message));
        };

        let readImageSize = (file) => {
            return new Promise((resolve, reject) => {
                let image = new Image();
                let objectUrl = URL.createObjectURL(file);

                image.onload = () => {
                    URL.revokeObjectURL(objectUrl);
                    resolve({
                        width: image.naturalWidth,
                        height: image.naturalHeight
                    });
                };

                image.onerror = () => {
                    URL.revokeObjectURL(objectUrl);
                    reject();
                };

                image.src = objectUrl;
            });
        };

        let getMarked = () => window.marked.marked || window.marked;

        let renderMarkdownPreview = () => {
            if (!markdownPreview.length) {
                return;
            }

            let parser = getMarked();
            let raw = contentInput.val() || '';
            let html = parser.parse(raw, {
                breaks: true,
                gfm: true
            });

            markdownPreview.html(window.DOMPurify.sanitize(html, {
                ADD_TAGS: ['video', 'iframe'],
                ADD_ATTR: [
                    'allow',
                    'allowfullscreen',
                    'alt',
                    'class',
                    'controls',
                    'frameborder',
                    'height',
                    'loading',
                    'referrerpolicy',
                    'src',
                    'title',
                    'width'
                ]
            }));

            markdownPreview.find('pre code').each((index, block) => {
                window.hljs.highlightElement(block);
            });
        };

        let setSelection = (start, end) => {
            let input = contentInput.get(0);
            if (!input) {
                return;
            }

            input.focus();
            input.setSelectionRange(start, end);
        };

        let insertTextAtCursor = (text) => {
            let input = contentInput.get(0);
            if (!input) {
                return;
            }

            let value = input.value;
            let start = input.selectionStart;
            let end = input.selectionEnd;
            input.value = value.slice(0, start) + text + value.slice(end);
            contentInput.trigger('input');
            setSelection(start + text.length, start + text.length);
        };

        let replaceText = (search, replacement) => {
            let input = contentInput.get(0);
            if (!input) {
                return;
            }

            let index = input.value.indexOf(search);
            if (index < 0) {
                insertTextAtCursor(replacement);
                return;
            }

            input.value = input.value.slice(0, index) + replacement + input.value.slice(index + search.length);
            contentInput.trigger('input');
            setSelection(index + replacement.length, index + replacement.length);
        };

        let replaceSelection = (formatter) => {
            let input = contentInput.get(0);
            if (!input) {
                return;
            }

            let value = input.value;
            let start = input.selectionStart;
            let end = input.selectionEnd;
            let selected = value.slice(start, end);
            let result = formatter(selected, start, end, value);
            let text = typeof result === 'string' ? result : result.text;
            let selectionStart = typeof result === 'string' ? start : result.selectionStart;
            let selectionEnd = typeof result === 'string' ? start + text.length : result.selectionEnd;

            input.value = value.slice(0, start) + text + value.slice(end);
            contentInput.trigger('input');
            setSelection(selectionStart, selectionEnd);
        };

        let prefixLines = (selected, prefix) => {
            let text = selected || t('backend.editor.samples.text', 'texto');
            return text
                .split('\n')
                .map((line) => prefix + line)
                .join('\n');
        };

        let toolbarActions = {
            heading: (selected) => {
                let text = selected || t('backend.editor.samples.heading', 'Titulo');
                return {
                    text: prefixLines(text, '## '),
                    selectionStart: contentInput.get(0).selectionStart + 3,
                    selectionEnd: contentInput.get(0).selectionStart + 3 + text.length
                };
            },
            bold: (selected, start) => {
                let text = selected || t('backend.editor.samples.bold', 'texto em negrito');
                return {
                    text: '**' + text + '**',
                    selectionStart: start + 2,
                    selectionEnd: start + 2 + text.length
                };
            },
            italic: (selected, start) => {
                let text = selected || t('backend.editor.samples.italic', 'texto em italico');
                return {
                    text: '*' + text + '*',
                    selectionStart: start + 1,
                    selectionEnd: start + 1 + text.length
                };
            },
            quote: (selected) => prefixLines(selected || t('backend.editor.samples.quote', 'citacao'), '> '),
            code: (selected, start) => {
                if (selected.includes('\n')) {
                    return {
                        text: '```\n' + selected + '\n```',
                        selectionStart: start + 4,
                        selectionEnd: start + 4 + selected.length
                    };
                }

                let text = selected || t('backend.editor.samples.code', 'codigo');
                return {
                    text: '`' + text + '`',
                    selectionStart: start + 1,
                    selectionEnd: start + 1 + text.length
                };
            },
            link: (selected, start) => {
                let text = selected || t('backend.editor.samples.link', 'texto do link');
                return {
                    text: '[' + text + '](https://)',
                    selectionStart: start + text.length + 3,
                    selectionEnd: start + text.length + 11
                };
            },
            'unordered-list': (selected) => prefixLines(selected || t('backend.editor.samples.item', 'item'), '- '),
            'ordered-list': (selected) => {
                let text = selected || t('backend.editor.samples.item', 'item');
                return text
                    .split('\n')
                    .map((line, index) => (index + 1) + '. ' + line)
                    .join('\n');
            },
            'task-list': (selected) => prefixLines(selected || t('backend.editor.samples.item', 'item'), '- [ ] ')
        };

        let escapeHtmlAttribute = (value) => {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        };

        let attachmentToReference = (attachment) => {
            let name = escapeHtmlAttribute(attachment.name);
            let url = escapeHtmlAttribute(attachment.url);

            if (attachment.type === 'image') {
                let alt = escapeHtmlAttribute(attachment.alt || attachment.name);
                let width = attachment.width ? ' width="' + attachment.width + '"' : '';
                let height = attachment.height ? ' height="' + attachment.height + '"' : '';
                return '<img' + width + height + ' alt="' + alt + '" src="' + url + '" />';
            }

            if (attachment.type === 'video') {
                return '<video controls src="' + url + '" title="' + name + '"></video>';
            }

            return '[' + attachment.name + '](' + attachment.url + ')';
        };

        let uploadFiles = async (files) => {
            files = Array.from(files || []).filter((file) => {
                return file.type.startsWith('image/') || file.type.startsWith('video/');
            });

            if (!files.length || !window.editorObjectSlug) {
                return;
            }

            let formData = new FormData();
            let placeholders = [];

            files.forEach((file, index) => {
                let placeholder = t('backend.editor.uploading_file', 'Uploading :filename...', {filename: file.name});
                placeholders.push(placeholder);
                formData.append('files[]', file);
                insertTextAtCursor((index === 0 ? '\n' : '') + placeholder + '\n');
            });

            let response = await apiCall({
                url: url + '/' + window.editorObjectSlug + '/attachments',
                method: 'POST',
                data: formData
            });

            if (!response.status || !response.data || !response.data.attachments) {
                placeholders.forEach((placeholder) => {
                    replaceText(placeholder, t('backend.editor.upload_failed', 'Upload failed'));
                });
                return;
            }

            response.data.attachments.forEach((attachment, index) => {
                replaceText(placeholders[index], attachmentToReference(attachment));
            });
        };

        let uploadCover = async (file) => {
            if (!file || !file.type.startsWith('image/') || !window.editorObjectSlug) {
                return;
            }

            setCoverError();

            try {
                let size = await readImageSize(file);
                if (size.width < 1200) {
                    setCoverError(t('backend.editor.cover_min_width_error', 'A imagem precisa ter pelo menos 1200px de largura.'));
                    return;
                }
            } catch (error) {
                setCoverError(t('backend.editor.cover_read_error', 'Nao foi possivel ler essa imagem.'));
                return;
            }

            let formData = new FormData();
            formData.append('cover', file);
            coverDropzone.addClass('is-dragging');

            let response = await apiCall({
                url: url + '/' + window.editorObjectSlug + '/cover',
                method: 'POST',
                data: formData
            });

            coverDropzone.removeClass('is-dragging');

            if (!response.status || !response.data || !response.data.cover_image) {
                setCoverError(response.responseJSON?.message || response.message || t('backend.editor.cover_upload_error', 'Upload da capa falhou.'));
                return;
            }

            coverInput.val(response.data.cover_image);
            updateCoverPreview(response.data.cover_image);
        };

        coverDropzone.on('click', () => {
            coverFileInput.trigger('click');
        });

        coverPreview.on('error', () => {
            coverInput.val('');
            updateCoverPreview('');
            setCoverError(t('backend.editor.cover_missing_error', 'Imagem de capa nao encontrada.'));
        });

        coverFileInput.on('change', (event) => {
            uploadCover(event.target.files[0]);
            event.target.value = '';
        });

        coverDropzone.on('dragenter dragover', (event) => {
            event.preventDefault();
            coverDropzone.addClass('is-dragging');
        });

        coverDropzone.on('dragleave drop', (event) => {
            event.preventDefault();
            coverDropzone.removeClass('is-dragging');
        });

        coverDropzone.on('drop', (event) => {
            let transfer = event.originalEvent.dataTransfer;
            if (transfer && transfer.files && transfer.files.length) {
                uploadCover(transfer.files[0]);
            }
        });

        editor.find('[data-markdown-tab]').on('click', (event) => {
            let tab = $(event.currentTarget).attr('data-markdown-tab');

            editor.find('[data-markdown-tab]').removeClass('active');
            editor.find('[data-markdown-tab="' + tab + '"]').addClass('active');
            editor.find('[data-markdown-pane]').removeClass('active');
            editor.find('[data-markdown-pane="' + tab + '"]').addClass('active');

            if (tab === 'preview') {
                renderMarkdownPreview();
            }
        });

        editor.find('[data-md-action]').on('click', (event) => {
            let action = $(event.currentTarget).attr('data-md-action');
            if (action === 'attachment') {
                attachmentInput.trigger('click');
                return;
            }
            if (toolbarActions[action]) {
                replaceSelection(toolbarActions[action]);
            }
        });

        attachmentInput.on('change', (event) => {
            uploadFiles(event.target.files);
            event.target.value = '';
        });

        contentInput.on('dragenter dragover', (event) => {
            event.preventDefault();
            contentInput.addClass('is-dragging');
        });

        contentInput.on('dragleave drop', (event) => {
            event.preventDefault();
            contentInput.removeClass('is-dragging');
        });

        contentInput.on('drop', (event) => {
            let transfer = event.originalEvent.dataTransfer;
            if (transfer && transfer.files && transfer.files.length) {
                uploadFiles(transfer.files);
            }
        });

        contentInput.on('input', renderMarkdownPreview);

        titleDisplay.on('click', (event) => {
            event.preventDefault();
            editTitle();
        });

        titleDisplay.on('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                editTitle();
            }
        });

        titleInput.on('blur', () => {
            updateTitleDisplay();
            titleShell.removeClass('is-editing');
        });

        titleInput.on('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                titleInput.trigger('blur');
                saveButton.trigger('click');
            }

            if (event.key === 'Escape') {
                titleInput.trigger('blur');
            }
        });

        saveButton.on('click', async () => {
            if (!window.editorObjectSlug) {
                return;
            }

            titleInput.trigger('blur');
            setSaveState('saving');

            let nodeResponse = await apiCall({
                url: url + '/' + window.editorObjectSlug,
                method: 'PUT',
                data: {
                    lang: currentLang,
                    title: titleInput.val(),
                    description: descriptionInput.val(),
                    cover_image: coverInput.val(),
                    content: contentInput.val()
                }
            });

            if (!nodeResponse.status) {
                setSaveState('error');
                return;
            }

            if (nodeResponse.data) {
                setNodeData(nodeResponse.data);
            }

            setSaveState('saved');
        });

        let getNode = (identifier) => {
            return apiCall({
                url: url + '/' + identifier,
                method: 'GET',
            }).then((response) => {
                if (response.status) {
                    setNodeData(response.data);
                }
                return response;
            });
        };

        languageSelect.on('change', () => {
            let newLang = languageSelect.val();
            let identifier = window.editorObjectId || window.editorObjectSlug;
            let path = identifier
                ? '/' + newLang + '/dashboard/nodes/' + identifier + '/edit'
                : '/' + newLang + '/dashboard/nodes/create';

            window.location.assign(path);
        });

        if (url) {
            if (!window.editorObjectSlug) {
                apiCall({
                    url: url,
                    method: 'POST',
                }).then((response) => {
                    if (response.status) {
                        history.replaceState({}, "", "/" + currentLang + "/dashboard/nodes/" + response.data.slug + "/edit");
                        window.editorObjectSlug = response.data.slug;
                        setNodeData(response.data);
                    }
                });
            } else {
                getNode(window.editorObjectSlug);
            }
        }
    }
};

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    domReady();
} else {
    document.addEventListener('DOMContentLoaded', domReady);
}
