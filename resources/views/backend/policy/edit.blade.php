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
            <div class="col-6"><h4>Edit Policy Page</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Edit Policy Page</li>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('policy-pages.index') }}">Policy Pages</a></li>
                    <li class="breadcrumb-item active">{{ $page->title }}</li>
                  </ol></nav>
                  <div>
                    <a href="{{ $page->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary radius-30">View Page</a>
                    <a href="{{ route('policy-pages.index') }}" class="btn btn-secondary radius-30">Back</a>
                  </div>
                </div>

                <form action="{{ route('policy-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  @include('backend.policy._form')
                  <div class="mt-4"><button type="submit" class="btn btn-primary px-5 radius-30">Update</button></div>
                </form>

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
