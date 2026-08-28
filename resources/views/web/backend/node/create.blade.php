@extends('web.backend.dom')

@section('title') {{ trans($id ? 'backend.nodes.edit_title' : 'backend.nodes.create_title') }} @endsection

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/dashboard.css">
    <style>
        .node-editor-page {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .node-title-row,
        .node-editor-panel {
            background: var(--surface-bg-color);
            border: 1px solid var(--lines);
            border-radius: 5px;
            overflow: hidden;
        }

        .node-title-row {
            min-height: 64px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            background: transparent;
            border: none;
        }

        .node-title-shell {
            flex: 1 1 auto;
            min-width: 0;
        }

        .node-language-select {
            width: 86px;
            height: 38px;
            flex: 0 0 auto;
            color: var(--text-color);
            background-color: var(--surface-bg-color);
            border-color: var(--lines);
            font-weight: 650;
            text-transform: uppercase;
        }

        .node-language-select:focus {
            color: var(--text-color);
            background-color: var(--surface-bg-color);
            border-color: var(--primary-bg);
            box-shadow: 0 0 0 .25rem rgba(27, 197, 189, .18);
        }

        .node-language-select option {
            color: var(--text-color);
            background-color: var(--surface-bg-color);
        }

        .dark .node-language-select {
            border-color: #2b2b2b;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23d7dde8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }

        .dark .node-language-select:focus {
            border-color: #2b2b2b;
        }

        .node-title-display,
        .node-title-input {
            width: 100%;
            min-height: 38px;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            background: transparent;
            border: 0;
            font-size: 26px;
            font-weight: 650;
            line-height: 1.25;
        }

        .node-title-display {
            display: block;
            cursor: text;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .node-title-display:focus {
            outline: 0;
            box-shadow: 0 0 0 3px rgba(27, 197, 189, .18);
        }

        .node-title-display.is-empty {
            color: #a1a5b7;
        }

        .node-title-input {
            display: none;
            border-radius: 5px;
            outline: none;
        }

        .node-title-input:focus {
            box-shadow: 0 0 0 3px rgba(27, 197, 189, .18);
        }

        .node-title-shell.is-editing .node-title-display {
            display: none;
        }

        .node-title-shell.is-editing .node-title-input {
            display: block;
        }

        .node-save-button {
            flex: 0 0 auto;
            min-width: 116px;
        }

        .node-meta-row {
            display: grid;
            grid-template-columns: 160px minmax(0, 1fr);
            gap: 16px;
            padding: 16px;
        }

        .node-description-field,
        .node-cover-field {
            min-width: 0;
        }

        .node-meta-row label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted-text-color);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        #description {
            height: 160px;
            min-height: 160px;
            max-height: 160px;
            resize: none;
            border-color: var(--lines);
            color: var(--text-color);
            background: var(--surface-bg-color);
        }

        .node-cover-dropzone {
            width: 100%;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            color: var(--muted-text-color);
            background: var(--surface-muted-bg-color);
            border: 1px dashed var(--lines);
            border-radius: 5px;
            cursor: pointer;
        }

        .node-cover-dropzone:hover,
        .node-cover-dropzone:focus,
        .node-cover-dropzone.is-dragging {
            color: var(--text-color);
            background: var(--control-hover-bg-color);
            border-color: var(--primary-bg);
            outline: 0;
        }

        .node-cover-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
            z-index: 2;
            gap: 8px;
            padding: 12px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .node-cover-placeholder i {
            font-size: 22px;
        }

        .node-cover-hint,
        .node-cover-error {
            margin-top: 7px;
            font-size: 11px;
            line-height: 1.35;
        }

        .node-cover-hint {
            color: #7e8299;
        }

        .node-cover-error {
            display: none;
            color: var(--danger-color);
        }

        .node-cover-preview {
            width: 100%;
            height: 100%;
            display: none;
            position: absolute;
            inset: 0;
            object-fit: cover;
        }

        .node-cover-dropzone.has-image .node-cover-placeholder {
            width: 100%;
            height: 100%;
            color: #fff;
            background: rgba(15, 23, 42, .38);
            opacity: 0;
            transition: opacity .15s ease-in-out;
        }

        .node-cover-dropzone.has-image .node-cover-preview {
            display: block;
        }

        .node-cover-dropzone.has-image:hover .node-cover-placeholder,
        .node-cover-dropzone.has-image:focus .node-cover-placeholder {
            opacity: 1;
        }

        .node-cover-input {
            display: none;
        }

        .markdown-editor {
            border-top: 1px solid var(--lines);
        }

        .markdown-editor-tabs {
            min-height: 48px;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 12px;
            padding: 0 12px;
            background: var(--surface-muted-bg-color);
            border-bottom: 1px solid var(--lines);
        }

        .markdown-tabs {
            display: flex;
            align-items: stretch;
        }

        .markdown-tab {
            min-width: 78px;
            padding: 0 16px;
            color: var(--muted-text-color);
            background: transparent;
            border: 0;
            border-right: 1px solid transparent;
            border-left: 1px solid transparent;
            font-size: 13px;
            font-weight: 600;
        }

        .markdown-tab.active {
            color: var(--text-color);
            background: var(--surface-bg-color);
            border-color: var(--lines);
            border-bottom: 1px solid var(--surface-bg-color);
            margin-bottom: -1px;
        }

        .markdown-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
            min-width: 0;
            padding: 7px 0;
            overflow-x: auto;
        }

        .markdown-tool {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            color: var(--muted-text-color);
            background: transparent;
            border: 1px solid transparent;
            border-radius: 5px;
            font-size: 14px;
        }

        .markdown-tool:hover,
        .markdown-tool:focus {
            color: var(--text-color);
            background: var(--control-hover-bg-color);
            border-color: var(--lines);
            outline: 0;
        }

        .markdown-tool-separator {
            width: 1px;
            height: 22px;
            flex: 0 0 auto;
            margin: 0 5px;
            background: var(--lines);
        }

        .markdown-pane {
            display: none;
        }

        .markdown-pane.active {
            display: block;
        }

        #content {
            width: 100%;
            min-height: calc(100vh - 362px);
            padding: 18px;
            color: var(--text-color);
            background: var(--surface-bg-color);
            border: 0;
            border-radius: 0;
            resize: vertical;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        #content:focus {
            outline: 0;
            box-shadow: inset 0 0 0 2px rgba(27, 197, 189, .22);
        }

        #content.is-dragging {
            background: var(--control-hover-bg-color);
            box-shadow: inset 0 0 0 2px var(--primary-bg);
        }

        .markdown-attachment-input {
            display: none;
        }

        .markdown-preview {
            min-height: calc(100vh - 362px);
            padding: 24px;
            color: var(--text-color);
            background: var(--surface-bg-color);
            font-size: 15px;
            line-height: 1.7;
        }

        .markdown-preview:empty::before {
            content: "{{ trans('backend.editor.empty_preview') }}";
            color: var(--muted-text-color);
        }

        .markdown-preview h1,
        .markdown-preview h2,
        .markdown-preview h3 {
            margin: 1.25em 0 .65em;
            font-weight: 650;
        }

        .markdown-preview h1:first-child,
        .markdown-preview h2:first-child,
        .markdown-preview h3:first-child,
        .markdown-preview p:first-child {
            margin-top: 0;
        }

        .markdown-preview pre {
            padding: 14px 16px;
            overflow-x: auto;
            background: var(--code-bg-color);
            border: 1px solid var(--lines);
            border-radius: 5px;
        }

        .markdown-preview code {
            padding: 2px 5px;
            background: var(--code-bg-color);
            border-radius: 4px;
            font-size: .92em;
        }

        .markdown-preview pre code {
            padding: 0;
            background: transparent;
        }

        .markdown-preview .hljs {
            background: var(--code-bg-color);
        }

        .markdown-preview blockquote {
            margin: 1em 0;
            padding-left: 14px;
            color: var(--muted-text-color);
            border-left: 4px solid var(--lines);
        }

        .markdown-preview iframe {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 16 / 9;
            height: auto;
            border: 0;
            border-radius: 5px;
        }

        @media (max-width: 767px) {
            .node-title-row {
                align-items: stretch;
                flex-direction: column;
            }

            .node-save-button {
                width: 100%;
            }

            .node-meta-row {
                grid-template-columns: 1fr;
            }

            .node-cover-field {
                max-width: 180px;
            }

            .markdown-editor-tabs {
                flex-direction: column;
                padding: 0;
            }

            .markdown-toolbar {
                justify-content: flex-start;
                padding: 8px 12px;
            }
        }
    </style>
