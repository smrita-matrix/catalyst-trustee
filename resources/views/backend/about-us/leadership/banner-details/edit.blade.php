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
                <div class="col-6">
                  <h4>Edit Leadership Banner</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('leadership-banner-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Banner</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Leadership &mdash; Banner</h4>
                        <p class="f-m-light mt-1">Update the breadcrumb banner title, its parent label and the background image.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('leadership-banner-details.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="col-lg-6">
                                <label class="form-label" for="title">Title <span class="txt-danger">*</span></label>
                                <input class="form-control" id="title" type="text" name="title" value="{{ old('title', $banner->title) }}" placeholder="e.g. Leadership" required>
                                <div class="invalid-feedback">Please enter the Title.</div>
                            </div>

                            <!-- Breadcrumb Parent -->
                            <div class="col-lg-6">
                                <label class="form-label" for="breadcrumb_parent">Breadcrumb Parent</label>
                                <input class="form-control" id="breadcrumb_parent" type="text" name="breadcrumb_parent" value="{{ old('breadcrumb_parent', $banner->breadcrumb_parent) }}" placeholder="e.g. About">
                                <small class="d-block text-secondary mt-1">Shown as: Home &rsaquo; <b>Parent</b> &rsaquo; Title</small>
                            </div>

                            <!-- Background Image -->
                            <div class="col-lg-6">
                                <label class="form-label" for="background_image">Background Image</label>
                                <input class="form-control" id="background_image" type="file" name="background_image" accept=".jpg, .jpeg, .png, .webp" onchange="previewBg()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Leave empty to keep the current image. Max 2MB.</small>
                            </div>

                            <!-- Preview / Current -->
                            <div class="col-lg-6">
                                <label class="form-label">{{ $banner->background_image ? 'Current Image' : 'Preview' }}</label>
                                <div class="img-preview bg-image-preview">
                                    @if ($banner->background_image)
                                        <img id="existing_bg" src="{{ asset('about-us/leadership/banner/' . $banner->background_image) }}" alt="bg">
                                    @endif
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('leadership-banner-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Update</button>
                            </div>
                        </form>
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

<script>
    function previewBg() {
        const file = document.getElementById('background_image').files[0];
        const preview = document.querySelector('.bg-image-preview');
        const existing = document.getElementById('existing_bg');
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (jpg, jpeg, png, webp).');
            document.getElementById('background_image').value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (ev) {
            if (existing) existing.remove();
            preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">';
        };
        reader.readAsDataURL(file);
    }
</script>
</body>

</html>
