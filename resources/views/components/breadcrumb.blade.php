<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        @foreach($items as $item)
            @php
                // Заменяем "Home" на "Главная" в хлебных крошках
                $itemName = $item['name'] === 'Home' ? 'Главная' : $item['name'];
            @endphp
            
            @if($item['active'])
                <li class="breadcrumb-item active" aria-current="page">
                    <span>{{ $itemName }}</span>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">{{ $itemName }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav> 