<script>
    (() => {
        document.addEventListener('DOMContentLoaded', () => {
            let _loaded = 0;
            let full_loaded = false;
            let remove_preload_timeout = 500;
            let scripts = document.querySelector('#scripts');

            let removePreload = () => {
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
                    if(clone.classList.contains('on-ready')) return;
                    clone.time = 0;
                    clone.onload = () => {
                        clone.loaded = true;
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
