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
                  <h4>Add Group Overview</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('group-companies-overview-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Group Overview</li>
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
                        <h4>Group Companies &mdash; Catalyst Group Overview</h4>
                        <p class="f-m-light mt-1">Set the two images, heading and description.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('group-companies-overview-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Main Image -->
                            <div class="col-lg-6">
                                <label class="form-label" for="main_image">Main Image</label>
                                <input class="form-control main-image-input" id="main_image" type="file" name="main_image" accept=".jpg, .jpeg, .png, .webp" data-preview="main-preview">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .jpg, .jpeg, .png, .webp</small>
                                <div class="img-preview bg-image-preview mt-2" id="main-preview"></div>
                            </div>

                            <!-- Small Image -->
                            <div class="col-lg-6">
                                <label class="form-label" for="small_image">Small Image</label>
                                <input class="form-control main-image-input" id="small_image" type="file" name="small_image" accept=".jpg, .jpeg, .png, .webp" data-preview="small-preview">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .jpg, .jpeg, .png, .webp</small>
                                <div class="img-preview bg-image-preview mt-2" id="small-preview"></div>
                            </div>

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', 'Catalyst Group Overview') }}" placeholder="e.g. Catalyst Group Overview" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- Description (CKEditor) -->
                            <div class="col-12">
                                <label class="form-label" for="editor">Description</label>
                                <textarea class="form-control" id="editor" name="description" placeholder="Enter the description">{{ old('description') }}</textarea>
                            </div>

                            <!-- ============ Group Entities (table repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Group Entities / Companies</h5>
                                    <button type="button" id="btn-add-entity" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="entities-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 200px;">Logo / Image</th>
                                                <th style="width: 200px;">Title</th>
                                                <th>Description</th>
                                                <th style="width: 170px;">Know More Link</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="entities-tbody">
                                            @php $oldEntTitles = old('entity_title', ['']); @endphp
                                            @foreach ($oldEntTitles as $i => $t)
                                                <tr class="entity-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td><input class="form-control mb-2 entity-image-input" type="file" name="entity_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview entity-image-preview"></div></td>
                                                    <td><input class="form-control" type="text" name="entity_title[]" value="{{ $t }}" placeholder="e.g. CTL Trusteeship Limited"></td>
                                                    <td><textarea class="form-control" name="entity_description[]" rows="3" placeholder="Entity description">{{ old('entity_description')[$i] ?? '' }}</textarea></td>
                                                    <td><input class="form-control" type="text" name="entity_link[]" value="{{ old('entity_link')[$i] ?? '' }}" placeholder="e.g. #"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-entity" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Each entity: logo image, title, description and "Know More" link. Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('group-companies-overview-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        document.querySelectorAll('.main-image-input').forEach(function (input) {
            input.addEventListener('change', function () {
                const preview = document.getElementById(input.dataset.preview);
                preview.innerHTML = '';
                const file = input.files[0];
                if (!file) return;
                const ext = file.name.split('.').pop().toLowerCase();
                if (['png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
                    alert('Please upload a valid image (jpg, jpeg, png, webp).');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
                reader.readAsDataURL(file);
            });
        });

        /* ---------------- Entities repeater ---------------- */
        const tbody  = document.getElementById('entities-tbody');
        const addBtn = document.getElementById('btn-add-entity');

        function reindex() {
            tbody.querySelectorAll('.entity-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function entityRow() {
            const row = document.createElement('tr');
            row.className = 'entity-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 entity-image-input" type="file" name="entity_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><input type="hidden" name="entity_existing_image[]" value=""><div class="img-preview entity-image-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="entity_title[]" placeholder="e.g. CTL Trusteeship Limited"></td>' +
                '<td><textarea class="form-control" name="entity_description[]" rows="3" placeholder="Entity description"></textarea></td>' +
                '<td><input class="form-control" type="text" name="entity_link[]" placeholder="e.g. #"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-entity" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(entityRow()); reindex(); });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-entity');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.entity-row');
            if (rows.length > 1) {
                removeBtn.closest('.entity-row').remove();
            } else {
                const row = removeBtn.closest('.entity-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { if (el.type !== 'hidden') el.value = ''; });
                const hidden = row.querySelector('input[type="hidden"]'); if (hidden) hidden.value = '';
                const prev = row.querySelector('.entity-image-preview'); if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.entity-image-input');
            if (!input) return;
            const preview = input.parentElement.querySelector('.entity-image-preview');
            if (!preview) return;
            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) return;
            const ext = file.name.split('.').pop().toLowerCase();
            if (['svg', 'png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
                alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        });
    });
</script>
</body>

</html>
