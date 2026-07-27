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
                  <h4>Edit Company Overview Introduction</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('company-overview-introduction-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Introduction</li>
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
                        <h4>Company Overview &mdash; Introduction</h4>
                        <p class="f-m-light mt-1">Update the intro image, stats badges, headings, content and button.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('company-overview-introduction-details.update', $introduction->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Image -->
                            <div class="col-lg-6">
                                <label class="form-label" for="image">Main Image</label>
                                <input class="form-control" id="image" type="file" name="image" accept=".jpg, .jpeg, .png, .webp" onchange="previewImg()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Leave empty to keep the current image. Max 2MB.</small>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ $introduction->image ? 'Current Image' : 'Preview' }}</label>
                                <div class="img-preview bg-image-preview">
                                    @if ($introduction->image)
                                        <img id="existing_img" src="{{ asset('about-us/company-overview/introduction/' . $introduction->image) }}" alt="image">
                                    @endif
                                </div>
                            </div>

                            <!-- Stats badges -->
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label" for="experience_number">Experience Number</label>
                                <input class="form-control" id="experience_number" type="text" name="experience_number" value="{{ old('experience_number', $introduction->experience_number) }}" placeholder="e.g. 29+">
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label" for="experience_label">Experience Label</label>
                                <input class="form-control" id="experience_label" type="text" name="experience_label" value="{{ old('experience_label', $introduction->experience_label) }}" placeholder="e.g. Years Of Fiduciary & Trusteeship Expertise">
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label" for="established_label">Established Label</label>
                                <input class="form-control" id="established_label" type="text" name="established_label" value="{{ old('established_label', $introduction->established_label) }}" placeholder="e.g. Established In">
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label" for="established_year">Established Year</label>
                                <input class="form-control" id="established_year" type="text" name="established_year" value="{{ old('established_year', $introduction->established_year) }}" placeholder="e.g. 1997">
                            </div>

                            <!-- Sub Heading / Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="sub_heading">Sub Heading</label>
                                <input class="form-control" id="sub_heading" type="text" name="sub_heading" value="{{ old('sub_heading', $introduction->sub_heading) }}" placeholder="e.g. Company Overview">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', $introduction->heading) }}" placeholder="e.g. Introduction to Catalyst Trusteeship Limited" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Tagline -->
                            <div class="col-12">
                                <label class="form-label" for="tagline">Tagline</label>
                                <input class="form-control" id="tagline" type="text" name="tagline" value="{{ old('tagline', $introduction->tagline) }}" placeholder="e.g. India’s Trusted Partner in Trusteeship & Fiduciary Solutions">
                            </div>

                            <!-- Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Description</label>
                                <textarea class="form-control" id="editor" name="description" placeholder="Enter the description">{{ old('description', $introduction->description) }}</textarea>
                            </div>

                            <!-- Read More Content (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor1">Read More Content <span class="text-secondary">(hidden until the "Read More" button is clicked — leave empty to hide the button)</span></label>
                                <textarea class="form-control" id="editor1" name="more_content" placeholder="Extra paragraphs shown when Read More is clicked">{{ old('more_content', $introduction->more_content) }}</textarea>
                            </div>

                            <!-- Button -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text</label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', $introduction->button_text) }}" placeholder="e.g. Read More">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link</label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link', $introduction->button_link) }}" placeholder="e.g. # or /about-us">
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('company-overview-introduction-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
    function previewImg() {
        const file = document.getElementById('image').files[0];
        const preview = document.querySelector('.bg-image-preview');
        const existing = document.getElementById('existing_img');
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (jpg, jpeg, png, webp).');
            document.getElementById('image').value = '';
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
