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
                  <h4>Add Leadership Content</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('leadership-content-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Leadership Content</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Leadership &mdash; Content</h4>
                        <p class="f-m-light mt-1">Manage the intro, Board of Directors and Leadership Team.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('leadership-content-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- ============ Intro ============ -->
                            <div class="col-lg-6">
                                <label class="form-label" for="intro_sub_heading">Intro Sub Heading</label>
                                <input class="form-control" id="intro_sub_heading" type="text" name="intro_sub_heading" value="{{ old('intro_sub_heading', 'ABOUT') }}" placeholder="e.g. ABOUT">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="intro_heading">Intro Heading</label>
                                <input class="form-control" id="intro_heading" type="text" name="intro_heading" value="{{ old('intro_heading', 'Leadership') }}" placeholder="e.g. Leadership">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="intro_description">Intro Description</label>
                                <textarea class="form-control" id="intro_description" name="intro_description" rows="3" placeholder="Intro paragraph">{{ old('intro_description') }}</textarea>
                            </div>

                            <!-- ============ Board of Directors ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <label class="form-label" for="board_heading">Board Heading</label>
                                <input class="form-control" id="board_heading" type="text" name="board_heading" value="{{ old('board_heading', 'Board of Directors') }}" placeholder="e.g. Board of Directors">
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
                                            @php $oldBoard = old('board_name', ['']); @endphp
                                            @foreach ($oldBoard as $i => $name)
                                                <tr class="member-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td><input class="form-control mb-2 member-image-input" type="file" name="board_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview member-image-preview"></div></td>
                                                    <td><input class="form-control" type="text" name="board_name[]" value="{{ $name }}" placeholder="e.g. Mr. Ravindra Marathe"></td>
                                                    <td><input class="form-control" type="text" name="board_designation[]" value="{{ old('board_designation')[$i] ?? '' }}" placeholder="e.g. Chairman"></td>
                                                    <td><textarea class="form-control" name="board_description[]" rows="3" placeholder="Bio / description">{{ old('board_description')[$i] ?? '' }}</textarea></td>
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
                                <input class="form-control" id="team_heading" type="text" name="team_heading" value="{{ old('team_heading', 'Leadership Team') }}" placeholder="e.g. Leadership Team">
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
                                            @php $oldTeam = old('team_name', ['']); @endphp
                                            @foreach ($oldTeam as $i => $name)
                                                <tr class="member-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td><input class="form-control mb-2 member-image-input" type="file" name="team_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview member-image-preview"></div></td>
                                                    <td><input class="form-control" type="text" name="team_name[]" value="{{ $name }}" placeholder="e.g. Mr. Chintaman Dixit"></td>
                                                    <td><input class="form-control" type="text" name="team_designation[]" value="{{ old('team_designation')[$i] ?? '' }}" placeholder="e.g. Founder"></td>
                                                    <td><textarea class="form-control" name="team_description[]" rows="3" placeholder="Bio / description">{{ old('team_description')[$i] ?? '' }}</textarea></td>
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
                                <button class="btn btn-primary px-4" type="submit">Submit</button>
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
