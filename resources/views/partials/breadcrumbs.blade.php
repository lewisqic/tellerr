<ol class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)
        @if (!$breadcrumb->last)
            <li class="breadcrumb-item">
                <a href="{{ $breadcrumb->url }}">
                    @if ( isset($breadcrumb->icon) )
                        <i class="fa {{ $breadcrumb->icon }}"></i>
                    @endif
                    {{ $breadcrumb->title }}
                </a>
            </li>
        @else
            <li class="breadcrumb-item active">
                @if ( isset($breadcrumb->icon) )
                    <i class="fa {{ $breadcrumb->icon }}"></i>
                @endif
                {{ $breadcrumb->title }}
            </li>
        @endif
    @endforeach
</ol>