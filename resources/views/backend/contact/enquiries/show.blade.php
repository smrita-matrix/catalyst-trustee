<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

    <div class="page-body">
      <div class="container-fluid">
        <div class="page-title">
          <div class="row">
            <div class="col-6"><h4>Enquiry Details</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Enquiry Details</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                @if(session('message'))
                  <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Contact Us</li>
                    <li class="breadcrumb-item"><a href="{{ route('contact-enquiry.index') }}">Enquiries</a></li>
                    <li class="breadcrumb-item active">{{ $enquiry->full_name }}</li>
                  </ol></nav>
                  <a href="{{ route('contact-enquiry.index') }}" class="btn btn-secondary radius-30">Back</a>
                </div>

                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Enquiry</b></div>
                  <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                      <tbody>
                        <tr><th style="width:260px;">Name</th><td>{{ $enquiry->full_name }}</td></tr>
                        <tr><th>Service</th><td><b>{{ $enquiry->service }}</b></td></tr>
                        <tr><th>Location</th><td>{{ $enquiry->location ?: '-' }}</td></tr>
                        <tr><th>Email</th><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td></tr>
                        <tr><th>Mobile</th><td><a href="tel:{{ $enquiry->mobile }}">{{ $enquiry->mobile }}</a></td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Comments / Questions</b></div>
                  <div class="card-body">{!! nl2br(e($enquiry->comments)) !!}</div>
                </div>

                <p class="text-secondary">
                  Received {{ $enquiry->created_at ? \Carbon\Carbon::parse($enquiry->created_at)->format('d M Y, H:i') : '-' }}
                  @if($enquiry->ip_address) from {{ $enquiry->ip_address }} @endif
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('components.backend.footer')
    </div>
    </div>

    @include('components.backend.main-js')

</body>

</html>
