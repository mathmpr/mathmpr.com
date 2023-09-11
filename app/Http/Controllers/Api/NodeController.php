<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeContent;
use App\Utils\File\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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
        $node = Node::whereTranslationExists(['slug' => $slug]);
        if (auth()->user()) {
            $node->where(['user_id' => auth()->user()->id]);
        }
        $node = $node
            ->with([
                'contents'
            ])
            ->first();

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

    public function reorder(Request $request, $lang)
    {
        $id = $request->get('content_id');
        $nodeId = $request->get('node_id');
        $oldIndex = $request->get('old_index');
        $newIndex = $request->get('new_index');

        NodeContent::where(['id' => $id])
            ->update(['order' => $newIndex]);

        $builder = NodeContent::where('id', '!=', $id)
            ->where(['node_id' => $nodeId])
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

    protected function handle($type, $object)
    {
        $object = json_decode($object, true);
        switch (strtolower($type)) {
            case 'media':
                $file = (new File(str_replace('//', '/public/', base_path($object['local']))))
                    ->copy(storage_path('app/public/nodes/'));
                $object['local'] = '/storage/nodes/' . $file->getFullName();
                break;
            default:
                break;
        }
        return json_encode($object);
    }

    public function store(Request $request, $lang, $slug = false)
    {
        if ($slug) {
            $node = Node::whereTranslationExists(['slug' => $slug]);
            if (auth()->user()) {
                $node->where(['user_id' => auth()->user()->id]);
            }
            $node = $node->first();

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

    public function update(Request $request, $id)
    {
        $node = auth()->user()->nodes()->find($id);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        $updated = $node->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Node can not be updated'
            ], 500);
        }
    }

    public function destroyContent(Request $request, $lang, $slug)
    {
        $id = $request->get('id');
        /**
         * @var NodeContent $content
         */
        $content = NodeContent::where(['id' => $id])
            ->first();
        $content->deleteMedia();
        if ($content && $content->delete()) {
            return response()->json([
                'status' => true
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Content cannot be deleted'
        ], 500);
    }

    public function destroy(Request $request, $lang, $slug)
    {
        $node = auth()->user()->nodes()->find($slug);

        if (!$node) {
            return response()->json([
                'status' => false,
                'message' => 'Node not found'
            ], 400);
        }

        if ($node->delete()) {
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
