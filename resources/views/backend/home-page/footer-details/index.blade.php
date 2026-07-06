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
                <div class="col-6">
                </div>
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('footer-details.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Footer</li>
                            </ol>
                        </nav>

                        <a href="{{ route('footer-details.create') }}" class="btn btn-primary px-5 radius-30">+ Add Footer</a>
                    </div>

                    <div class="table-responsive custom-scrollbar">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Social</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($footer as $key => $section)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($section->logo)
                                            <img src="{{ asset('home/footer/' . $section->logo) }}" alt="logo" style="max-height: 40px; max-width: 90px; object-fit: contain;">
                                        @else
                                            <span class="text-muted">No logo</span>
                                        @endif
                                    </td>
                                    <td>{{ $section->phone }}</td>
                                    <td>{{ $section->email }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($section->address, 50) }}</td>
                                    <td>
                                        @php $social = $section->social_links ?? []; @endphp
                                        @foreach ($social as $s)
                                            @if (!empty($s['url']))
                                                <a href="{{ $s['url'] }}" target="_blank" class="me-1"><i class="{{ $s['icon'] ?? '' }}"></i></a>
                                            @else
                                                <i class="{{ $s['icon'] ?? '' }} me-1 text-muted"></i>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('footer-details.edit', $section->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('footer-details.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this Footer?');">
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
                                    <td colspan="7" class="text-center">No Footer records found.</td>
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
