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
            <div class="col-6"><h4>Application Details</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Application Details</li>
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
                    <li class="breadcrumb-item">Careers</li>
                    <li class="breadcrumb-item"><a href="{{ route('career-application.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">{{ $application->full_name }}</li>
                  </ol></nav>
                  <a href="{{ route('career-application.index') }}" class="btn btn-secondary radius-30">Back</a>
                </div>

                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Candidate</b></div>
                  <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                      <tbody>
                        <tr><th style="width:280px;">Name</th><td>{{ $application->full_name }}</td></tr>
                        <tr><th>Position Applied For</th><td><b>{{ $application->position }}</b></td></tr>
                        <tr><th>Email</th><td><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></td></tr>
                        <tr><th>Phone</th><td>{{ $application->phone }}</td></tr>
                        <tr><th>City</th><td>{{ $application->city }}</td></tr>
                        <tr><th>Resume</th>
                          <td>
                            <a href="{{ route('career-application.download', $application->id) }}" class="btn btn-sm btn-light">
                              <i class="fa fa-download"></i> {{ $application->resume_original_name ?: 'Download CV' }}
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                @if($application->intro)
                <div class="card border mb-4">
                  <div class="card-header py-2"><b>Intro / Why we should hire them</b></div>
                  <div class="card-body">{!! nl2br(e($application->intro)) !!}</div>
                </div>
                @endif

                <p class="text-secondary">
                  Received {{ $application->created_at ? \Carbon\Carbon::parse($application->created_at)->format('d M Y, H:i') : '-' }}
                  @if($application->ip_address) from {{ $application->ip_address }} @endif
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
