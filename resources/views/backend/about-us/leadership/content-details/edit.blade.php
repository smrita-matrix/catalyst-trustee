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
                  <h4>Edit Leadership Content</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('leadership-content-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Leadership Content</li>
                </ol>
                </div>
              </div>
            </div>
          </div>

          @php
              $imgBase = 'about-us/leadership/content/';
              // Board rows (old input takes precedence, else stored)
              if (old('board_name')) {
                  $boardRows = collect(old('board_name'))->map(function ($n, $i) {
                      return ['name' => $n, 'designation' => old('board_designation')[$i] ?? '', 'description' => old('board_description')[$i] ?? '', 'image' => old('board_existing_image')[$i] ?? null];
                  })->all();
              } else {
                  $boardRows = $content->board_members ?: [['name' => '', 'designation' => '', 'description' => '', 'image' => null]];
              }
              // Team rows
              if (old('team_name')) {
                  $teamRows = collect(old('team_name'))->map(function ($n, $i) {
                      return ['name' => $n, 'designation' => old('team_designation')[$i] ?? '', 'description' => old('team_description')[$i] ?? '', 'image' => old('team_existing_image')[$i] ?? null];
                  })->all();
              } else {
                  $teamRows = $content->team_members ?: [['name' => '', 'designation' => '', 'description' => '', 'image' => null]];
              }
          @endphp

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Leadership &mdash; Content</h4>
                        <p class="f-m-light mt-1">Update the intro, Board of Directors and Leadership Team.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('leadership-content-details.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- ============ Intro ============ -->
                            <div class="col-lg-6">
                                <label class="form-label" for="intro_sub_heading">Intro Sub Heading</label>
                                <input class="form-control" id="intro_sub_heading" type="text" name="intro_sub_heading" value="{{ old('intro_sub_heading', $content->intro_sub_heading) }}" placeholder="e.g. ABOUT">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="intro_heading">Intro Heading</label>
                                <input class="form-control" id="intro_heading" type="text" name="intro_heading" value="{{ old('intro_heading', $content->intro_heading) }}" placeholder="e.g. Leadership">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="intro_description">Intro Description</label>
                                <textarea class="form-control" id="intro_description" name="intro_description" rows="3" placeholder="Intro paragraph">{{ old('intro_description', $content->intro_description) }}</textarea>
                            </div>

                            <!-- ============ Board of Directors ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <label class="form-label" for="board_heading">Board Heading</label>
                                <input class="form-control" id="board_heading" type="text" name="board_heading" value="{{ old('board_heading', $content->board_heading) }}" placeholder="e.g. Board of Directors">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Board of Directors</h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-member" data-target="board-tbody" data-prefix="board">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 190px;">Photo</th>
                                                <th style="width: 190px;">Name</th>
                                                <th style="width: 180px;">Designation</th>
                                                <th>Description</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="board-tbody">
                                            @foreach ($boardRows as $i => $m)
                                                <tr class="member-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 member-image-input" type="file" name="board_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <input type="hidden" name="board_existing_image[]" value="{{ $m['image'] ?? '' }}">
                                                        <div class="img-preview member-image-preview">
                                                            @if (!empty($m['image']))<img src="{{ asset($imgBase . $m['image']) }}" alt="photo">@endif
                                                        </div>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="board_name[]" value="{{ $m['name'] ?? '' }}" placeholder="e.g. Mr. Ravindra Marathe"></td>
                                                    <td><input class="form-control" type="text" name="board_designation[]" value="{{ $m['designation'] ?? '' }}" placeholder="e.g. Chairman"></td>
                                                    <td><textarea class="form-control" name="board_description[]" rows="3" placeholder="Bio / description">{{ $m['description'] ?? '' }}</textarea></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-member" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ============ Leadership Team ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <label class="form-label" for="team_heading">Team Heading</label>
                                <input class="form-control" id="team_heading" type="text" name="team_heading" value="{{ old('team_heading', $content->team_heading) }}" placeholder="e.g. Leadership Team">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Leadership Team</h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-member" data-target="team-tbody" data-prefix="team">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 190px;">Photo</th>
                                                <th style="width: 190px;">Name</th>
                                                <th style="width: 180px;">Designation</th>
                                                <th>Description</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="team-tbody">
                                            @foreach ($teamRows as $i => $m)
                                                <tr class="member-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 member-image-input" type="file" name="team_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <input type="hidden" name="team_existing_image[]" value="{{ $m['image'] ?? '' }}">
                                                        <div class="img-preview member-image-preview">
                                                            @if (!empty($m['image']))<img src="{{ asset($imgBase . $m['image']) }}" alt="photo">@endif
                                                        </div>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="team_name[]" value="{{ $m['name'] ?? '' }}" placeholder="e.g. Mr. Chintaman Dixit"></td>
                                                    <td><input class="form-control" type="text" name="team_designation[]" value="{{ $m['designation'] ?? '' }}" placeholder="e.g. Founder"></td>
                                                    <td><textarea class="form-control" name="team_description[]" rows="3" placeholder="Bio / description">{{ $m['description'] ?? '' }}</textarea></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-member" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('leadership-content-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Update</button>
                            </div>
                        </form>
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

@include('backend.about-us.leadership.content-details._repeater-js')
</body>

</html>
