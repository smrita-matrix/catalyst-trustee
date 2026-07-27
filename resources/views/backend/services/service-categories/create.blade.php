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
                <div class="col-6"><h4>Add Service Category</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('service-category.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Category</li>
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
                        <h4>Service Category</h4>
                        <p class="f-m-light mt-1">Add a service category (e.g. SEBI Regulated Services). Service pages are grouped under these categories.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('service-category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-6">
                                <label class="form-label" for="name">Category Name <span class="txt-danger">*</span></label>
                                <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. SEBI Regulated Services" required>
                                <div class="invalid-feedback">Please enter the Category Name.</div>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="sort_order">Display Order</label>
                                <input class="form-control" id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                                <small class="d-block text-secondary mt-1">Lower numbers appear first.</small>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Show on website</label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label" for="icon">Menu Icon</label>
                                <input class="form-control" id="icon" type="file" name="icon" accept=".png,.jpg,.jpeg,.webp,.svg" onchange="previewIcon()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Small icon shown next to the category in the menu. Max 2MB.</small>
                                <div class="img-preview mt-2" id="icon-preview"></div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('service-category.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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

@include('backend.services.service-categories._items-js')
</body>

</html>
