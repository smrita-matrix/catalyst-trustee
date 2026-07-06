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
                                    <a href="{{ route('leadership-details.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Leadership</li>
                            </ol>
                        </nav>

                        <a href="{{ route('leadership-details.create') }}" class="btn btn-primary px-5 radius-30">+ Add Leadership</a>
                    </div>

                    <div class="table-responsive custom-scrollbar">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Leadership Heading</th>
                                <th>Members</th>
                                <th>Numbers Heading</th>
                                <th>Counters</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leadership as $key => $section)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $section->leadership_heading }}</td>
                                    <td>
                                        @php $leaders = $section->leaders ?? []; @endphp
                                        @if (count($leaders))
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($leaders as $leader)
                                                    <span class="d-inline-flex align-items-center gap-2">
                                                        @if (!empty($leader['image']))
                                                            <img src="{{ asset('home/leadership/' . $leader['image']) }}" alt="" style="max-height: 26px; max-width: 26px; object-fit: cover; border-radius: 50%;">
                                                        @endif
                                                        <small>{{ $leader['name'] ?? '' }}<span class="text-muted"> &middot; {{ $leader['designation'] ?? '' }}</span></small>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>{{ $section->numbers_heading }}</td>
                                    <td>
                                        @php $numbers = $section->numbers ?? []; @endphp
                                        @if (count($numbers))
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($numbers as $number)
                                                    <small>{{ $number['number'] ?? '' }}{{ $number['suffix'] ?? '' }} <span class="text-muted">{{ $number['count_text'] ?? '' }}</span></small>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('leadership-details.edit', $section->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('leadership-details.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this Leadership section?');">
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
                                    <td colspan="6" class="text-center">No Leadership records found.</td>
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
