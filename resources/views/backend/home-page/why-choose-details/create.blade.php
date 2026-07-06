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
                  <h4>Add Why Choose Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('why-choose-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Why Choose</li>
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
                        <h4>Why Choose Catalyst Trusteeship Limited</h4>
                        <p class="f-m-light mt-1">Set the section heading, then add one or more items (icon + paragraph).</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('why-choose-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Section Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading') }}" placeholder="e.g. Why Choose Catalyst Trusteeship Limited?" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- ============ Items (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Items</h5>
                                    <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="items-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 300px;">Icon (upload or paste SVG)</th>
                                                <th>Paragraph</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-tbody">
                                            @php
                                                $oldTexts = old('item_text', ['']);
                                                $oldSvgs  = old('item_icon_svg', []);
                                            @endphp
                                            @foreach ($oldTexts as $i => $oldText)
                                                <tr class="item-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 item-icon-input" type="file" name="item_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">
                                                        <textarea class="form-control item-svg-input mb-2" name="item_icon_svg[]" rows="2" placeholder="…or paste &lt;svg&gt;…&lt;/svg&gt; code">{{ $oldSvgs[$i] ?? '' }}</textarea>
                                                        <div class="item-icon-preview"></div>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="item_text[]" rows="2" placeholder="e.g. First non-banking private sector company registered with SEBI as a Debenture Trustee in India">{{ $oldText }}</textarea>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Per row: upload an icon (.svg/.png/.jpg/.webp, max 2MB) or paste raw &lt;svg&gt; code. Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('why-choose-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const tbody  = document.getElementById('items-tbody');
        const addBtn = document.getElementById('btn-add-item');

        function reindex() {
            tbody.querySelectorAll('.item-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function rowTemplate() {
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td>' +
                    '<input class="form-control mb-2 item-icon-input" type="file" name="item_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">' +
                    '<textarea class="form-control item-svg-input mb-2" name="item_icon_svg[]" rows="2" placeholder="…or paste <svg>…</svg> code"></textarea>' +
                    '<div class="item-icon-preview"></div>' +
                '</td>' +
                '<td><textarea class="form-control" name="item_text[]" rows="2" placeholder="Paragraph text"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(rowTemplate());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-item');
            if (!removeBtn) return;

            const rows = tbody.querySelectorAll('.item-row');
            if (rows.length > 1) {
                removeBtn.closest('.item-row').remove();
            } else {
                const row = removeBtn.closest('.item-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                const prev = row.querySelector('.item-icon-preview');
                if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        // Live preview – uploaded file
        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.item-icon-input');
            if (!input) return;

            const preview = input.parentElement.querySelector('.item-icon-preview');
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

        // Live preview – pasted SVG markup
        tbody.addEventListener('input', function (e) {
            const svgInput = e.target.closest('.item-svg-input');
            if (!svgInput) return;

            const preview = svgInput.parentElement.querySelector('.item-icon-preview');
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
