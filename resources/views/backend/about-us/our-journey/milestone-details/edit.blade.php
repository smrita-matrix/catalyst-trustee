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
                  <h4>Edit Milestone</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('our-journey-milestone-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Milestone</li>
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
                        <h4>Our Journey &mdash; Milestones in Progress</h4>
                        <p class="f-m-light mt-1">Update this timeline milestone's year, description and icon.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('our-journey-milestone-details.update', $milestone->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Year -->
                            <div class="col-lg-4">
                                <label class="form-label" for="year">Year <span class="txt-danger">*</span></label>
                                <input class="form-control" id="year" type="text" name="year" value="{{ old('year', $milestone->year) }}" placeholder="e.g. 1997" required>
                                <div class="invalid-feedback">Please enter the Year.</div>
                            </div>

                            <!-- Sort Order -->
                            <div class="col-lg-4">
                                <label class="form-label" for="sort_order">Display Order</label>
                                <input class="form-control" id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $milestone->sort_order) }}" placeholder="e.g. 1">
                                <small class="d-block text-secondary mt-1">Lower numbers appear first on the timeline.</small>
                            </div>

                            <!-- Icon -->
                            <div class="col-lg-4">
                                <label class="form-label" for="icon_image">Icon Image</label>
                                <input class="form-control" id="icon_image" type="file" name="icon_image" accept=".png, .jpg, .jpeg, .webp, .svg" onchange="previewIcon()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Leave empty to keep the current icon. Max 2MB.</small>
                                <div class="img-preview bg-image-preview mt-2" id="icon-preview">
                                    @if ($milestone->icon_image)<img id="existing_icon" src="{{ asset('about-us/our-journey/milestones/' . $milestone->icon_image) }}" alt="icon">@endif
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="e.g. Embarked on the Journey of Trusteeship and secured First Debenture Trustee Assignments.">{{ old('description', $milestone->description) }}</textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('our-journey-milestone-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
    function previewIcon() {
        const file = document.getElementById('icon_image').files[0];
        const preview = document.getElementById('icon-preview');
        const existing = document.getElementById('existing_icon');
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['svg', 'png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
            document.getElementById('icon_image').value = '';
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
