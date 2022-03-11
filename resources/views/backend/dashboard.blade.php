@extends('backend.dom')

@section('head')
    <link rel="stylesheet" href="css/backend.css">
    <link rel="stylesheet" href="css/backend/dashboard.css">
@endsection

@section('scripts')
    <script class="on-ready app">
        $(function () {

            let firstRun = true;

            $("#mathmpr-editor .module-selector li").draggable({
                helper: 'clone',
                start: function (event, ui) {
                    this.cloned = $(this).parent().find(this.nodeName.toLowerCase() + ':last-child');
                    this.cloned.css({
                        border: 0
                    });
                },

                revert: function (event, ui) {
                    $(this).data("uiDraggable").originalPosition = {
                        top: $(this).offset().top,
                        left: $(this).offset().left
                    };
                    return !event;
                }
            });
            $("#droppable").droppable({
                drop: function (event, ui) {
                    $(this).append($(ui.draggable[0]).clone())
                }
            });
        });
    </script>
@endsection

@section('main')

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
        </div>
        <div id="droppable">
            k<br>
            k<br>
            k<br>
            k<br>
            k<br>
            k<br>
            k<br>
        </div>
    </div>


@endsection

