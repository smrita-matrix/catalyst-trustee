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
            <div class="col-6"><h4>Careers Page Content</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Careers Page Content</li>
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
                    <li class="breadcrumb-item active">Page Content</li>
                  </ol></nav>
                  @if(!$content)
                    <a href="{{ route('career-page.create') }}" class="btn btn-primary px-5 radius-30">+ Add Content</a>
                  @else
                    <a href="{{ route('career-page.edit', $content->id) }}" class="btn btn-primary px-5 radius-30">Edit Content</a>
                  @endif
                </div>

                @if(!$content)
                  <div class="alert alert-info">No content yet. Click <b>Add Content</b> to set up the Careers page.</div>
                @else
                  <div class="table-responsive custom-scrollbar">
                  <table class="table table-bordered align-middle">
                    <tbody>
                      <tr><th style="width:280px;">Banner Heading</th><td>{{ $content->banner_title ?: '-' }}</td></tr>
                      <tr><th>Intro Heading</th><td>{{ \Illuminate\Support\Str::limit($content->intro_heading, 90) ?: '-' }}</td></tr>
                      <tr><th>Intro Paragraph</th><td>{{ \Illuminate\Support\Str::limit($content->intro_text, 120) ?: '-' }}</td></tr>
                      <tr><th>Form Heading</th><td>{{ $content->form_heading ?: '-' }}</td></tr>
                      <tr><th>Applications Emailed To</th>
                        <td>
                          @if($content->notify_email)
                            {{ $content->notify_email }}
                          @else
                            <span class="badge badge-light-danger">Not set - HR gets no email</span>
                          @endif
                        </td>
                      </tr>
                      <tr><th>Live Page</th><td><a href="{{ route('frontend.careers') }}" target="_blank" rel="noopener noreferrer">/careers</a></td></tr>
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
