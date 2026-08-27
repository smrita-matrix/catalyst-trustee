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
            <div class="col-6"><h4>Current Openings</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Current Openings</li>
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
                    <li class="breadcrumb-item active">Current Openings</li>
                  </ol></nav>
                  <a href="{{ route('career-opening.create') }}" class="btn btn-primary px-5 radius-30">+ Add Opening</a>
                </div>

                <div class="table-responsive custom-scrollbar">
                <table class="table table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:45px;">#</th>
                      <th>Job Title</th>
                      <th style="width:120px;">Experience</th>
                      <th style="width:100px;">Vacancies</th>
                      <th style="width:180px;">Location</th>
                      <th style="width:80px;">Order</th>
                      <th style="width:100px;">Status</th>
                      <th style="width:170px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($openings as $key => $item)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><b>{{ $item->title }}</b></td>
                        <td>{{ $item->experience ?: '-' }}</td>
                        <td>{{ $item->vacancies ?: '-' }}</td>
                        <td>{{ $item->location ?: '-' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                          @if($item->status)
                            <span class="badge badge-light-success">Shown</span>
                          @else
                            <span class="badge badge-light-danger">Hidden</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('career-opening.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                          <form action="{{ route('career-opening.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this opening?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="8" class="text-center text-muted">No openings yet.</td></tr>
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

    @include('components.backend.footer')
    </div>
    </div>

    @include('components.backend.main-js')

</body>

</html>
