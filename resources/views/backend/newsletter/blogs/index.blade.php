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
        <div class="col-6"><h4>Articles — Blog</h4></div>
        <div class="col-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg></a></li>
            <li class="breadcrumb-item">Articles</li>
            <li class="breadcrumb-item active">Blog</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4>Blog Posts</h4>
          <p class="f-m-light mt-1 mb-0">
            Shown on the website at
            <a href="{{ route('frontend.blogs') }}" target="_blank" rel="noopener noreferrer">/blog</a>,
            newest first.
          </p>
        </div>
        <a href="{{ route('blogs.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Post</a>
      </div>

      <div class="card-body">
        <div class="table-responsive custom-scrollbar">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:55px;">#</th>
                <th style="width:130px;">Picture</th>
                <th>Title</th>
                <th style="width:130px;">Published</th>
                <th style="width:110px;">On the site</th>
                <th style="width:230px;">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($blogs as $i => $blog)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  @if ($blog->image)
                  <img src="{{ $blog->image_url }}" alt="" style="width:110px; height:70px; object-fit:cover; border-radius:6px;">
                  @else
                  <span class="text-secondary">none</span>
                  @endif
                </td>
                <td>
                  <b>{{ $blog->title }}</b>
                  <div class="text-secondary small mt-1">/blog/{{ $blog->slug }}</div>
                  @if ($blog->author)<div class="text-secondary small">by {{ $blog->author }}</div>@endif
                </td>
                <td>{{ $blog->published_on ? $blog->published_on->format('d M Y') : '—' }}</td>
                <td>
                  <a href="{{ route('blogs.toggle-status', $blog->id) }}"
                     class="badge {{ $blog->status ? 'bg-success' : 'bg-secondary' }}"
                     title="Click to {{ $blog->status ? 'hide it' : 'show it' }}">
                    {{ $blog->status ? 'Shown' : 'Hidden' }}
                  </a>
                </td>
                {{-- Named buttons rather than icons: three icons stacked in a
                     narrow column were hard to tell apart. --}}
                <td>
                  <div class="d-flex flex-wrap gap-1">
                    <a href="{{ $blog->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">View</a>
                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST"
                          onsubmit="return confirm('Delete this post?');">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-secondary py-4">No posts yet. Use <b>Add Post</b> to write the first one.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('components.backend.footer')
@include('components.backend.main-js')
</html>
