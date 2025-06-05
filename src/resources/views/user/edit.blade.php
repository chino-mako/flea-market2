@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/edit.css') }}">
@endpush

@section('content')
<div class="profile-edit-container">
  <h1 class="page-title">プロフィール設定</h1>

  <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
    @csrf
    @method('PUT')

    <div class="image-upload">
      <div class="image-preview">
        @if($user->profile_image)
          <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="preview-img">
        @else
          <div class="preview-placeholder"></div>
        @endif
      </div>

      <label for="profile_image" class="image-select-button">画像を選択する</label>
      <input type="file" name="profile_image" id="profile_image" class="file-input">
      @error('profile_image')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="name">ユーザー名</label>
      <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
      @error('name')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="postal_code">郵便番号</label>
      <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
      @error('postal_code')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="address">住所</label>
      <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
      @error('address')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="building">建物名</label>
      <input type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
      @error('building')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="update-button">更新する</button>
  </form>
</div>
@endsection
