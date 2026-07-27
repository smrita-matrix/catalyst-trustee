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
                <div class="col-6"><h4>Layout Guide</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item active">Layout Guide</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="alert alert-light border">
                <span class="text-secondary">This shows what each layout looks like and which sections it has. Choose a product's layout in <a href="{{ route('product-category.index') }}">Product Categories</a>, then fill its content in <a href="{{ route('product-services.index') }}">Product Services</a>.</span>
            </div>

            <div class="row">
                @foreach($guide as $layout)
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">{{ $layout['name'] }}</h4>
                                <span class="badge bg-info mt-1">{{ $layout['for'] }}</span>
                            </div>
                            <a href="{{ $layout['sample'] }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-external-link"></i> Open sample design
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Live scaled preview of the sample design -->
                            <div style="position:relative; width:100%; height:230px; overflow:hidden; border:1px solid #e6e8ec; border-radius:8px; background:#f7f8fa;">
                                <iframe src="{{ $layout['sample'] }}" loading="lazy"
                                        style="width:1440px; height:1000px; border:0; transform:scale(0.32); transform-origin:0 0; pointer-events:none;"
                                        title="{{ $layout['name'] }} sample"></iframe>
                                <a href="{{ $layout['sample'] }}" target="_blank" rel="noopener" style="position:absolute; inset:0;"></a>
                            </div>

                            <h6 class="mt-3 mb-2">Sections in this layout:</h6>
                            <ol class="mb-0 ps-3">
                                @foreach($layout['sections'] as $sec)
                                    <li class="mb-1">{{ $sec }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-secondary"><i class="fa fa-info-circle"></i> If a preview looks blank, the sample site may block embedding — click <b>Open sample design</b> to view it in a new tab.</p>
          </div>
        </div>
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
