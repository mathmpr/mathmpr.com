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
        $offset = $all['offset'] ?? 0;
        $limit = $all['limit'] ?? 18;
        $types = $all['types'] ?? [];


        $medias = Media::limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'desc');

        if (!empty($types) && is_array($types)) {
            $count = Media::whereIn('type', $types)->count();
            $medias->whereIn('type', $types);
        } else {
            $count = Media::count();
        }

        $total_pages = $count / $limit;

        return response()->json([
            'success' => true,
            'page' => ((int)($offset / $limit)) + 1,
            'total_pages' => is_float($total_pages) ? ((int)$total_pages + 1) : $total_pages,
            'data' => $medias
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
            $type = $request->get('type');
            $filename = $request->get('url');
            $public_url = $filename;
            $mime = 'url';
        } else {
            $mime = $uploadedFile->getMimeType();
            $type = explode('/', $uploadedFile->getMimeType());
            $type = array_shift($type);
            if (!in_array($type, ['image', 'video'])) {
                $type = 'document';
            }

            $filename = $uploadedFile->getClientOriginalName();
            $exp = explode('.', $filename);
            $ext = array_pop($exp);

            $extensions = [
                'pdf' => ['pdf'],
                'document' => ['doc', 'docx'],
                'sheet' => ['xls', 'xlsx', 'csv'],
                'zipped' => ['zip', 'rar', 'gz'],
                'text' => ['txt']
            ];

            foreach ($extensions as $_type => $extension) {
                if (in_array($ext, $extension)) {
                    $type = $_type;
                }
            }

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

            if (str_contains($uploadedFile->getMimeType(), 'image')
                && !str_contains($uploadedFile->getMimeType(), '/gif')) {
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
            'local' => $public_url,
            'mine' => $mime
        ]))->save();

        return response()->json([
            'success' => true,
            'media_library' => [
                'type' => $type,
                'name' => $filename,
                'mine' => $mime,
                'local' => in_array($type, ['image', 'video'])
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
