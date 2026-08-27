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
            <div class="col-6"><h4>Grievance Page Content</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Grievance Page Content</li>
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
                    <li class="breadcrumb-item">Grievance</li>
                    <li class="breadcrumb-item active">Page Content</li>
                  </ol></nav>
                  @if(!$content)
                    <a href="{{ route('grievance-page.create') }}" class="btn btn-primary px-5 radius-30">+ Add Content</a>
                  @else
                    <a href="{{ route('grievance-page.edit', $content->id) }}" class="btn btn-primary px-5 radius-30">Edit Content</a>
                  @endif
                </div>

                @if(!$content)
                  <div class="alert alert-info">No content yet. Click <b>Add Content</b> to set up the Investor Grievance page.</div>
                @else
                  <div class="table-responsive custom-scrollbar">
                  <table class="table table-bordered align-middle">
                    <tbody>
                      <tr><th style="width:280px;">Banner Heading</th><td>{{ $content->banner_title ?: '-' }}</td></tr>
                      <tr><th>Note Above the Form</th><td>{{ $content->intro_text ?: '-' }}</td></tr>
                      <tr><th>First Section Heading</th><td>{{ $content->holder_heading ?: '-' }}</td></tr>
                      <tr><th>Second Section Heading</th><td>{{ $content->instrument_heading ?: '-' }}</td></tr>
                      <tr><th>Complaint Tick-boxes</th><td>{{ $content->complaint_options ? count($content->complaint_options) . ' option(s)' : 'none' }}</td></tr>
                      <tr><th>Notes Below the Form</th><td>{{ $content->notes ? count($content->notes) . ' note(s)' : 'none' }}</td></tr>
                      <tr><th>New Grievances Emailed To</th>
                        <td>
                          @if($content->notify_email)
                            {{ $content->notify_email }}
                          @else
                            <span class="badge badge-light-danger">Not set - the team gets no email</span>
                          @endif
                        </td>
                      </tr>
                      <tr><th>Live Page</th>
                        <td><a href="{{ route('frontend.investor_grievance') }}" target="_blank" rel="noopener noreferrer">/investor-grievance</a></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                @endif

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
