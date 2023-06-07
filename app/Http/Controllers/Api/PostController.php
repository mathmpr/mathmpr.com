<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Post::all()
        ]);
    }

    public function show(Request $request, $lang, $slug)
    {
        $post = Post::whereTranslationExists(['slug' => $slug]);
        if (auth()->user()) {
            $post->where(['user_id' => auth()->user()->id]);
        }
        $post = $post
            ->with([
                'contents'
            ])
            ->first();

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found '
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $post
        ]);
    }

    public function reorder(Request $request, $lang)
    {
        $id = $request->get('content_id');
        $postId = $request->get('post_id');
        $oldIndex = $request->get('old_index');
        $newIndex = $request->get('new_index');

        PostContent::where(['id' => $id])
            ->update(['order' => $newIndex]);

        $builder = PostContent::where('id', '!=', $id)
            ->where(['post_id' => $postId])
            ->where(['lang' => App::getLocale()]);
        if ($newIndex > $oldIndex) {
            $builder->where('order', '>=', $oldIndex)
                ->where('order', '<=', $newIndex)
                ->update(['order' => DB::raw('`order` - 1')]);
        } else {
            $builder->where('order', '>=', $newIndex)
                ->where('order', '<=', $oldIndex)
                ->update(['order' => DB::raw('`order` + 1')]);
        }
    }

    public function store(Request $request, $lang, $slug = false)
    {
        if ($slug) {
            $post = Post::whereTranslationExists(['slug' => $slug]);
            if (auth()->user()) {
                $post->where(['user_id' => auth()->user()->id]);
            }
            $post = $post->first();
            if ($request->get('object') && $post) {
                $content = PostContent::select(['order'])
                    ->where(['post_id' => $post->id])
                    ->where(['lang' => App::getLocale()])
                    ->orderBy('order', 'desc')
                    ->limit(1)
                    ->first();
                if ($content) {
                    $order = $content->order + 1;
                } else {
                    $order = 0;
                }

                $content = new PostContent([
                    'post_id' => $post->id,
                    'order' => $order,
                    'content' => $request->get('object'),
                    'lang' => App::getLocale(),
                    'type' => $request->get('type'),
                ]);

                if ($content->save()) {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'post' => $post,
                            'content' => $content,
                        ]
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'post' => $post,
                ]
            ]);
        } else {
            $post = new Post();
            $post->title = $request->get('title', '');
            $post->description = $request->get('description', '');
            $post->slug = !empty($post->title) ? Str::slug($post->title) : 's_' . Str::random(7);

            if (auth()->user()->posts()->save($post)) {
                return response()->json([
                    'status' => true,
                    'data' => $post->toArray()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Post not added'
                ], 500);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Post can not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
