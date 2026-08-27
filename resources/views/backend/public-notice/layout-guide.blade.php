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
                <div class="col-6"><h4>Public Notice — Layout Guide</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('notice-category.index', ['tab' => 'pages']) }}">Public Notice</a></li>
                    <li class="breadcrumb-item active">Layout Guide</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>

          <div class="container-fluid">
            <div class="alert alert-light border">
              <span class="text-secondary">
                Each Public Notice page uses one of these designs. Every preview below is a
                <b>real page from this website</b>, so what you see is exactly what visitors get.
                Pick the design on the page itself under <a href="{{ route('notice-category.index', ['tab' => 'pages']) }}">Pages</a>.
              </span>
            </div>

            <div class="row">
              @foreach($guide as $layout)
              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="mb-0">{{ $layout['label'] }}</h4>
                      <span class="badge bg-info mt-1">Used by {{ $layout['used_by'] }} page(s)</span>
                    </div>
                    @if($layout['sample'])
                      <a href="{{ $layout['sample'] }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-external-link"></i> Open full page
                      </a>
                    @endif
                  </div>
                  <div class="card-body">

                    {{-- Scaled-down live preview of a real page using this design. --}}
                    <div style="position:relative; width:100%; height:260px; overflow:hidden; border:1px solid #e6e8ec; border-radius:8px; background:#f7f8fa;">
                      @if($layout['sample'])
                        <iframe src="{{ $layout['sample'] }}" loading="lazy"
                                style="width:1440px; height:1900px; border:0; transform:scale(0.34); transform-origin:0 0; pointer-events:none;"
                                title="{{ $layout['label'] }} preview"></iframe>
                        <a href="{{ $layout['sample'] }}" target="_blank" rel="noopener" style="position:absolute; inset:0;"></a>
                      @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                          No page uses this design yet.
                        </div>
                      @endif
                    </div>

                    @if($layout['example'])
                      <p class="text-secondary small mt-2 mb-2">Previewing: <b>{{ $layout['example'] }}</b></p>
                    @endif

                    <p class="mb-2">{{ $layout['summary'] }}</p>

                    <h6 class="mb-1">Columns this design uses:</h6>
                    <ul class="mb-0 ps-3">
                      @foreach($layout['fields'] as $field)
                        <li class="mb-1">{{ $field }}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              @endforeach
            </div>

            <p class="text-secondary">
              <i class="fa fa-info-circle"></i>
              Columns not listed for a design are simply ignored on that page — filling them does no harm.
            </p>
          </div>
        </div>
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
