<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaLibraryController extends Controller
{

    public function index(Request $request)
    {
        $all = $request->all();
        $id = $all['id'] ?? 0;
        return response()->json([
            'success' => true,
            'data' => Media::where('id', '>', $id)
                ->limit(20)
                ->get()
                ->all()
        ]);
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        if (!$uploadedFile) {
            $uploadedFile = $request->file('upload');
        }

        if (!$uploadedFile) {
            $type = 'url';
            $filename = $request->url;
            $public_url = $filename;
        } else {
            $type = explode('/', $uploadedFile->getMimeType());
            $type = array_shift($type);

            $filename = $uploadedFile->getClientOriginalName();
            $exp = explode('.', $filename);
            $ext = array_pop($exp);

            $clear_name = Str::slug(join('.', $exp));
            $filename = $clear_name . '.' . $ext;
            $storage = storage_path('app/public/');

            $count = 1;
            while (file_exists($storage . $filename)) {
                $new_filename = $clear_name . '_' . $count . '.' . $ext;
                $filename = $new_filename;
                $count++;
            }

            Storage::disk('local')->putFileAs(
                'public',
                $uploadedFile,
                $filename
            );

            if (str_contains($uploadedFile->getMimeType(), 'image')) {
                $filename_webp = $clear_name . '.webp';
                $intervention = Image::make($storage . $filename);
                unlink($storage . $filename);

                $count = 1;
                while (file_exists($storage . $filename_webp)) {
                    $new_filename = $clear_name . '_' . $count . '.webp';
                    $filename_webp = $new_filename;
                    $count++;
                }
                $filename = $filename_webp;

                $intervention->encode('webp', 80)->save($storage . $filename);
            }

            $public_url = '/storage/' . $filename;
        }

        (new Media([
            'name' => $filename,
            'type' => $type,
            'local' => $public_url
        ]))->save();

        return response()->json([
            'success' => true,
            'media_library' => [
                'type' => $type,
                'filename' => $filename,
                'url' => $type != 'url'
                    ? URL::to('/') . $public_url
                    : $public_url
            ]
        ]);
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
