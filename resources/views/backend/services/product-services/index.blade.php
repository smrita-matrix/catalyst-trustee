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
                <div class="col-6"></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <svg class="stroke-icon">
                          <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Services</li>
                                <li class="breadcrumb-item active" aria-current="page">Product Services</li>
                            </ol>
                        </nav>
                        <a href="{{ route('product-category.index') }}" class="btn btn-outline-primary px-4 radius-30">Manage Products</a>
                    </div>

                    <div class="alert alert-light border">
                        <span class="text-secondary">Pick a category tab, then click <b>Edit Page</b> on a product to fill its page content. To add a new product or change its layout, use <a href="{{ route('product-category.index') }}">Product Categories</a>.</span>
                    </div>

                    @php $grouped = $products->groupBy('service_category_id'); @endphp

                    <!-- Category tabs -->
                    <ul class="nav nav-tabs" id="serviceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#svc-all" type="button" role="tab">
                                All Products <span class="badge bg-secondary ms-1">{{ $products->count() }}</span>
                            </button>
                        </li>
                        @foreach($categories as $cat)
                            @php $count = optional($grouped->get($cat->id))->count() ?? 0; @endphp
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#svc-{{ $cat->id }}" type="button" role="tab">
                                    {{ $cat->name }} <span class="badge bg-info ms-1">{{ $count }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="svc-all" role="tabpanel">
                            @include('backend.services.product-categories._table', ['rows' => $products, 'showCategory' => true])
                        </div>
                        @foreach($categories as $cat)
                            <div class="tab-pane fade" id="svc-{{ $cat->id }}" role="tabpanel">
                                @include('backend.services.product-categories._table', ['rows' => ($grouped->get($cat->id) ?? collect())->values(), 'showCategory' => false])
                            </div>
                        @endforeach
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

</body>

</html>