@endsection

@section('main')

    <div class="container node-editor-page">
        <div class="node-title-row">
            <select class="form-select node-language-select" id="node-language" aria-label="{{ trans('backend.editor.language') }}">
                @foreach(config('app.available_locales') as $locale)
                    <option value="{{ $locale }}" @selected($locale === $lang)>{{ strtoupper($locale) }}</option>
                @endforeach
            </select>

            <div class="node-title-shell" id="node-title-shell">
                <span class="node-title-display is-empty" id="node-title-display" role="button" tabindex="0">{{ trans('backend.editor.untitled') }}</span>
                <input class="node-title-input" id="title" name="title" placeholder="{{ trans('backend.editor.untitled') }}" autocomplete="off">
            </div>
            <button class="btn btn-primary node-save-button" id="node-save" type="button">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ trans('backend.editor.save') }}
            </button>
        </div>

        <div class="node-editor-panel">
            <div class="node-meta-row">
                <div class="node-cover-field">
                    <label for="node-cover-input">{{ trans('backend.editor.cover') }}</label>
                    <button class="node-cover-dropzone" id="node-cover-dropzone" type="button">
                        <span class="node-cover-placeholder">
                            <i class="fa-solid fa-image"></i>
                            <span class="node-cover-placeholder-text">{{ trans('backend.editor.upload_cover') }}</span>
                        </span>
                        <img class="node-cover-preview" id="node-cover-preview" alt="">
                    </button>
                    <div class="node-cover-hint">{{ trans('backend.editor.cover_hint') }}</div>
                    <div class="node-cover-error" id="node-cover-error"></div>
                    <input class="node-cover-input" id="node-cover-input" type="file" accept="image/jpeg,image/png,image/gif,image/webp">
                    <input id="cover_image" name="cover_image" type="hidden">
                </div>

                <div class="node-description-field">
                    <label for="description">{{ trans('backend.editor.description') }}</label>
                    <textarea class="form-control" id="description" name="description" placeholder="{{ trans('backend.editor.description_placeholder') }}"></textarea>
                </div>
            </div>

            <div id="mathmpr-editor" data-id="{{$id}}" data-url="/api/{{ $lang }}/nodes">
                <div class="markdown-editor">
                    <div class="markdown-editor-tabs">
                        <div class="markdown-tabs" role="tablist">
                            <button class="markdown-tab active" data-markdown-tab="write" type="button">{{ trans('backend.editor.write') }}</button>
                            <button class="markdown-tab" data-markdown-tab="preview" type="button">{{ trans('backend.editor.preview') }}</button>
                        </div>

                        <div class="markdown-toolbar" aria-label="{{ trans('backend.editor.markdown_tools') }}">
                            <button class="markdown-tool" data-md-action="heading" type="button" title="{{ trans('backend.editor.toolbar.heading') }}">
                                <i class="fa-solid fa-heading"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="bold" type="button" title="{{ trans('backend.editor.toolbar.bold') }}">
                                <i class="fa-solid fa-bold"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="italic" type="button" title="{{ trans('backend.editor.toolbar.italic') }}">
                                <i class="fa-solid fa-italic"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="quote" type="button" title="{{ trans('backend.editor.toolbar.quote') }}">
                                <i class="fa-solid fa-quote-left"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="code" type="button" title="{{ trans('backend.editor.toolbar.code') }}">
                                <i class="fa-solid fa-code"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="link" type="button" title="{{ trans('backend.editor.toolbar.link') }}">
                                <i class="fa-solid fa-link"></i>
                            </button>
                            <span class="markdown-tool-separator"></span>
                            <button class="markdown-tool" data-md-action="unordered-list" type="button" title="{{ trans('backend.editor.toolbar.unordered_list') }}">
                                <i class="fa-solid fa-list-ul"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="ordered-list" type="button" title="{{ trans('backend.editor.toolbar.ordered_list') }}">
                                <i class="fa-solid fa-list-ol"></i>
                            </button>
                            <button class="markdown-tool" data-md-action="task-list" type="button" title="{{ trans('backend.editor.toolbar.task_list') }}">
                                <i class="fa-solid fa-list-check"></i>
                            </button>
                            <span class="markdown-tool-separator"></span>
                            <button class="markdown-tool" data-md-action="attachment" type="button" title="{{ trans('backend.editor.toolbar.attachment') }}">
                                <i class="fa-solid fa-paperclip"></i>
                            </button>
                            <input class="markdown-attachment-input" id="markdown-attachment-input" type="file" accept="image/*,video/*" multiple>
                        </div>
                    </div>

                    <div class="markdown-pane active" data-markdown-pane="write">
                        <textarea id="content" name="content" spellcheck="false" placeholder="{{ trans('backend.editor.content_placeholder') }}"></textarea>
                    </div>

                    <div class="markdown-pane" data-markdown-pane="preview">
                        <div class="markdown-preview" id="markdown-preview"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
