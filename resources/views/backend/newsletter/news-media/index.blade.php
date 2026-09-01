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
            <div class="col-6"><h4>News &amp; Media</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">News &amp; Media</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">

                @if(session('message'))
                  <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                  </div>
                @endif

                {{-- ---------------- Banner (kept at the top) ---------------- --}}
                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Page Banner</b>
                    <small class="text-secondary d-block">Shown at the top of the News &amp; Media page.</small>
                  </div>
                  <div class="card-body">
                    <form class="row g-4 custom-input" action="{{ route('news-media.banner.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="col-lg-3">
                        <label class="form-label">Banner Heading</label>
                        <input class="form-control" type="text" name="title" value="{{ optional($banner)->title }}" placeholder="e.g. News &amp; Media">
                      </div>
                      <div class="col-lg-3">
                        <label class="form-label">Breadcrumb Parent</label>
                        <input class="form-control" type="text" name="breadcrumb_parent" value="{{ optional($banner)->breadcrumb_parent }}" placeholder="e.g. Newsletter">
                      </div>
                      <div class="col-lg-3">
                        <label class="form-label">Breadcrumb Label</label>
                        <input class="form-control" type="text" name="breadcrumb_child" value="{{ optional($banner)->breadcrumb_child }}" placeholder="e.g. News &amp; Media">
                      </div>
                      <div class="col-lg-3">
                        <label class="form-label">Section Heading</label>
                        <input class="form-control" type="text" name="section_heading" value="{{ optional($banner)->section_heading }}" placeholder="heading above the cards">
                      </div>
                      <div class="col-lg-6">
                        <label class="form-label">Background Image</label>
                        <input class="form-control" type="file" name="background_image" accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-secondary d-block mt-1">
                          <i class="fa fa-info-circle"></i> Recommended <b>1500 x 976 px</b> - WebP, JPG or PNG, max 4 MB. Cropped to fill from the centre.
                        </small>
                        @if(optional($banner)->background_image)
                          <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                            <img src="{{ asset('news-media-uploads/banner/'.$banner->background_image) }}" alt="banner" style="max-height:100px; border-radius:6px; display:block;">
                          </div>
                        @endif
                      </div>
                      <div class="col-12">
                        <button class="btn btn-primary px-5" type="submit">Save Banner</button>
                      </div>
                    </form>
                  </div>
                </div>

                {{-- ---------------- Cards ---------------- --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Newsletter</li>
                    <li class="breadcrumb-item active">News &amp; Media</li>
                  </ol></nav>
                  <a href="{{ route('news-media.create') }}" class="btn btn-primary px-5 radius-30">+ Add News</a>
                </div>

                <div class="table-responsive custom-scrollbar">
                <table class="table table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:45px;">#</th>
                      <th style="width:120px;">Image</th>
                      <th style="width:150px;">Category</th>
                      <th>Title</th>
                      <th style="width:130px;">Read More</th>
                      <th style="width:80px;">Order</th>
                      <th style="width:100px;">Status</th>
                      <th style="width:170px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($items as $key => $item)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                          @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="image" style="max-height:48px; max-width:90px; object-fit:cover; border-radius:4px;">
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                        <td>{{ $item->category ?: '-' }}</td>
                        <td><b>{{ $item->title }}</b></td>
                        <td>
                          @if($item->read_more_url)
                            <a href="{{ $item->read_more_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light">
                              <i class="fa fa-external-link"></i> Open
                            </a>
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                          @if($item->status)
                            <span class="badge badge-light-success">Shown</span>
                          @else
                            <span class="badge badge-light-danger">Hidden</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('news-media.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                          <form action="{{ route('news-media.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="8" class="text-center text-muted">No News &amp; Media items yet.</td></tr>
                    @endforelse
                  </tbody>
                </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('components.backend.footer')
    </div>
    </div>

    @include('components.backend.main-js')

</body>

</html>
