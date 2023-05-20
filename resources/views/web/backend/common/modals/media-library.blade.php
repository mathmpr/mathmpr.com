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
                            <input type="file" class="form-control" name="file[]" multiple>
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
