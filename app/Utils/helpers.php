<?php

use Symfony\Component\CssSelector\CssSelectorConverter;
use Illuminate\Support\Facades\URL;
use App\Utils\Lang;

/**
 * render and get result html from view
 * find all href and src in all generated DOM
 * if is not an url (local url) add base_url to root of current site
 * util if you have a site running in two different env that one of this run in complex path like http://<domain>/<folder>/
 *
 * @param $view
 * @return false|string
 */
function postView($view): bool|string
{
    $dom = new DOMDocument();
    $render = $view->render();
    if (empty($render)) {
        return $view;
    }
    try {
        $dom->loadHTML($render, LIBXML_NOERROR);
    } catch (\Exception $e) {
        return $view;
    }
    $xpath = (new DOMXPath($dom))->query((new CssSelectorConverter())->toXPath('[src],[href],[data-src],[datasrc]'));
    if ($xpath && $xpath->length > 0) {
        foreach ($xpath as $item) {
            foreach (['href', 'src', 'data-src', 'datasrc'] as $attr) {
                $value = $item->getAttribute($attr);

                if (!empty($value)) {
                    $value = ltrim($value, '/');
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        if ($item->nodeName == 'a') {
                            $value = URL::to('/') . '/' . Lang::$lang . '/' . $value;
                        } else {
                            $value = URL::to('/') . '/' . $value;
                        }
                        $item->setAttribute($attr, $value);
                    }
                }
            }
        }
    }

    $xpath = (new DOMXPath($dom))->query(
        (new CssSelectorConverter())->toXPath('#scripts + script, #scripts + style, #scripts + link')
    );
    while ($xpath->length) {
        foreach ($xpath as $item) {
            $item->parentNode->removeChild($item);
        }
        $xpath = (new DOMXPath($dom))->query(
            (new CssSelectorConverter())->toXPath('#scripts + script, #scripts + style, #scripts + link')
        );
    }
    return $dom->saveHTML();
}

function isApiCall(): bool
{
    return str_contains(request()->path(), 'api/');
}
