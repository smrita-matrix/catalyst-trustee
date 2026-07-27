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
                                <li class="breadcrumb-item">About Us</li>
                                <li class="breadcrumb-item">Leadership</li>
                                <li class="breadcrumb-item active" aria-current="page">Content</li>
                            </ol>
                        </nav>

                        <a href="{{ route('leadership-content-details.create') }}" class="btn btn-primary px-5 radius-30">+ Add Leadership Content</a>
                    </div>

                    <div class="table-responsive custom-scrollbar">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Intro Heading</th>
                                <th>Board Members</th>
                                <th>Team Members</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($content as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->intro_heading }}</td>
                                    <td>
                                        @php $board = $item->board_members ?? []; @endphp
                                        {{ count($board) }} member(s)
                                        @if (count($board))
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit(implode(', ', array_column($board, 'name')), 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php $team = $item->team_members ?? []; @endphp
                                        {{ count($team) }} member(s)
                                        @if (count($team))
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit(implode(', ', array_column($team, 'name')), 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('leadership-content-details.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('leadership-content-details.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this content?');">
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
                                    <td colspan="5" class="text-center">No leadership content found.</td>
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
