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

class NodeContentController extends Controller
{
    /**
     * @param $request
     * @param $lang
     * @param $slug
     * @return array(string|false, Node|false, NodeContent|false)|false[]
     */
    private function getRequiredData($request, $lang, $slug): array
    {
        $node = Node::whereTranslationExists(['slug' => $slug]);
        if (auth()->user()) {
            $node->where(['user_id' => auth()->user()->id]);
        }
        $node = $node->first();
        $object = $request->get('object');
        if ($object && $node) {
            $id = $request->get('id');
            $content = null;
            if ($id) {
                $content = NodeContent::where(['id' => $id])
                    ->where(['lang' => $lang])
                    ->orderBy('order', 'desc')
                    ->limit(1)
                    ->first();
                if (!$content || $content->node_id != $node->id) {
                    return [false, false, false];
                }
                return [
                    'update',
                    $node,
                    $content,
                ];
            }
            return [
                'insert',
                $node,
                $content,
            ];
        }
        return [false, false, false];
    }

    public function reorder(Request $request, $lang, $slug)
    {
        $oldIndex = $request->get('old_index');
        $newIndex = $request->get('new_index');

        list($action, $node, $content) = $this->getRequiredData($request, $lang, $slug);

        if ($content) {
            $content->order = $newIndex;
            $content->save();
        } else {
            return;
        }

        $builder = NodeContent::where('id', '!=', $content->id)
            ->where(['node_id' => $content->id])
            ->where(['lang' => $lang]);
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
        list($action, $node, $content) = $this->getRequiredData($request, $lang, $slug);
        if ($action === 'insert') {
            $order = $request->get('order');
            $addOrder = false;
            if ($order === null) {
                if ($content) {
                    $order = $content->order + 1;
                } else {
                    $order = 0;
                }
            } else {
                $addOrder = true;
            }
            $type = $request->get('type');
            $contentObject = $this->handle($type, $request->get('object'));
            $content = new NodeContent([
                'node_id' => $node->id,
                'order' => $order,
                'content' => $contentObject,
                'lang' => $lang,
                'type' => $type,
            ]);
            if ($content->save()) {
                if ($addOrder) {
                    NodeContent::where('id', '!=', $content->id)
                        ->where(['node_id' => $content->node_id])
                        ->where(['lang' => $lang])
                        ->where('order', '>=', $order)
                        ->update(['order' => DB::raw('`order` + 1')]);
                }
                return response()->json([
                    'status' => true,
                    'data' => [
                        'node' => $node,
                        'content' => $content,
                    ]
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'update' => 'update',
                'data' => [
                    'node' => $node,
                    'content' => $content,
                ]
            ]);
        }

        return response(400)->json([
            'status' => false,
        ]);
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
