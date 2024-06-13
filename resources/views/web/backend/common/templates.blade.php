<template id="cropper">
    <div>
        <div class="cropper-wrapper">
            <div class="cropper"></div>
            <div class="cropper-information">
                <div>
                    <p>Desired size: <b class="d_size">1000 x 1000</b></p>
                    <p>Image original size: <b class="o_size"></b></p>
                    <p>Preview size: <b class="p_size"></b></p>
                    <p><label for="cropper-x_size">Zoom percentage: </label><input type="number" id="cropper-x_size" class="z_size form-control"/><b></b></p>
                    <p><label for="cropper-x">X on preview: </label><input type="number" id="cropper-x" class="x form-control"/></p>
                    <p><label for="cropper-y">Y on preview: </label><input type="number" id="cropper-y" class="y form-control"/></p>
                </div>
            </div>
            <h5>Live preview</h5>
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

<template id="media-result">
    <div class="media-result">
        <div>
            <img src="" alt="">
        </div>
        <div class="controls row">
            <div class="col-5 buttons">
                <button class="btn btn-primary crop"><i class="fa-solid fa-crop"></i>{{ trans('backend.plugins.media.crop') }}</button>
            </div>
            <div class="col-7">
                <label for="alt"></label>
                <input class="form-control" id="alt" name="alt" placeholder="Alt">
            </div>
        </div>
    </div>
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
    <div></div>
</template>

<template data-module-name="text">
    <div>
        <div class="editor"></div>
    </div>
</template>
