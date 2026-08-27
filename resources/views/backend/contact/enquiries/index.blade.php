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
            <div class="col-6"><h4>Contact Enquiries</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Contact Enquiries</li>
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
                    <li class="breadcrumb-item active">Enquiries</li>
                  </ol></nav>
                  <span class="badge bg-primary">{{ $enquiries->where('is_read', 0)->count() }} unread</span>
                </div>

                <div class="table-responsive custom-scrollbar">
                <table class="display" id="basic-1">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Received</th>
                      <th>Name</th>
                      <th>Service</th>
                      <th>Location</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($enquiries as $key => $item)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') : '-' }}</td>
                        <td><b>{{ $item->full_name }}</b></td>
                        <td>{{ \Illuminate\Support\Str::limit($item->service, 30) }}</td>
                        <td>{{ $item->location ?: '-' }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->mobile }}</td>
                        <td>
                          @if($item->is_read)
                            <span class="badge badge-light-success">Read</span>
                          @else
                            <span class="badge badge-light-danger">New</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('contact-enquiry.show', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                          <form action="{{ route('contact-enquiry.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this enquiry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="9" class="text-center">No enquiries received yet.</td></tr>
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
