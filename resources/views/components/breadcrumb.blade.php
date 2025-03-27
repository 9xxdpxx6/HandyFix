<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        @foreach($items as $item)
            @if($item['active'])
                <li class="breadcrumb-item active" aria-current="page">
                    <span>{{ $item['name'] }}</span>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav> 