<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

     <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>Newsletter — Articles</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg></a></li>
                    <li class="breadcrumb-item">Newsletter</li>
                    <li class="breadcrumb-item active">Articles</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">

            {{-- ===== BANNER (same page) ===== --}}
            <div class="card">
                <div class="card-header"><h4>Page Banner</h4>
                    <p class="f-m-light mt-1 mb-0">Banner image + title shown at the top of the Articles page.</p></div>
                <div class="card-body">
                    <form class="needs-validation custom-input" novalidate action="{{ route('articles.banner.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <label class="form-label">Title</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title', $banner->title ?? 'Articles') }}" placeholder="Articles">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Breadcrumb Parent</label>
                                <input class="form-control" type="text" name="breadcrumb_parent" value="{{ old('breadcrumb_parent', $banner->breadcrumb_parent ?? 'Newsletter') }}" placeholder="Newsletter">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Breadcrumb Child</label>
                                <input class="form-control" type="text" name="breadcrumb_child" value="{{ old('breadcrumb_child', $banner->breadcrumb_child ?? 'Articles') }}" placeholder="Articles">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Background Image</label>
                                <input class="form-control single-image-input" type="file" name="background_image" accept=".jpg,.jpeg,.png,.webp">
                                <div class="img-preview mt-2">@if($banner && $banner->background_image)<img src="{{ asset('newsletter/banner/'.$banner->background_image) }}" alt="banner" style="max-height:90px;">@endif</div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-primary px-5" type="submit">Save Banner</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ===== ARTICLES (year-wise) ===== --}}
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Articles by Year</h4>
                    <a href="{{ route('articles.create') }}" class="btn btn-primary px-5 radius-30">+ Add Articles</a>
                </div>

                @forelse ($orderedKeys as $year)
                    @php $items = $groups[$year]; @endphp
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fa fa-calendar text-primary"></i> {{ $year }}
                                <span class="badge badge-light-primary ms-2">{{ $items->count() }}</span></h5>
                            <a href="{{ route('articles.create', ['year' => $year]) }}" class="btn btn-primary btn-sm radius-30">+ Add to {{ $year }}</a>
                        </div>
                        <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">#</th>
                                    <th style="width:80px;">Order</th>
                                    <th style="width:110px;">Cover</th>
                                    <th>Title (label)</th>
                                    <th style="width:110px;">PDF</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width:150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>@if($item->image_url)<img src="{{ $item->image_url }}" alt="cover" style="max-height:44px;">@else<span class="text-muted">—</span>@endif</td>
                                        <td><b>{{ $item->title }}</b></td>
                                        <td>@if($item->pdf_url)<a href="{{ $item->pdf_url }}" target="_blank" class="btn btn-sm btn-light"><i class="fa fa-file-pdf-o"></i> View</a>@else<span class="text-muted">—</span>@endif</td>
                                        <td>@if($item->status)<span class="badge badge-light-success">Shown</span>@else<span class="badge badge-light-danger">Hidden</span>@endif</td>
                                        <td>
                                            <a href="{{ route('articles.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this article?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No articles yet. Click <b>Add Articles</b> to start.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
