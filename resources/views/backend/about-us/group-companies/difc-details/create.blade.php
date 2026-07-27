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
                  <h4>Add DIFC Services</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('group-companies-difc-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add DIFC Services</li>
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
                        <h4>Group Companies &mdash; Catalyst (DIFC) Services Limited</h4>
                        <p class="f-m-light mt-1">Set the logo, heading, descriptions, service boxes and button.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('group-companies-difc-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Logo -->
                            <div class="col-lg-6">
                                <label class="form-label" for="logo_image">Logo Image</label>
                                <input class="form-control" id="logo_image" type="file" name="logo_image" accept=".png, .jpg, .jpeg, .webp, .svg" onchange="previewLogo()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .png, .jpg, .webp, .svg</small>
                                <div class="img-preview bg-image-preview mt-2" id="logo-preview"></div>
                            </div>

                            <!-- Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', 'Catalyst (DIFC) Services Limited') }}" placeholder="e.g. Catalyst (DIFC) Services Limited" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Top Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Top Description</label>
                                <textarea class="form-control" id="editor" name="top_description" placeholder="Paragraphs shown above the service boxes">{{ old('top_description') }}</textarea>
                            </div>

                            <!-- ============ Service Boxes (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Service Boxes</h5>
                                    <button type="button" id="btn-add-service" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="services-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 220px;">Icon</th>
                                                <th>Title</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="services-tbody">
                                            @php $oldSvcTitles = old('service_title', ['']); @endphp
                                            @foreach ($oldSvcTitles as $i => $t)
                                                <tr class="service-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td><input class="form-control mb-2 service-icon-input" type="file" name="service_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview service-icon-preview"></div></td>
                                                    <td><textarea class="form-control" name="service_title[]" rows="2" placeholder="e.g. UAE mainland and free zone company incorporation">{{ $t }}</textarea></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-service" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Each box: icon + title. Empty rows are ignored.</small>
                            </div>

                            <!-- Bottom Description (CKEditor) -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <label class="form-label" for="editor1">Bottom Description</label>
                                <textarea class="form-control" id="editor1" name="bottom_description" placeholder="Paragraphs shown below the service boxes">{{ old('bottom_description') }}</textarea>
                            </div>

                            <!-- Button -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text</label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', 'Know More') }}" placeholder="e.g. Know More">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link</label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link') }}" placeholder="e.g. #">
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('group-companies-difc-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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

@include('backend.about-us.group-companies.difc-details._services-js')
</body>

</html>
