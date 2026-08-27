<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

    <div class="page-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Public Notice</li>
                            <li class="breadcrumb-item"><a href="{{ route('notice-category.index') }}">Menu &amp; Pages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    <a href="{{ route('notice-category.index', ['tab' => [1 => 'categories', 2 => 'subcategories', 3 => 'pages'][$level]]) }}" class="btn btn-secondary radius-30">Back</a>
                </div>

                <form action="{{ route('notice-category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('backend.public-notice.categories._form')

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-5 radius-30">Update</button>
                    </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('components.backend.footer')
    @include('components.backend.main-js')

</body>

</html>
