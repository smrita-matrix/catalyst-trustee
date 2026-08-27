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
            <div class="col-6"><h4>Grievance Details</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Grievance Details</li>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Grievance</li>
                    <li class="breadcrumb-item"><a href="{{ route('grievance-submission.index') }}">Submissions</a></li>
                    <li class="breadcrumb-item active">{{ $grievance->full_name }}</li>
                  </ol></nav>
                  <a href="{{ route('grievance-submission.index') }}" class="btn btn-secondary radius-30">Back</a>
                </div>

                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Investor / Debenture Holder</b></div>
                  <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                      <tbody>
                        <tr><th style="width:280px;">Full Name</th><td>{{ $grievance->full_name }}</td></tr>
                        <tr><th>PAN</th><td>{{ $grievance->pan }}</td></tr>
                        <tr><th>Email</th><td><a href="mailto:{{ $grievance->email }}">{{ $grievance->email }}</a></td></tr>
                        <tr><th>Mobile</th><td>{{ $grievance->mobile ?: '-' }}</td></tr>
                        <tr><th>Postal Address</th><td>{!! nl2br(e($grievance->address)) !!}</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Instrument Details &amp; Grievance</b></div>
                  <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                      <tbody>
                        <tr><th style="width:280px;">Debenture Issuer Name</th><td>{{ $grievance->issuer_name }}</td></tr>
                        <tr><th>Debenture Series Name</th><td>{{ $grievance->series_name ?: '-' }}</td></tr>
                        <tr><th>ISIN / Multiple ISIN</th><td>{{ $grievance->isin }}</td></tr>
                        <tr><th>No of Bonds held</th><td>{{ $grievance->bonds_held }}</td></tr>
                        <tr><th>Complaint Particulars</th>
                          <td>
                            @foreach((array) $grievance->complaint_types as $type)
                              <span class="badge badge-light-primary">{{ $type }}</span>
                            @endforeach
                          </td>
                        </tr>
                        <tr><th>Details of Grievance</th><td>{!! nl2br(e($grievance->complaint_details)) !!}</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <p class="text-secondary">
                  Received {{ $grievance->created_at ? \Carbon\Carbon::parse($grievance->created_at)->format('d M Y, H:i') : '-' }}
                  @if($grievance->ip_address) from {{ $grievance->ip_address }} @endif
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
