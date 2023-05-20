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
    <div>
    </div>
</template>

<template data-module-name="text">
    <div>
        <div class="editor"></div>
    </div>
</template>
