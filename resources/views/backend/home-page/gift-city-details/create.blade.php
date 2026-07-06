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
                  <h4>Add GIFT City Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('gift-city-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add GIFT City</li>
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
                        <h4>GIFT City Service Offerings</h4>
                        <p class="f-m-light mt-1">Set the section heading, add the service items, and an optional bottom text.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('gift-city-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Section Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading') }}" placeholder="e.g. GIFT City Service Offerings" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- ============ Items (table repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Service Items</h5>
                                    <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="items-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 200px;">Image</th>
                                                <th style="width: 220px;">Title</th>
                                                <th style="width: 200px;">Link</th>
                                                <th>Paragraph</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-tbody">
                                            @php
                                                $oldTitles = old('item_title', ['']);
                                            @endphp
                                            @foreach ($oldTitles as $i => $oldTitle)
                                                <tr class="item-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 item-image-input" type="file" name="item_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <div class="img-preview item-image-preview"></div>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="item_title[]" rows="2" placeholder="e.g. Alternative Investment Funds (AIFs) in GIFT City">{{ $oldTitle }}</textarea>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="item_title_link[]" value="{{ old('item_title_link')[$i] ?? '' }}" placeholder="e.g. #">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="item_description[]" rows="2" placeholder="Item paragraph">{{ old('item_description')[$i] ?? '' }}</textarea>
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
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Each item: image (.png/.jpg/.webp/.svg, max 2MB), title, link and paragraph. Serial numbers (01, 02…) are added automatically. Empty rows are ignored.</small>
                            </div>

                            <!-- Bottom Text -->
                            <div class="col-12">
                                <label class="form-label" for="footer_text">Bottom Text</label>
                                <textarea class="form-control" id="footer_text" name="footer_text" rows="3" placeholder="e.g. As GIFT City continues to evolve as a global financial destination...">{{ old('footer_text') }}</textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('gift-city-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
                '<td><input class="form-control mb-2 item-image-input" type="file" name="item_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview item-image-preview"></div></td>' +
                '<td><textarea class="form-control" name="item_title[]" rows="2" placeholder="Item title"></textarea></td>' +
                '<td><input class="form-control" type="text" name="item_title_link[]" placeholder="e.g. #"></td>' +
                '<td><textarea class="form-control" name="item_description[]" rows="2" placeholder="Item paragraph"></textarea></td>' +
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
                const prev = row.querySelector('.item-image-preview');
                if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        // Live image preview
        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.item-image-input');
            if (!input) return;

            const preview = input.parentElement.querySelector('.item-image-preview');
            if (!preview) return;

            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();
            const validExts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            if (!validExts.includes(ext)) {
                alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">';
            };
            reader.readAsDataURL(file);
        });
    });
</script>
</body>

</html>
