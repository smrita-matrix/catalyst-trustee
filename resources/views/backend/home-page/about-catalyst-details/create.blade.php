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
                  <h4>Add About Catalyst</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('about-catalyst-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add About Catalyst</li>
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
                        <h4>About Catalyst Section</h4>
                        <p class="f-m-light mt-1">Fill up the About Catalyst section details and submit the form.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('about-catalyst-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Sub Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="sub_heading">Sub Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="sub_heading" type="text" name="sub_heading" value="{{ old('sub_heading') }}" placeholder="e.g. ABOUT CATALYST" required>
                                <div class="invalid-feedback">Please enter the Sub Heading.</div>
                            </div>

                            <!-- Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading') }}" placeholder="e.g. India's Trusted Partner in Trusteeship & Fiduciary Solutions" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Description <span class="txt-danger">*</span></label>
                                <textarea class="form-control" id="editor" name="description" placeholder="Enter the About Catalyst description" required>{{ old('description') }}</textarea>
                                <div class="invalid-feedback">Please enter the Description.</div>
                            </div>

                            <!-- Button Text -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text <span class="txt-danger">*</span></label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text') }}" placeholder="e.g. Know More" required>
                                <div class="invalid-feedback">Please enter the Button Text.</div>
                            </div>

                            <!-- Button Link -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link <span class="txt-danger">*</span></label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link') }}" placeholder="e.g. /about-us or https://..." required>
                                <div class="invalid-feedback">Please enter the Button Link.</div>
                            </div>

                            <!-- ============ About Catalyst Features (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">About Catalyst Features</h5>
                                    <button type="button" id="btn-add-feature" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="features-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 240px;">Icon (SVG)</th>
                                                <th style="width: 220px;">Title</th>
                                                <th>Description</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="features-tbody">
                                            @php
                                                $oldTitles = old('feature_title', ['']);
                                                $oldDescs  = old('feature_description', []);
                                                $oldSvgs   = old('feature_icon_svg', []);
                                            @endphp
                                            @foreach ($oldTitles as $i => $oldTitle)
                                                <tr class="feature-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 feature-icon-input" type="file" name="feature_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">
                                                        <textarea class="form-control feature-svg-input mb-2" name="feature_icon_svg[]" rows="2" placeholder="…or paste &lt;svg&gt;…&lt;/svg&gt; code">{{ $oldSvgs[$i] ?? '' }}</textarea>
                                                        <div class="feature-icon-preview"></div>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="feature_title[]" value="{{ $oldTitle }}" placeholder="e.g. Trust & Integrity">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="feature_description[]" rows="2" placeholder="Short description">{{ $oldDescs[$i] ?? '' }}</textarea>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-feature" title="Remove">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Add one or more feature cards. Icon accepts .svg, .png, .jpg, .jpeg, .webp (max 2MB). Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('about-catalyst-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody  = document.getElementById('features-tbody');
        const addBtn = document.getElementById('btn-add-feature');

        function reindex() {
            tbody.querySelectorAll('.feature-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function rowTemplate() {
            const row = document.createElement('tr');
            row.className = 'feature-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 feature-icon-input" type="file" name="feature_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp"><textarea class="form-control feature-svg-input mb-2" name="feature_icon_svg[]" rows="2" placeholder="…or paste <svg>…</svg> code"></textarea><div class="feature-icon-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="feature_title[]" placeholder="e.g. Trust & Integrity"></td>' +
                '<td><textarea class="form-control" name="feature_description[]" rows="2" placeholder="Short description"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-feature" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(rowTemplate());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-feature');
            if (!removeBtn) return;

            const rows = tbody.querySelectorAll('.feature-row');
            if (rows.length > 1) {
                removeBtn.closest('.feature-row').remove();
            } else {
                // Clear the last remaining row instead of removing it
                const row = removeBtn.closest('.feature-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                const prev = row.querySelector('.feature-icon-preview');
                if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        // Live icon preview per row – uploaded file
        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.feature-icon-input');
            if (!input) return;

            const preview = input.parentElement.querySelector('.feature-icon-preview');
            if (!preview) return;

            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();
            const validExts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            if (!validExts.includes(ext)) {
                alert('Please upload a valid icon (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview" style="max-height: 42px; max-width: 42px; border: 1px solid #eee; padding: 2px; border-radius: 4px;">';
            };
            reader.readAsDataURL(file);
        });

        // Live icon preview per row – pasted SVG markup
        tbody.addEventListener('input', function (e) {
            const svgInput = e.target.closest('.feature-svg-input');
            if (!svgInput) return;

            const preview = svgInput.parentElement.querySelector('.feature-icon-preview');
            if (!preview) return;

            const code = svgInput.value.trim();
            if (code.toLowerCase().indexOf('<svg') !== -1) {
                preview.innerHTML = '<span style="display:inline-block; max-height:42px; max-width:42px; overflow:hidden;">' + code + '</span>';
                preview.querySelectorAll('svg').forEach(function (svg) {
                    svg.style.maxHeight = '42px';
                    svg.style.maxWidth = '42px';
                });
            } else if (code === '') {
                preview.innerHTML = '';
            }
        });
    });
</script>
</body>

</html>
