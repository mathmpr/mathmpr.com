<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Node;
use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use League\CommonMark\CommonMarkConverter;

class NodeController extends Controller
{
    public function view($lang, $slug)
    {
        $node = $this->findNodeBySlug($slug);

        if (!$node) {
            abort(404);
        }

        $similar = Node::latest()
            ->where('id', '!=', $node->id)
            ->limit(8)
            ->get()
            ->filter(fn (Node $node) => $node->title && $node->slug && $node->cover_image)
            ->take(6)
            ->values();

        return Controller::autoDiscoverView('single', [
            'node' => $node,
            'currentNode' => $node,
            'contentHtml' => $this->renderMarkdown((string) $node->content),
            'similar' => $similar,
        ]);
    }

    private function findNodeBySlug(string $slug): ?Node
    {
        $class = str_replace('\\', '/', Node::class);
        $nodeId = DB::table('translates')
            ->where('object_class', $class)
            ->where('field', 'slug')
            ->where('lang', App::getLocale())
            ->where('value', $slug)
            ->value('object_id');

        if (!$nodeId) {
            return null;
        }

        return Node::find($nodeId);
    }

    private function renderMarkdown(string $markdown): string
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        return $this->sanitizeHtml((string) $converter->convert($markdown));
    }

    private function sanitizeHtml(string $html): string
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $document->documentElement;
        if ($root instanceof DOMElement) {
            $this->sanitizeNode($root);
        }

        $result = '';
        foreach ($root->childNodes ?? [] as $child) {
            $result .= $document->saveHTML($child);
        }

        return $result;
    }

    private function sanitizeNode(DOMNode $node): void
    {
        $allowedTags = [
            'a', 'blockquote', 'br', 'code', 'div', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'hr', 'iframe', 'img', 'li', 'ol', 'p', 'pre', 'span', 'strong', 'table', 'tbody',
            'td', 'th', 'thead', 'tr', 'ul', 'video',
        ];

        $allowedAttributes = [
            'a' => ['href', 'title', 'target', 'rel'],
            'code' => ['class'],
            'iframe' => ['allow', 'allowfullscreen', 'class', 'frameborder', 'height', 'loading', 'referrerpolicy', 'src', 'title', 'width'],
            'img' => ['alt', 'class', 'height', 'src', 'title', 'width'],
            'video' => ['class', 'controls', 'height', 'src', 'title', 'width'],
        ];

        foreach (iterator_to_array($node->childNodes) as $child) {
            $this->sanitizeNode($child);
        }

        if (!$node instanceof DOMElement) {
            return;
        }

        $tag = strtolower($node->tagName);
        if (!in_array($tag, $allowedTags)) {
            $this->unwrapNode($node);
            return;
        }

        if ($tag === 'iframe' && !$this->isAllowedYoutubeEmbed($node->getAttribute('src'))) {
            $node->parentNode?->removeChild($node);
            return;
        }

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $allowed = $allowedAttributes[$tag] ?? [];
            if (!in_array($name, $allowed)) {
                $node->removeAttribute($attribute->name);
            }
        }

        if ($tag === 'a') {
            $href = $node->getAttribute('href');
            if (!preg_match('/^(https?:\\/\\/|\\/|#)/', $href)) {
                $node->removeAttribute('href');
            }
            $node->setAttribute('rel', 'noopener');
        }
    }

    private function unwrapNode(DOMElement $node): void
    {
        while ($node->firstChild) {
            $node->parentNode?->insertBefore($node->firstChild, $node);
        }
        $node->parentNode?->removeChild($node);
    }

    private function isAllowedYoutubeEmbed(string $src): bool
    {
        return preg_match('/^https:\\/\\/(www\\.)?(youtube\\.com\\/embed\\/|youtube-nocookie\\.com\\/embed\\/)/', $src) === 1;
    }
}
