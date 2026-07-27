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
                <div class="col-6"><h4>Edit Product</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('product-category.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Product</li>
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
                        <h4>Product</h4>
                        <p class="f-m-light mt-1">Update this product.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input" novalidate action="{{ route('product-category.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-6">
                                <label class="form-label" for="service_category_id">Service Category <span class="txt-danger">*</span></label>
                                <select class="form-select" id="service_category_id" name="service_category_id" required>
                                    <option value="">— Select service category —</option>
                                    @foreach($serviceCategories as $cat)
                                        <option value="{{ $cat->id }}" {{ (string) old('service_category_id', $product->service_category_id) === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a Service Category.</div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="name">Product Name <span class="txt-danger">*</span></label>
                                <input class="form-control" id="name" type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Debenture Trustee Services (Listed)" required>
                                <div class="invalid-feedback">Please enter the Product Name.</div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="layout">Page Layout</label>
                                <select class="form-select" id="layout" name="layout">
                                    <option value="">— Select layout —</option>
                                    @foreach(\App\Models\ProductCategory::LAYOUTS as $key => $label)
                                        <option value="{{ $key }}" {{ old('layout', $product->layout) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <small class="d-block text-secondary mt-1">Which page design this product uses. <a href="{{ route('layout-guide') }}" target="_blank">See Layout Guide</a></small>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="sort_order">Display Order</label>
                                <input class="form-control" id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" placeholder="0">
                                <small class="d-block text-secondary mt-1">Lower numbers appear first.</small>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Show on website</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('product-category.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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

</body>

</html>
