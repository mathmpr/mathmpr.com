@php
    $image = $post->cover_image ?: '/images/mathmpr.jpg';
    $description = $post->description ?: '';
@endphp

<div class="home-node-card {{ $class ?? 'col-lg-3' }}">
    <img
        src="{{ $image }}"
        alt="{{ $post->title }}"
        width="800"
        height="800"
        decoding="async"
        loading="{{ $eager ?? false ? 'eager' : 'lazy' }}"
        @if($eager ?? false) fetchpriority="high" @endif
    >
    <a href="/{{ $lang }}/{{ $post->slug }}">
        <h3>{{ $post->title }}</h3>
        @if($description)
            <p>{{ $description }}</p>
        @endif
        <em></em>
    </a>
</div>
