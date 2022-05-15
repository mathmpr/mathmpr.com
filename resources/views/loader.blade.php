<script>

    let empty = (param) => {
        return !param || (param instanceof Array && param.length === 0) || (param === '');
    }

    let is_string = (param) => {
        return typeof param === 'string';
    }

    let is_number = (param) => {
        return !isNaN(param);
    }

    let is_int = (param) => {
        return typeof param === 'number' && (parseInt(param) + '') === (param + '');
    }

    let is_float = (param) => {
        return typeof param === 'number' && (parseInt(param) + '') === (param + '');
    }

    let is_array = (param) => {
        return param instanceof Array;
    }

    let is_object = (param) => {
        return typeof param === 'object' && param;
    }

    let is_callable = (param) => {
        return typeof param === 'function';
    }

    let capitalize = (str) => {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    let camelize = (str) => {
        return str.replace(/[^a-z0-9]/gi, ' ').split(' ').map((value, index) => {
            return index > 0 ? capitalize(value) : value;
        }).join('');
    }

    let upper_camelize = (str) => {
        return capitalize(camelize(str));
    }

    let clone = (obj, hash = new WeakMap()) => {
        if (Object(obj) !== obj || obj instanceof Function) return obj;
        if (hash.has(obj)) return hash.get(obj);
        let result;
        try {
            result = new obj.constructor();
        } catch (e) {
            result = Object.create(Object.getPrototypeOf(obj));
        }

        if (obj instanceof Map)
            Array.from(obj, ([key, val]) => result.set(clone(key, hash),
                clone(val, hash)));
        else if (obj instanceof Set)
            Array.from(obj, (key) => result.add(clone(key, hash)));

        hash.set(obj, result);

        return Object.assign(result, ...Object.keys(obj).map(
            key => ({[key]: clone(obj[key], hash)})));
    }

    let getCoords = (elem) => {
        let box = elem.getBoundingClientRect();

        let body = document.body;
        let docEl = document.documentElement;

        let scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
        let scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

        let clientTop = docEl.clientTop || body.clientTop || 0;
        let clientLeft = docEl.clientLeft || body.clientLeft || 0;

        let top = box.top + scrollTop - clientTop;
        let left = box.left + scrollLeft - clientLeft;

        return {top: Math.round(top), left: Math.round(left)};
    }

    let makeid = (length) => {
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

    if (!String.prototype.format) {
        String.prototype.format = function () {
            let args = arguments;
            return this.replace(/{(\d+)}/g, function (match, number) {
                return typeof args[number] != 'undefined'
                    ? args[number]
                    : match;
            });
        };
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
                    if (clone.classList.contains('on-ready') || clone.classList.contains('app')) return;
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
