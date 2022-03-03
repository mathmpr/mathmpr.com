<?php

namespace App\Utils;

use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class StaticImage
{
    static function download($url): string
    {
        if (!file_exists(public_path('images/static/'))) {
            mkdir(public_path('images/static/'));
        }

        $f_json = public_path('images/static/image_cache.json');

        if (!file_exists($f_json)) {
            file_put_contents($f_json, json_encode([]));
        }

        $json = json_decode(file_get_contents($f_json), true);

        if (!array_key_exists($url, $json)) {
            $file_name = 'f' . uniqid() . '.webp';
            $file_path = public_path('images/static/' . $file_name);
            $image = file_get_contents($url);
            $intervention = Image::make($image);
            $intervention->encode('webp')->save($file_path);
            $json[$url] = URL::to('/') . '/images/static/' . $file_name;
            file_put_contents($f_json, json_encode($json));
            return $json[$url];
        } else {
            return $json[$url];
        }
    }
}
