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
                                    <a href="{{ route('proofs-details.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Proofs / Recognition</li>
                            </ol>
                        </nav>

                        <a href="{{ route('proofs-details.create') }}" class="btn btn-primary px-5 radius-30">+ Add Proofs</a>
                    </div>

                    <div class="table-responsive custom-scrollbar">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Heading</th>
                                <th>Cards</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($proofs as $key => $section)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($section->heading), 60) }}</td>
                                    <td>
                                        @php $items = $section->items ?? []; @endphp
                                        @if (count($items))
                                            <div class="d-flex flex-column gap-2">
                                                @foreach ($items as $item)
                                                    <span class="d-inline-flex align-items-center gap-2" title="{{ $item['text'] ?? '' }}">
                                                        @if (!empty($item['icon_svg']))
                                                            <span style="display:inline-block;max-height:24px;max-width:24px;overflow:hidden;line-height:0;">{!! $item['icon_svg'] !!}</span>
                                                        @elseif (!empty($item['icon']))
                                                            <img src="{{ asset('home/proofs/' . $item['icon']) }}" alt="icon" style="max-height:24px;max-width:24px;">
                                                        @elseif (!empty($item['image']))
                                                            <img src="{{ asset('home/proofs/' . $item['image']) }}" alt="img" style="max-height:24px;max-width:40px;object-fit:cover;border-radius:3px;">
                                                        @endif
                                                        <small>{{ \Illuminate\Support\Str::limit($item['text'] ?? '', 60) }}</small>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No cards</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('proofs-details.edit', $section->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('proofs-details.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this Proofs section?');">
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
                                    <td colspan="4" class="text-center">No Proofs records found.</td>
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
