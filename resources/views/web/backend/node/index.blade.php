@extends('web.backend.dom')

@section('title') {{ trans('backend.nodes.title') }} @endsection

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/dashboard.css">
    <style>
        .nodes-page {
            padding-top: 30px;
        }

        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 22px;
        }

        .page-title {
            margin: 0 0 4px;
            color: var(--text-color);
            font-size: 23px;
            font-weight: 650;
        }

        .page-subtitle {
            margin: 0;
            color: var(--muted-text-color);
            font-size: 13px;
        }

        .panel {
            overflow: hidden;
            background: var(--surface-bg-color);
            border: 1px solid var(--lines);
            border-radius: 5px;
        }

        .panel-header {
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 16px;
            border-bottom: 1px solid var(--lines);
        }

        .panel-title {
            margin: 0;
            color: var(--text-color);
            font-size: 13px;
            font-weight: 650;
        }

        .panel-body {
            padding: 16px;
        }

        .table-finora {
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--lines);
            --bs-table-color: var(--text-color);
            --bs-table-hover-color: var(--text-color);
            --bs-table-hover-bg: var(--surface-hover-bg-color);
            width: 100%;
            margin-bottom: 0;
            font-size: 13px;
            vertical-align: middle;
        }

        .table-finora thead th {
            padding: 12px 16px;
            color: var(--muted-text-color);
            border-bottom-color: var(--lines);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            white-space: nowrap;
        }

        .table-finora tbody td {
            padding: 13px 16px;
            border-color: var(--lines);
            color: var(--text-color);
        }

        .table-finora tbody tr:hover {
            background: var(--surface-hover-bg-color);
        }

        .table-finora tbody tr:hover td {
            color: var(--text-color);
        }

        .table-finora tbody tr:hover .nodes-title {
            color: var(--strong-text-color);
        }

        .table-finora tbody tr:hover code,
        .table-finora tbody tr:hover .text-muted {
            color: inherit;
        }

        .nodes-title {
            color: var(--strong-text-color);
            font-weight: 600;
        }

        .nodes-description {
            max-width: 460px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nodes-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            white-space: nowrap;
        }

        .node-delete-title {
            color: var(--strong-text-color);
        }

        .node-delete-error {
            display: none;
        }

        @media (max-width: 767px) {
            .page-head,
            .panel-header,
            .panel-body {
                align-items: flex-start;
                flex-direction: column;
            }

            .nodes-description {
                max-width: 240px;
            }
        }
    </style>
@endsection

@section('main')

    <div class="container nodes-page">
        <div class="page-head">
            <div>
                <h1 class="page-title">{{ trans('backend.nodes.title') }}</h1>
                <p class="page-subtitle">{{ trans('backend.nodes.subtitle') }}</p>
            </div>
            <div>
                <a class="btn btn-primary" href="/{{ $lang }}/dashboard/nodes/create">
                    <i class="fa-solid fa-plus"></i>
                    {{ trans('backend.nodes.new') }}
                </a>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div class="d-flex align-items-center gap-2">
                    <h2 class="panel-title">{{ trans('backend.nodes.panel_title') }}</h2>
                    <span class="badge text-bg-secondary">{{ trans('backend.nodes.records', ['count' => $nodes->total()]) }}</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-finora table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ trans('backend.nodes.table.title') }}</th>
                            <th scope="col">{{ trans('backend.nodes.table.slug') }}</th>
                            <th scope="col">{{ trans('backend.nodes.table.description') }}</th>
                            <th scope="col">{{ trans('backend.nodes.table.updated_at') }}</th>
                            <th scope="col" class="text-end">{{ trans('backend.nodes.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($nodes as $node)
                            @php
                                $slug = $node->slug;
                                $title = $node->title ?: ($slug ?: trans('backend.nodes.fallback_title', ['id' => $node->id]));
                                $description = $node->description ?: '-';
                                $editUrl = $slug
                                    ? "/{$lang}/dashboard/nodes/{$slug}/edit"
                                    : "/{$lang}/dashboard/nodes/{$node->id}/edit";
                            @endphp
                            <tr data-node-row="{{ $node->id }}">
                                <td class="nodes-title">{{ $title }}</td>
                                <td>
                                    @if($slug)
                                        <code>{{ $slug }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="nodes-description" title="{{ $description }}">{{ $description }}</td>
                                <td>{{ optional($node->updated_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="nodes-actions">
                                        @if($slug)
                                            <a class="btn btn-outline-secondary btn-sm" href="/{{ $lang }}/{{ $slug }}" target="_blank" rel="noopener">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-outline-primary btn-sm" href="{{ $editUrl }}">
                                            <i class="fa-solid fa-pen"></i>
                                            {{ trans('backend.nodes.edit') }}
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm node-duplicate-trigger"
                                                type="button"
                                                data-node-url="/api/{{ $lang }}/nodes/{{ $node->id }}/duplicate">
                                            <i class="fa-solid fa-copy"></i>
                                            {{ trans('backend.nodes.duplicate') }}
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm node-delete-trigger"
                                                type="button"
                                                data-node-id="{{ $node->id }}"
                                                data-node-title="{{ $title }}"
                                                data-node-url="/api/{{ $lang }}/nodes/{{ $node->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                            {{ trans('backend.nodes.delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                    @endforeach

                    @if($nodes->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                {{ trans('backend.nodes.empty') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            @if($nodes->hasPages())
                <div class="panel-body d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">{{ trans('backend.nodes.pagination.page', ['current' => $nodes->currentPage(), 'last' => $nodes->lastPage()]) }}</span>
                    <div class="d-flex gap-2">
                        @if($nodes->onFirstPage())
                            <button class="btn btn-sm btn-outline-secondary" type="button" disabled>{{ trans('backend.nodes.pagination.previous') }}</button>
                        @else
                            <a class="btn btn-sm btn-outline-secondary" href="{{ $nodes->previousPageUrl() }}">{{ trans('backend.nodes.pagination.previous') }}</a>
                        @endif

                        @if($nodes->hasMorePages())
                            <a class="btn btn-sm btn-outline-secondary" href="{{ $nodes->nextPageUrl() }}">{{ trans('backend.nodes.pagination.next') }}</a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" type="button" disabled>{{ trans('backend.nodes.pagination.next') }}</button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="nodeDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h1 class="modal-title fs-6">{{ trans('backend.nodes.delete_modal.title') }}</h1>
                        <div class="text-secondary small mt-1">{{ trans('backend.nodes.delete_modal.subtitle') }}</div>
                    </div>
                    <button type="button" class="btn-close node-delete-cancel" aria-label="{{ trans('backend.nodes.delete_modal.cancel') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">{{ trans('backend.nodes.delete_modal.question') }}</p>
                    <div class="fw-semibold node-delete-title" id="node-delete-title"></div>
                    <div class="text-danger small mt-2">{{ trans('backend.nodes.delete_modal.warning') }}</div>
                    <div class="text-danger small mt-2 node-delete-error" id="node-delete-error">
                        {{ trans('backend.nodes.delete_modal.error') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary node-delete-cancel" type="button">
                        {{ trans('backend.nodes.delete_modal.cancel') }}
                    </button>
                    <button class="btn btn-danger" id="node-delete-confirm" type="button">
                        {{ trans('backend.nodes.delete_modal.confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
