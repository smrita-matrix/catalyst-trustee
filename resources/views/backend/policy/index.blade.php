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
            <div class="col-6"><h4>Policy Pages</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Policy Pages</li>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Policy Pages</li>
                    <li class="breadcrumb-item active">All Pages</li>
                  </ol></nav>
                  <a href="{{ route('policy-pages.create') }}" class="btn btn-primary px-5 radius-30">+ Add Page</a>
                </div>

                <div class="alert alert-light border">
                  Pages such as <b>Privacy Policy</b>, <b>Terms of Use</b> and <b>Disclaimer</b>.
                  Anything ticked as <b>Show in footer</b> appears automatically at the bottom of every page of the website.
                </div>

                @if($pages->isEmpty())
                  <div class="alert alert-info mb-0">No policy pages yet. Click <b>Add Page</b> to create one.</div>
                @else
                  <div class="table-responsive custom-scrollbar">
                  <table class="table table-bordered align-middle">
                    <thead>
                      <tr>
                        <th style="width:60px;">Order</th>
                        <th>Page Title</th>
                        <th>Web Address</th>
                        <th style="width:110px;">Sections</th>
                        <th style="width:120px;">In Footer</th>
                        <th style="width:120px;">Status</th>
                        <th style="width:190px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pages as $page)
                      <tr>
                        <td>{{ $page->sort_order }}</td>
                        <td>
                          <b>{{ $page->title }}</b>
                          @if($page->effective_on)
                            <small class="text-secondary d-block">Last updated {{ $page->effective_on->format('d M Y') }}</small>
                          @endif
                        </td>
                        <td>
                          <a href="{{ $page->url }}" target="_blank" rel="noopener noreferrer">/{{ $page->slug }}</a>
                        </td>
                        <td>{{ count($page->sections ?? []) }}</td>
                        <td>
                          @if($page->show_in_footer)
                            <span class="badge badge-light-success">Yes</span>
                          @else
                            <span class="badge badge-light-secondary">No</span>
                          @endif
                        </td>
                        <td>
                          @if($page->status)
                            <span class="badge badge-light-success">Published</span>
                          @else
                            <span class="badge badge-light-danger">Hidden</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('policy-pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                          <form action="{{ route('policy-pages.destroy', $page->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Delete the {{ $page->title }} page? The footer link will disappear too.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                          </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  </div>
                @endif

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
