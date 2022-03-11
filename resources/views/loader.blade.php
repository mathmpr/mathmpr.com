<script>

    makeid = (length) => {
        length = length || 8;
        let result = '';
        let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

    (() => {

        let scripts = document.querySelector('#scripts');
        if (scripts) {
            scripts.content.querySelectorAll('*').forEach((el) => {
                el.setAttribute('data-id', makeid());
            });
        }

        let loadedIds = [];

        function afterLoad() {
            let scripts = document.querySelector('#scripts');
            if (scripts) {
                scripts.content.querySelectorAll('*').forEach((el) => {
                    let clone = el.cloneNode(true);
                    if (clone.classList.contains('on-ready') && !clone.classList.contains('app')) {
                        document.head.appendChild(clone);
                    }
                });
            }
        }

        let appLoaded = () => {
            let scripts = document.querySelector('#scripts');
            if (scripts) {
                scripts.content.querySelectorAll('*').forEach((el) => {
                    if (loadedIds.indexOf(el.getAttribute('data-id')) < 0) {
                        loadedIds.push(el.getAttribute('data-id'));
                        let clone = el.cloneNode(true);
                        if (clone.classList.contains('app')) {
                            document.head.appendChild(clone);
                        }
                    }
                });
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            let _loaded = 0;
            let full_loaded = false;
            let remove_preload_timeout = 500;
            let scripts = document.querySelector('#scripts');

            let removePreload = () => {

                afterLoad();

                setTimeout(() => {
                    let preload = document.querySelector('#preload');
                    if (preload) {
                        preload.classList.add('bye');
                        setTimeout(() => {
                            preload.parentNode.removeChild(preload);
                        }, 400);
                    }
                }, remove_preload_timeout);
            }

            if (scripts) {
                scripts.content.querySelectorAll('*').forEach((el) => {
                    let clone = el.cloneNode(true);
                    if (clone.classList.contains('on-ready')) return;
                    clone.time = 0;
                    if (clone.href || clone.src) {
                        clone.onload = () => {
                            clone.loaded = true;
                            if (clone.src && (clone.src.indexOf('app.js') || clone.src.indexOf('app.min.js'))) {
                                appLoaded();
                            }
                            _loaded++;
                        }
                    } else {
                        _loaded++;
                    }
                    clone.timeout = setInterval(() => {
                        clone.time += 100;
                        let timeout = clone.getAttribute('timeout');
                        if (clone.time >= (timeout ? timeout : 3000)) {
                            clearInterval(clone.timeout);
                            if (!clone.loaded) {
                                console.warn((clone.src ? clone.src : clone.href) + ' exceeded timeout of load time: ' + (timeout ? timeout : 3000) + '. page was loaded without finish/full load of this resource');
                                _loaded++;
                            }
                        }
                        if (_loaded >= scripts.content.querySelectorAll('*').length && !full_loaded) {
                            clearInterval(clone.timeout);
                            full_loaded = true;
                            removePreload();
                        }
                    }, 100);
                    document.head.appendChild(clone);
                });
            } else {
                removePreload();
            }
        });
    })();
</script>
