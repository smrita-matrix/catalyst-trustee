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
                <div class="col-6"></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <svg class="stroke-icon">
                          <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
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
                    <p class="f-m-light mt-1 mb-0">Banner image + title shown at the top of the Notices &amp; Announcements page.</p></div>
                <div class="card-body">
                    <form class="needs-validation custom-input" novalidate action="{{ route('notices.banner.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <label class="form-label">Title</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title', $banner->title ?? 'Notices & Announcements') }}" placeholder="Notices & Announcements">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Breadcrumb Parent</label>
                                <input class="form-control" type="text" name="breadcrumb_parent" value="{{ old('breadcrumb_parent', $banner->breadcrumb_parent ?? 'Public Notice') }}" placeholder="Public Notice">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Breadcrumb Child</label>
                                <input class="form-control" type="text" name="breadcrumb_child" value="{{ old('breadcrumb_child', $banner->breadcrumb_child ?? 'Notices & Announcements') }}" placeholder="Notices & Announcements">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Background Image</label>
                                <input class="form-control single-image-input" type="file" name="background_image" accept=".jpg,.jpeg,.png,.webp">
                                <div class="img-preview mt-2">@if($banner && $banner->background_image)<img src="{{ asset('public-notice/banner/'.$banner->background_image) }}" alt="banner" style="max-height:90px;">@endif</div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-primary px-5" type="submit">Save Banner</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Public Notice</li>
                                <li class="breadcrumb-item active" aria-current="page">Notices &amp; Announcements</li>
                            </ol>
                        </nav>
                    </div>

                    @php $bySection = $notices->groupBy('section'); @endphp

                    @foreach (\App\Models\Notice::SECTIONS as $sectionKey => $sectionLabel)
                        @php $items = $bySection->get($sectionKey, collect()); $isAuc = $sectionKey === 'auc'; @endphp
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><i class="fa fa-folder-open-o text-primary"></i> {{ $sectionLabel }}
                                    <span class="badge badge-light-primary ms-2">{{ $items->count() }}</span></h5>
                                <a href="{{ route('notices.create', ['section' => $sectionKey]) }}" class="btn btn-primary btn-sm radius-30">+ Add to this section</a>
                            </div>

                            <div class="table-responsive custom-scrollbar">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:45px;">#</th>
                                        <th style="width:80px;">Order</th>
                                        <th style="width:150px;">{{ $isAuc ? 'Date' : 'Period' }}</th>
                                        <th>Title @if($isAuc)<span class="text-secondary">& description</span>@endif</th>
                                        <th style="width:110px;">Document</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:170px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->sort_order }}</td>
                                            <td>{{ $isAuc ? ($item->notice_date ?: '—') : ($item->period ?: '—') }}</td>
                                            <td><b>{{ $item->title }}</b>
                                                @if ($isAuc && $item->description)
                                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->document_url)
                                                    <a href="{{ $item->document_url }}" target="_blank" class="btn btn-sm btn-light" title="View"><i class="fa fa-file-pdf-o"></i> View</a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status)
                                                    <span class="badge badge-light-success">Shown</span>
                                                @else
                                                    <span class="badge badge-light-danger">Hidden</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('notices.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('notices.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notice?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center text-muted">No notices in this section yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            </div>
                        </div>
                    @endforeach
                  </div>
                </div>
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
