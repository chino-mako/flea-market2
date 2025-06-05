@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')

<nav class="tab-menu">
    <a href="{{ route('items.index', ['tab' => 'recommend']) }}" class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('items.index', ['tab' => 'mylist']) }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</nav>

<div class="product-list">
    @foreach ($items as $item)
        <div class="item-card">
            <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
                <div class="item-image">
                    @php
                        $image = $item->image_path;
                        $isExternal = Str::startsWith($image, ['http://', 'https://']);
                    @endphp
                    <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}" alt="{{ $item->title }}">
                </div>
                <div class="item-name">{{ $item->title }}</div>
            </a>
            @if($item->is_sold)
            <span class="sold-label">SOLD</span>
            @endif
        </div>
    @endforeach
</div>

<div class="pagination">
    {{ $items->appends(['tab' => $tab])->links() }}
</div>

@endsection
