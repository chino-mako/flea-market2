@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endpush

@section('content')
<div class="listing-container">
    <h2 class="page-title">商品の出品</h2>
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="listing-form">
            @csrf

            <!-- 商品画像 -->
            <section class="form-section">
                <label class="section-title">商品画像</label>
                <div class="image-upload-box">
                    <label for="image" class="upload-button">画像を選択する</label>
                    <input type="file" name="image" id="image" hidden>
                    <div class="image-preview" style="margin-top: 10px;">
                        <img id="imagePreview" src="#" alt="プレビュー画像" style="display: none; max-width: 100%; height: auto;" />
                    </div>
                </div>
                @error('image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </section>

            <!-- 商品の詳細 -->
            <section class="form-section">
                <label class="section-title">商品の詳細</label>

                <!-- カテゴリー -->
                <div class="form-group">
                    <label>カテゴリー</label>
                    <div class="category-list">
                        @foreach($categories as $category)
                            <label class="category-chip">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" hidden
                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                {{ $category->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 商品の状態 -->
                <div class="form-group">
                    <label for="condition">商品の状態</label>
                    <select name="condition" id="condition">
                        <option value="">選択してください</option>
                        @foreach (['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'] as $condition)
                            <option value="{{ $condition }}" {{ old('condition') == $condition ? 'selected' : '' }}>
                                {{ $condition }}
                            </option>
                        @endforeach
                    </select>
                    @error('condition')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <!-- 商品名・説明・価格 -->
            <section class="form-section">
                <label class="section-title">商品名と説明</label>

                <div class="form-group">
                    <label for="title">商品名</label>
                    <input type="text" name="title" id="title" placeholder="商品名を入力" value="{{ old('title') }}">
                    @error('title')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="brand_name">ブランド名</label>
                    <input type="text" name="brand_name" id="brand_name" placeholder="ブランド名を入力" value="{{ old('brand_name') }}">
                    @error('brand_name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">商品の説明</label>
                    <textarea name="description" id="description" rows="5" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">販売価格</label>
                    <div class="price-input">
                        <span>¥</span>
                        <input type="text" name="price" id="price" placeholder="0" value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <button type="submit" class="submit-btn">出品する</button>
        </form>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
</script>
@endpush
