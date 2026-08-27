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
                <div class="col-6"><h4>Public Notice — Menu &amp; Pages</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <svg class="stroke-icon">
                          <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item active">Menu &amp; Pages</li>
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

                    {{-- Fallback banner for pages that have not set their own. --}}
                    <div class="card border mb-4">
                      <div class="card-header py-2"><b>Default Page Banner</b>
                        <small class="text-secondary d-block">Used by any Public Notice page that has no banner of its own.</small>
                      </div>
                      <div class="card-body">
                        <form class="row g-4 custom-input" action="{{ route('notice-category.banner.update') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="col-lg-4">
                            <label class="form-label">Banner Heading</label>
                            <input class="form-control" type="text" name="title" value="{{ optional($banner)->title }}" placeholder="e.g. Public Notice">
                          </div>
                          <div class="col-lg-4">
                            <label class="form-label">Breadcrumb Parent</label>
                            <input class="form-control" type="text" name="breadcrumb_parent" value="{{ optional($banner)->breadcrumb_parent }}" placeholder="e.g. Public Notice">
                          </div>
                          <div class="col-lg-4">
                            <label class="form-label">Background Image</label>
                            <input class="form-control" type="file" name="background_image" accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary d-block mt-1">
                              <i class="fa fa-info-circle"></i> Recommended <b>1500 x 976 px</b> - WebP, JPG or PNG, max 4 MB.
                            </small>
                            @if(optional($banner)->background_image)
                              <div class="mt-2"><img src="{{ asset('public-notice/banner/'.$banner->background_image) }}" alt="banner" style="max-height:60px;"></div>
                            @endif
                          </div>
                          <div class="col-12 text-end">
                            <button class="btn btn-primary px-5" type="submit">Save Banner</button>
                          </div>
                        </form>
                      </div>
                    </div>

                    {{-- Three steps, three tabs. Work through them in order. --}}
                    <ul class="nav nav-tabs border-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link {{ $tab === 'categories' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab-categories" role="tab">
                          <i class="fa fa-folder"></i> Step 1 — Categories
                          <span class="badge bg-light text-dark ms-1">{{ $categories->count() }}</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link {{ $tab === 'subcategories' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab-subcategories" role="tab">
                          <i class="fa fa-folder-open"></i> Step 2 — Sub Categories
                          <span class="badge bg-light text-dark ms-1">{{ $subCategories->count() }}</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link {{ $tab === 'pages' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab-pages" role="tab">
                          <i class="fa fa-file-text-o"></i> Step 3 — Pages
                          <span class="badge bg-light text-dark ms-1">{{ $pages->count() }}</span>
                        </a>
                      </li>
                    </ul>

                    <div class="tab-content pt-4">

                      {{-- ================= STEP 1 — CATEGORIES ================= --}}
                      <div class="tab-pane fade {{ $tab === 'categories' ? 'show active' : '' }}" id="tab-categories" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <p class="mb-0 text-muted">The main headings across the <b>Public Notice</b> menu — one column each.</p>
                          <a href="{{ route('notice-category.create', ['level' => 1]) }}" class="btn btn-primary radius-30">+ Add Category</a>
                        </div>

                        <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                          <thead class="table-light">
                            <tr>
                              <th style="width:60px;">Icon</th>
                              <th>Category Name</th>
                              <th style="width:120px;">Inside It</th>
                              <th style="width:80px;">Order</th>
                              <th style="width:100px;">Status</th>
                              <th style="width:200px;">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse ($categories as $item)
                              <tr>
                                <td>
                                  @if($item->icon)
                                    <img src="{{ asset('public-notice/icons/'.$item->icon) }}" alt="icon" style="height:28px;">
                                  @else
                                    <span class="text-muted">—</span>
                                  @endif
                                </td>
                                <td><b>{{ $item->name }}</b></td>
                                <td>{{ $all->where('parent_id', $item->id)->count() }} item(s)</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>@include('backend.public-notice.categories._status', ['item' => $item])</td>
                                <td>@include('backend.public-notice.categories._actions', ['item' => $item])</td>
                              </tr>
                            @empty
                              <tr><td colspan="6" class="text-center text-muted">No categories yet. Add one to start the menu.</td></tr>
                            @endforelse
                          </tbody>
                        </table>
                        </div>
                      </div>

                      {{-- ================= STEP 2 — SUB CATEGORIES ================= --}}
                      <div class="tab-pane fade {{ $tab === 'subcategories' ? 'show active' : '' }}" id="tab-subcategories" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <p class="mb-0 text-muted">
                            Optional. A sub category is a heading inside a category that opens a fly-out list —
                            like <b>SEBI Compliance by Debenture Trustee</b>. Skip this if a category's pages sit directly under it.
                          </p>
                          <a href="{{ route('notice-category.create', ['level' => 2]) }}" class="btn btn-primary radius-30 flex-shrink-0 ms-3">+ Add Sub Category</a>
                        </div>

                        <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                          <thead class="table-light">
                            <tr>
                              <th>Sub Category Name</th>
                              <th style="width:240px;">Inside Category</th>
                              <th style="width:120px;">Pages</th>
                              <th style="width:80px;">Order</th>
                              <th style="width:100px;">Status</th>
                              <th style="width:200px;">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse ($subCategories as $item)
                              <tr>
                                <td><b>{{ $item->name }}</b></td>
                                <td>{{ optional($all->firstWhere('id', $item->parent_id))->name ?: '—' }}</td>
                                <td>{{ $all->where('parent_id', $item->id)->count() }} page(s)</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>@include('backend.public-notice.categories._status', ['item' => $item])</td>
                                <td>@include('backend.public-notice.categories._actions', ['item' => $item])</td>
                              </tr>
                            @empty
                              <tr><td colspan="6" class="text-center text-muted">No sub categories. That is fine — pages can sit straight inside a category.</td></tr>
                            @endforelse
                          </tbody>
                        </table>
                        </div>
                      </div>

                      {{-- ================= STEP 3 — PAGES ================= --}}
                      <div class="tab-pane fade {{ $tab === 'pages' ? 'show active' : '' }}" id="tab-pages" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <p class="mb-0 text-muted">
                            The real pages. Open a page to set its banner, design and documents — everything for that page is on one screen.
                          </p>
                          <a href="{{ route('notice-category.create', ['level' => 3]) }}" class="btn btn-primary radius-30 flex-shrink-0 ms-3">+ Add Page</a>
                        </div>

                        <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                          <thead class="table-light">
                            <tr>
                              <th>Page Name</th>
                              <th style="width:230px;">Inside</th>
                              <th style="width:110px;">Opens</th>
                              <th style="width:230px;">Design</th>
                              <th style="width:100px;">Documents</th>
                              <th style="width:80px;">Order</th>
                              <th style="width:100px;">Status</th>
                              <th style="width:200px;">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse ($pages as $item)
                              <tr>
                                <td>
                                  <b>{{ $item->name }}</b>
                                  @if($item->link_type === 'page' && $item->slug)
                                    <br><a href="{{ route('frontend.notice_page', $item->slug) }}" target="_blank" rel="noopener noreferrer" class="small">/public-notice/{{ $item->slug }}</a>
                                  @endif
                                </td>
                                <td>{{ optional($all->firstWhere('id', $item->parent_id))->name ?: '—' }}</td>
                                <td>
                                  @switch($item->link_type)
                                    @case('page') <span class="badge bg-primary">Page</span> @break
                                    @case('pdf')
                                      <span class="badge bg-info">PDF</span>
                                      @if(!$item->document_file)<br><small class="text-danger">not uploaded</small>@endif
                                      @break
                                    @default <span class="badge bg-warning">Link</span>
                                  @endswitch
                                </td>
                                <td>{{ $item->layout ? (\App\Models\NoticeCategory::LAYOUTS[$item->layout] ?? $item->layout) : '—' }}</td>
                                <td>
                                  @php $docCount = $item->link_type === 'page' ? $item->notices()->count() : null; @endphp
                                  @if($docCount === null)
                                    <span class="text-muted">-</span>
                                  @elseif($docCount)
                                    <span class="badge badge-light-primary">{{ $docCount }}</span>
                                  @else
                                    <span class="badge badge-light-danger">none yet</span>
                                  @endif
                                </td>
                                <td>{{ $item->sort_order }}</td>
                                <td>@include('backend.public-notice.categories._status', ['item' => $item])</td>
                                <td>@include('backend.public-notice.categories._actions', ['item' => $item])</td>
                              </tr>
                            @empty
                              <tr><td colspan="8" class="text-center text-muted">No pages yet. Add a category first, then add pages here.</td></tr>
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
          </div>
        </div>
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
