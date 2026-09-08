<!doctype html>
<html lang="en">

<head>
  @include('components.backend.head')
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-6"><h4>Add Post</h4></div>
        <div class="col-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg></a></li>
            <li class="breadcrumb-item">Articles</li>
            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blog</a></li>
            <li class="breadcrumb-item active">Add Post</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="card">
      <div class="card-header"><h4>Add Post</h4></div>
      <div class="card-body">
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
          @endif

          @include('backend.newsletter.blogs._form')

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@include('components.backend.footer')
@include('components.backend.main-js')
</html>
