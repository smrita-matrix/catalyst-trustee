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
                  <h4>Add CTA Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('cta-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add CTA</li>
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
                        <h4>Call To Action Section</h4>
                        <p class="f-m-light mt-1">Set the heading, description, button and the background image.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('cta-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', 'Connect With Catalyst Trusteeship Limited') }}" placeholder="e.g. Connect With Catalyst Trusteeship Limited" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Description</label>
                                <textarea class="form-control" id="editor" name="description" placeholder="Enter the CTA description">{{ old('description') }}</textarea>
                            </div>

                            <!-- Button Text -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text</label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', 'Connect With Our Specialists') }}" placeholder="e.g. Connect With Our Specialists">
                            </div>

                            <!-- Button Link -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link</label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link') }}" placeholder="e.g. /contact-us or #">
                            </div>

                            <!-- Background Image -->
                            <div class="col-lg-6">
                                <label class="form-label" for="background_image">Background Image</label>
                                <input class="form-control" id="background_image" type="file" name="background_image" accept=".jpg, .jpeg, .png, .webp" onchange="previewBg()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .jpg, .jpeg, .png, .webp</small>
                            </div>

                            <!-- Preview -->
                            <div class="col-lg-6">
                                <label class="form-label">Preview</label>
                                <div class="img-preview bg-image-preview"></div>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('cta-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Submit</button>
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
        preview.innerHTML = '';
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (jpg, jpeg, png, webp).');
            document.getElementById('background_image').value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
        reader.readAsDataURL(file);
    }
</script>
</body>

</html>
