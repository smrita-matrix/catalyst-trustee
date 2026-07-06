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
                                    <a href="{{ route('about-catalyst-details.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">About Catalyst</li>
                            </ol>
                        </nav>

                        <a href="{{ route('about-catalyst-details.create') }}" class="btn btn-primary px-5 radius-30">+ Add About Catalyst</a>
                    </div>

                    <h5 class="mb-3">About Catalyst Section</h5>
                    <div class="table-responsive custom-scrollbar">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sub Heading</th>
                                <th>Heading</th>
                                <th>Description</th>
                                <th>Button</th>
                                <th>Features</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($about_catalyst as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->sub_heading }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($item->heading), 60) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 90) }}</td>
                                    <td>{{ $item->button_text }}</td>
                                    <td>
                                        @php $features = $item->features ?? []; @endphp
                                        @if (count($features))
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                @foreach ($features as $feature)
                                                    <span class="d-inline-flex align-items-center gap-1" title="{{ $feature['title'] ?? '' }}">
                                                        @if (!empty($feature['icon_svg']))
                                                            <span style="display:inline-block; max-height:28px; max-width:28px; overflow:hidden; line-height:0;">{!! $feature['icon_svg'] !!}</span>
                                                        @elseif (!empty($feature['icon']))
                                                            <img src="{{ asset('home/about-catalyst/' . $feature['icon']) }}" alt="icon" style="max-height: 28px; max-width: 28px;">
                                                        @endif
                                                        <small>{{ \Illuminate\Support\Str::limit($feature['title'] ?? '', 22) }}</small>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No features</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('about-catalyst-details.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('about-catalyst-details.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this About Catalyst section?');">
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
                                    <td colspan="7" class="text-center">No About Catalyst records found.</td>
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
