<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NodeController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Node::all()
        ]);
    }

    public function show(Request $request, $lang, $slug)
    {
        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found '
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $node
        ]);
    }

    public function store(Request $request, $lang, $slug = false)
    {
        if ($slug) {
            $node = $this->findNode($slug);

            return response()->json([
                'status' => true,
                'data' => [
                    'node' => $node,
                ]
            ]);
        } else {
            $node = new Node();
            $node->title = $request->get('title', '');
            $node->description = $request->get('description', '');
            $node->slug = !empty($node->title) ? Str::slug($node->title) : 's_' . Str::random(7);
            $node->content = $request->get('content', '');
            $node->cover_image = $request->get('cover_image');

            if (auth()->user()->nodes()->save($node)) {
                return response()->json([
                    'status' => true,
                    'data' => $node->toArray()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Node not added'
                ], 500);
            }
        }
    }

    public function update(Request $request, $lang, $slug)
    {
        $this->setLocaleFromRequest($request, $lang);

        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $class = str_replace('\\', '/', Node::class);
        $title = $request->get('title');
        $newSlug = $this->slugFromTitle($node, $class, $title);

        $fields = [
            'title' => $title,
            'description' => $request->get('description'),
            'content' => $request->get('content'),
            'slug' => $newSlug,
        ];

        foreach ($fields as $field => $value) {
            DB::table('translates')->updateOrInsert([
                'object_id' => $node->id,
                'object_class' => $class,
                'field' => $field,
                'lang' => App::getLocale(),
            ], [
                'value' => is_null($value) ? '' : $value,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        DB::table('nodes')
            ->where('id', $node->id)
            ->update([
                'cover_image' => $request->get('cover_image'),
                'updated_at' => now(),
            ]);

        $node->refresh();

        return response()->json([
            'status' => true,
            'data' => $node
        ]);
    }

    private function slugFromTitle(Node $node, string $class, mixed $title): string
    {
        $title = is_null($title) ? '' : trim((string) $title);

        if ($title === '') {
            return $node->slug ?: 's_' . Str::random(7);
        }

        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $suffix = 2;

        while (
            DB::table('translates')
                ->where('object_class', $class)
                ->where('field', 'slug')
                ->where('lang', App::getLocale())
                ->where('value', $slug)
                ->where('object_id', '!=', $node->id)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function uniqueTranslatedSlug(string $baseSlug, string $class, string $lang, ?int $ignoreNodeId = null): string
    {
        $baseSlug = trim($baseSlug) !== '' ? Str::slug($baseSlug) : 's_' . Str::random(7);
        $slug = $baseSlug;
        $suffix = 2;

        while (
            DB::table('translates')
                ->where('object_class', $class)
                ->where('field', 'slug')
                ->where('lang', $lang)
                ->where('value', $slug)
                ->when($ignoreNodeId, fn ($query) => $query->where('object_id', '!=', $ignoreNodeId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function findNode(string|int $identifier): ?Node
    {
        if (is_numeric($identifier)) {
            $node = Node::where('id', $identifier);
        } else {
            $node = Node::whereTranslationExists(['slug' => $identifier]);
        }

        if (auth()->user()) {
            $node->where(['user_id' => auth()->user()->id]);
        }

        return $node->first();
    }

    private function setLocaleFromRequest(Request $request, string $lang): void
    {
        $requestLang = $request->get('lang', $lang);
        if (in_array($requestLang, config('app.available_locales'))) {
            App::setLocale($requestLang);
        }
    }

    public function uploadAttachments(Request $request, $lang, $slug)
    {
        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $files = $request->file('files', []);
        if (!is_array($files)) {
            $files = [$files];
        }

        $attachments = [];
        foreach ($files as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $mime = $file->getMimeType();
            $type = Str::before($mime, '/');
            $allowedMimes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'video/mp4',
                'video/webm',
                'video/ogg',
                'video/quicktime',
            ];

            if (!in_array($type, ['image', 'video']) || !in_array($mime, $allowedMimes)) {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'attachment';
            $filename = $safeName . '-' . Str::uuid() . ($extension ? '.' . $extension : '');
            $path = $file->storeAs('node-attachments/' . $node->id, $filename, 'public');
            $width = null;
            $height = null;

            if ($type === 'image') {
                $size = @getimagesize(Storage::disk('public')->path($path));
                if ($size) {
                    $width = $size[0];
                    $height = $size[1];
                }
            }

            $attachments[] = [
                'name' => $originalName,
                'alt' => pathinfo($originalName, PATHINFO_FILENAME),
                'type' => $type,
                'mime' => $mime,
                'url' => '/storage/' . $path,
                'width' => $width,
                'height' => $height,
            ];
        }

        return response()->json([
            'status' => count($attachments) > 0,
            'data' => [
                'attachments' => $attachments,
            ],
            'message' => count($attachments) > 0 ? null : 'No valid image or video files uploaded'
        ], count($attachments) > 0 ? 200 : 422);
    }

    public function uploadCover(Request $request, $lang, $slug)
    {
        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $file = $request->file('cover');
        if (!$file || !$file->isValid()) {
            return response()->json([
                'status' => false,
                'message' => 'Cover image not found'
            ], 422);
        }

        $mime = $file->getMimeType();
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid cover image type'
            ], 422);
        }

        $size = @getimagesize($file->getPathname());
        if (!$size || $size[0] < 1200) {
            return response()->json([
                'status' => false,
                'message' => 'Cover image must be at least 1200px wide'
            ], 422);
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'cover';
        $filename = $safeName . '-' . Str::uuid() . ($extension ? '.' . $extension : '');
        $path = $file->storeAs('node-covers/' . $node->id, $filename, 'public');
        $url = '/storage/' . $path;

        DB::table('nodes')
            ->where('id', $node->id)
            ->update([
                'cover_image' => $url,
                'updated_at' => now(),
            ]);

        $node->refresh();

        return response()->json([
            'status' => true,
            'data' => [
                'node' => $node,
                'cover_image' => $url,
            ],
        ]);
    }

    public function duplicate(Request $request, $lang, $slug)
    {
        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $class = str_replace('\\', '/', Node::class);
        $now = now();

        $copy = DB::transaction(function () use ($node, $class, $now) {
            $copy = new Node();
            $copy->user_id = $node->user_id;
            $copy->cover_image = $node->cover_image;
            $copy->save();

            $translations = DB::table('translates')
                ->where('object_id', $node->id)
                ->where('object_class', $class)
                ->get();

            foreach ($translations as $translation) {
                $value = $translation->value;

                if ($translation->field === 'slug') {
                    $value = $this->uniqueTranslatedSlug($translation->value . '-copy', $class, $translation->lang);
                }

                DB::table('translates')->insert([
                    'object_id' => $copy->id,
                    'object_class' => $class,
                    'field' => $translation->field,
                    'lang' => $translation->lang,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return $copy;
        });

        $this->setLocaleFromRequest($request, $lang);
        $copy->refresh();

        return response()->json([
            'status' => true,
            'data' => $copy
        ], 201);
    }

    public function destroy(Request $request, $lang, $slug)
    {
        $node = $this->findNode($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $class = str_replace('\\', '/', Node::class);

        $deleted = DB::transaction(function () use ($node, $class) {
            DB::table('translates')
                ->where('object_id', $node->id)
                ->where('object_class', $class)
                ->delete();

            return $node->delete();
        });

        if ($deleted) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Node can not be deleted'
            ], 500);
        }
    }
}
