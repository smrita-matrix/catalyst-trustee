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
                  <h4>Edit About Catalyst</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('about-catalyst-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit About Catalyst</li>
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
                        <p class="f-m-light mt-1">Update the About Catalyst section details and submit the form.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('about-catalyst-details.update', $about_catalyst->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Sub Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="sub_heading">Sub Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="sub_heading" type="text" name="sub_heading" value="{{ old('sub_heading', $about_catalyst->sub_heading) }}" placeholder="e.g. ABOUT CATALYST" required>
                                <div class="invalid-feedback">Please enter the Sub Heading.</div>
                            </div>

                            <!-- Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', $about_catalyst->heading) }}" placeholder="e.g. India's Trusted Partner in Trusteeship & Fiduciary Solutions" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Description <span class="txt-danger">*</span></label>
                                <textarea class="form-control" id="editor" name="description" placeholder="Enter the About Catalyst description" required>{{ old('description', $about_catalyst->description) }}</textarea>
                                <div class="invalid-feedback">Please enter the Description.</div>
                            </div>

                            <!-- Button Text -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text <span class="txt-danger">*</span></label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', $about_catalyst->button_text) }}" placeholder="e.g. Know More" required>
                                <div class="invalid-feedback">Please enter the Button Text.</div>
                            </div>

                            <!-- Button Link -->
                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link <span class="txt-danger">*</span></label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link', $about_catalyst->button_link) }}" placeholder="e.g. /about-us or https://..." required>
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
                                                <th style="width: 260px;">Icon (SVG)</th>
                                                <th style="width: 220px;">Title</th>
                                                <th>Description</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="features-tbody">
                                            @php
                                                $existingFeatures = old('feature_title')
                                                    ? collect(old('feature_title'))->map(function ($t, $i) {
                                                        return [
                                                            'title'       => $t,
                                                            'description' => old('feature_description')[$i] ?? '',
                                                            'icon'        => old('feature_existing_icon')[$i] ?? null,
                                                        ];
                                                    })->all()
                                                    : ($about_catalyst->features ?: [['title' => '', 'description' => '', 'icon' => null]]);
                                            @endphp
                                            @foreach ($existingFeatures as $i => $feature)
                                                <tr class="feature-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 feature-icon-input" type="file" name="feature_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">
                                                        <input type="hidden" name="feature_existing_icon[]" value="{{ $feature['icon'] ?? '' }}">
                                                        <textarea class="form-control feature-svg-input mb-2" name="feature_icon_svg[]" rows="2" placeholder="…or paste &lt;svg&gt;…&lt;/svg&gt; code">{{ $feature['icon_svg'] ?? '' }}</textarea>
                                                        <div class="feature-current-icon">
                                                            @if (!empty($feature['icon_svg']))
                                                                <span style="display:inline-block; max-height:42px; max-width:42px; overflow:hidden;">{!! $feature['icon_svg'] !!}</span>
                                                            @elseif (!empty($feature['icon']))
                                                                <img src="{{ asset('home/about-catalyst/' . $feature['icon']) }}" alt="icon" style="max-height: 42px; max-width: 42px; border: 1px solid #eee; padding: 2px; border-radius: 4px;">
                                                            @else
                                                                <span class="text-muted small">No icon</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="feature_title[]" value="{{ $feature['title'] ?? '' }}" placeholder="e.g. Trust & Integrity">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="feature_description[]" rows="2" placeholder="Short description">{{ $feature['description'] ?? '' }}</textarea>
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
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Leave the file empty to keep a row's current icon. Icon accepts .svg, .png, .jpg, .jpeg, .webp (max 2MB). Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('about-catalyst-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
                '<td>' +
                    '<input class="form-control mb-2 feature-icon-input" type="file" name="feature_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">' +
                    '<input type="hidden" name="feature_existing_icon[]" value="">' +
                    '<textarea class="form-control feature-svg-input mb-2" name="feature_icon_svg[]" rows="2" placeholder="…or paste <svg>…</svg> code"></textarea>' +
                    '<div class="feature-current-icon"><span class="text-muted small">No icon</span></div>' +
                '</td>' +
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
                const row = removeBtn.closest('.feature-row');
                row.querySelectorAll('input, textarea').forEach(function (el) {
                    if (el.type !== 'hidden') el.value = '';
                });
                row.querySelector('.feature-current-icon').innerHTML = '<span class="text-muted small">No icon</span>';
                row.querySelector('input[name="feature_existing_icon[]"]').value = '';
            }
            reindex();
        });

        // Live icon preview per row (replaces the shown current icon) – uploaded file
        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.feature-icon-input');
            if (!input) return;

            const box = input.parentElement.querySelector('.feature-current-icon');
            const file = input.files[0];

            if (!file) {
                return;
            }

            const ext = file.name.split('.').pop().toLowerCase();
            const validExts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            if (!validExts.includes(ext)) {
                alert('Please upload a valid icon (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                if (box) {
                    box.innerHTML = '<img src="' + ev.target.result + '" alt="preview" style="max-height: 42px; max-width: 42px; border: 1px solid #eee; padding: 2px; border-radius: 4px;"> <small class="text-success">New</small>';
                }
            };
            reader.readAsDataURL(file);
        });

        // Live icon preview per row – pasted SVG markup
        tbody.addEventListener('input', function (e) {
            const svgInput = e.target.closest('.feature-svg-input');
            if (!svgInput) return;

            const box = svgInput.parentElement.querySelector('.feature-current-icon');
            if (!box) return;

            const code = svgInput.value.trim();
            if (code.toLowerCase().indexOf('<svg') !== -1) {
                box.innerHTML = '<span style="display:inline-block; max-height:42px; max-width:42px; overflow:hidden;">' + code + '</span>';
                box.querySelectorAll('svg').forEach(function (svg) {
                    svg.style.maxHeight = '42px';
                    svg.style.maxWidth = '42px';
                });
            }
        });
    });
</script>
</body>

</html>
