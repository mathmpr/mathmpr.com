<?php

namespace App\Utils;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Throwable;

class StaticImage
{
    static function download($url): string
    {
        $path = public_path('images/static/');
        if (!file_exists($path)) {
            File::makeDirectory($path, 0777, true);
        }

        $f_json = public_path('images/static/image_cache.json');

        if (!file_exists($f_json)) {
            file_put_contents($f_json, json_encode([]));
        }

        $json = json_decode(file_get_contents($f_json), true);

        try {
            if (!array_key_exists($url, $json)) {
                $file_name = 'f' . uniqid() . '.webp';
                $file_path = public_path('images/static/' . $file_name);

                $response = (new Client())->request('GET', $url);

                $intervention = Image::make($response->getBody()->getContents());
                $intervention->encode('webp')->save($file_path);
                $json[$url] = URL::to('/') . '/images/static/' . $file_name;

                file_put_contents($f_json, json_encode($json));
                return $json[$url];
            } else {
                return $json[$url];
            }
        } catch (Throwable $exception) {
            return false;
        }
    }
}
