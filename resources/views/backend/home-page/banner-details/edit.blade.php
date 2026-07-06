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
                  <h4>Edit Banner Details Form</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('banner-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Banner Details</li>
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
                        <h4>Banner Details Form</h4>
                        <p class="f-m-light mt-1">Fill up your true details and submit the form.</p>
                    </div>
                    <div class="card-body">
                        <div class="vertical-main-wizard">
                        <div class="row g-3">    
                            <!-- Removed empty col div -->
                            <div class="col-12">
                            <div class="tab-content" id="wizard-tabContent">
                                <div class="tab-pane fade show active" id="wizard-contact" role="tabpanel" aria-labelledby="wizard-contact-tab">
                                <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('banner-details.update', $banner_details->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Banner Heading-->
                                    <div class="col-12">
                                        <label class="form-label" for="editor">Banner Heading <span class="txt-danger">*</span></label>
                                        <textarea class="form-control" id="editor" name="banner_heading" placeholder="e.g. Building Trust Through Structured Financial & Trusteeship Solutions" required>{{ old('banner_heading', $banner_details->banner_heading) }}</textarea>
                                        <div class="invalid-feedback">Please enter a Banner Heading.</div>
                                    </div>

                                    <!-- Banner Description-->
                                    <div class="col-12">
                                        <label class="form-label" for="editor1">Banner Description <span class="txt-danger">*</span></label>
                                        <textarea class="form-control" id="editor1" name="banner_description" placeholder="Enter the banner description" required>{{ old('banner_description', $banner_details->banner_description) }}</textarea>
                                        <div class="invalid-feedback">Please enter a Banner Description.</div>
                                    </div>

                                    <!-- Button Text-->
                                    <div class="col-lg-6">
                                        <label class="form-label" for="button_text">Button Text <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', $banner_details->button_text) }}" placeholder="e.g. Find Out More" required>
                                        <div class="invalid-feedback">Please enter the Button Text.</div>
                                    </div>

                                    <!-- Button Link-->
                                    <div class="col-lg-6">
                                        <label class="form-label" for="button_link">Button Link <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link', $banner_details->button_link) }}" placeholder="e.g. /about-us or https://..." required>
                                        <div class="invalid-feedback">Please enter the Button Link.</div>
                                    </div>

                                    <!-- Banner Image -->
                                    <div class="col-lg-6">
                                        <label class="form-label" for="banner_image">Banner Image</label>
                                        <input class="form-control" id="banner_image" type="file" name="banner_image" accept=".jpg, .jpeg, .png, .webp" onchange="previewBannerImage()">
                                        <div class="invalid-feedback">Please upload a Banner Image.</div>
                                        <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Leave empty to keep the current image.</small>
                                        <small class="d-block text-secondary"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .jpg, .jpeg, .png, .webp</small>
                                    </div>

                                    <!-- Preview / Current Image -->
                                    <div class="col-lg-6">
                                        <label class="form-label">{{ $banner_details->banner_images ? 'Current Image' : 'Preview' }}</label>
                                        <div class="banner-image-preview-box">
                                            <span id="bannerImagePreviewPlaceholder" class="text-muted small" @if ($banner_details->banner_images) style="display: none;" @endif>Image preview will appear here</span>
                                            @if ($banner_details->banner_images)
                                                <img id="existing_banner_image" src="{{ asset('home/banner/' . $banner_details->banner_images) }}" alt="Current Banner Image" class="img-fluid rounded" style="max-height: 180px;">
                                            @endif
                                            <img id="banner_image_preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 180px; display: none;">
                                        </div>
                                    </div>


                                    <!-- Form Actions -->
                                    <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                        <a href="{{ route('banner-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                        <button class="btn btn-primary px-4" type="submit">Update</button>
                                    </div>
                                </form>


                                </div>
                            </div>
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

<script>
   function previewBannerImage() {
    const file = document.getElementById('banner_image').files[0];
    const previewImage = document.getElementById('banner_image_preview');
    const existingImage = document.getElementById('existing_banner_image');
    const placeholder = document.getElementById('bannerImagePreviewPlaceholder');

    // Reset the new preview
    previewImage.src = '';
    previewImage.style.display = 'none';

    if (file) {
        const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (validImageTypes.includes(file.type)) {
            const reader = new FileReader();

            reader.onload = function (e) {
                // Hide the existing image and placeholder, show the new preview
                if (existingImage) existingImage.style.display = 'none';
                if (placeholder) placeholder.style.display = 'none';

                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            alert('Please upload a valid image file (jpg, jpeg, png, webp).');
        }
    } else {
        // Restore the existing image (or placeholder) if the file was cleared
        if (existingImage) {
            existingImage.style.display = 'block';
        } else if (placeholder) {
            placeholder.style.display = 'inline';
        }
    }
}

</script>
</body>

</html>