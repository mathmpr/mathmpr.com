<template id="spotify-iframe">
    <div>
        <div>
            <span><i class="fa-brands fa-spotify"></i>media:spotify</span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <iframe style="border-radius:12px; height: 80px;" src="https://open.spotify.com/embed/track/%s" width="100%" frameBorder="0"
                allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                loading="lazy"></iframe>
    </div>
</template>

<template id="youtube-iframe">
    <div>
        <div>
            <span><i class="fa-brands fa-youtube"></i>media:youtube</span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <iframe width="100%" src="https://www.youtube.com/embed/%s" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
    </div>
</template>

<template id="vimeo-iframe">
    <div>
        <div>
            <span><i class="fa-brands fa-vimeo"></i>media:vimeo</span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <iframe width="100%" src="https://player.vimeo.com/video/%s?title=0&byline=0&portrait=0" frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
    </div>
</template>

<template id="audio-media">
    <div>
        <div>
            <span><i class="fa-solid fa-file-audio"></i></span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <audio controls>
            <source src="" type="">
        </audio>
    </div>
</template>

<template id="video-media">
    <div>
        <div>
            <span><i class="fa-solid fa-file-video"></i></span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <video controls>
            <source src="" type="">
        </video>
    </div>
</template>

<template id="image-media">
    <div>
        <div>
            <span><i class="fa-solid fa-file-image"></i></span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <img src="" alt="">
    </div>
</template>

<template id="general-media">
    <div>
        <div>
            <span><i class="fa-solid fa-file-image"></i></span>
            <button class="btn select">
                <i class="fa-solid fa-check"></i>
                <b>{{ trans('backend.select') }}</b>
            </button>
        </div>
        <i class="fa-solid fa-file"></i>
    </div>
</template>

<div class="modal fade" id="media-library" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="media-library" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Media Library
                    <button class="btn add_new_button">{{ trans('backend.plugins.media_library.add_new') }}</button>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="add_new">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#file" type="button"
                                    role="tab" aria-controls="file" aria-selected="true">
                                <i class="fa-solid fa-file"></i>
                                {{ trans('backend.plugins.media_library.upload_files')  }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#youtube" type="button"
                                    role="tab" aria-controls="youtube" aria-selected="false">
                                <i class="fa-brands fa-youtube"></i>
                                Youtube
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vimeo" type="button"
                                    role="tab" aria-controls="vimeo" aria-selected="false">
                                <i class="fa-brands fa-vimeo"></i>
                                Vimeo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#spotify" type="button"
                                    role="tab" aria-controls="spotify" aria-selected="false">
                                <i class="fa-brands fa-spotify"></i>
                                Spotify
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="upload-tab">
                            <div class="form-group">
                                <label for="file"
                                       class="form-label">{{ trans('backend.plugins.media_library.upload_files')  }}</label>
                                <input type="file" id="file" class="form-control" name="file[]" multiple>
                            </div>
                            <div class="results"></div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="youtube" role="tabpanel" aria-labelledby="youtube-tab">
                            <div class="form-group">
                                <label for="youtube-url" class="form-label">Youtube URL</label>
                                <input type="text" class="form-control" name="url" id="youtube-url"
                                       placeholder="Youtube URL">
                                <span class="error"></span>
                                <span class="success"></span>
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vimeo" role="tabpanel" aria-labelledby="vimeo-tab">
                            <div class="form-group">
                                <label for="vimeo-url" class="form-label">Vimeo URL</label>
                                <input type="text" class="form-control" name="url" id="vimeo-url"
                                       placeholder="Vimeo URL">
                                <span class="error"></span>
                                <span class="success"></span>
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="spotify" role="tabpanel" aria-labelledby="spotify-tab">
                            <div class="form-group">
                                <label for="spotify-url" class="form-label">Spotify Song Link</label>
                                <input type="text" class="form-control" name="url" id="spotify-url"
                                       placeholder="Spotify Song Link">
                                <span class="error"></span>
                                <span class="success"></span>
                            </div>
                            <div class="form-group mt-3 send">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="library">
                    <div class="content row">

                    </div>
                    <div class="pagination">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="selections">
                    <span class="number"></span>
                    {{ trans('backend.plugins.media_library.selected') }}
                </p>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Finalizar seleção</button>
            </div>
        </div>
    </div>
</div>
