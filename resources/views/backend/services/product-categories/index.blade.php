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

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Services</li>
                                <li class="breadcrumb-item active" aria-current="page">Product Categories</li>
                            </ol>
                        </nav>
                        <a href="{{ route('product-category.create') }}" class="btn btn-primary px-5 radius-30">+ Add Product</a>
                    </div>

                    <div class="alert alert-light border">
                        <span class="text-secondary">Add products and set each one's <b>Layout</b> here. To fill a product's <b>page content</b>, go to <a href="{{ route('product-services.index') }}">Product Services</a>.</span>
                    </div>

                    <div class="table-responsive custom-scrollbar">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th style="width:80px;">Order</th>
                                <th>Service Category</th>
                                <th>Product Name</th>
                                <th>Layout</th>
                                <th style="width:90px;">Status</th>
                                <th style="width:180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->sort_order }}</td>
                                    <td>{{ optional($item->serviceCategory)->name ?? '—' }}</td>
                                    <td><b>{{ $item->name }}</b></td>
                                    <td>
                                        @if ($item->layout && isset(\App\Models\ProductCategory::LAYOUTS[$item->layout]))
                                            <span class="badge bg-info">{{ \App\Models\ProductCategory::LAYOUTS[$item->layout] }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @include('components.backend.status-toggle', [
                                            'item' => $item,
                                            'url'  => route('product-category.toggle', $item->id),
                                        ])
                                    </td>
                                    <td>
                                        <a href="{{ route('product-category.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('product-category.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
