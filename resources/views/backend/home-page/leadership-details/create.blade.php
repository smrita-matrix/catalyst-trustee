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
                  <h4>Add Leadership Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('leadership-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Leadership</li>
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
                        <h4>Our Leadership &amp; Journey In Numbers</h4>
                        <p class="f-m-light mt-1">Manage the leadership team and the "Our Journey In Numbers" counters.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('leadership-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- ============ Our Leadership ============ -->
                            <div class="col-12">
                                <label class="form-label" for="leadership_heading">Leadership Heading</label>
                                <input class="form-control" id="leadership_heading" type="text" name="leadership_heading" value="{{ old('leadership_heading', 'Our Leadership') }}" placeholder="e.g. Our Leadership">
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Leadership Members</h5>
                                    <button type="button" id="btn-add-leader" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="leaders-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 170px;">Photo</th>
                                                <th style="width: 190px;">Name</th>
                                                <th style="width: 170px;">Designation</th>
                                                <th style="min-width: 320px;">Description (modal)</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="leaders-tbody">
                                            @php $oldLeaderNames = old('leader_name', ['']); @endphp
                                            @foreach ($oldLeaderNames as $i => $oldName)
                                                <tr class="leader-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 leader-image-input" type="file" name="leader_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <div class="img-preview leader-image-preview"></div>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="leader_name[]" value="{{ $oldName }}" placeholder="e.g. Mr. Chintaman Dixit"></td>
                                                    <td><input class="form-control" type="text" name="leader_designation[]" value="{{ old('leader_designation')[$i] ?? '' }}" placeholder="e.g. Founder"></td>
                                                    <td><textarea class="form-control leader-editor" name="leader_description[]" placeholder="Leadership bio / description">{{ old('leader_description')[$i] ?? '' }}</textarea></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-leader" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ============ Our Journey In Numbers ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <label class="form-label" for="numbers_heading">Numbers Heading</label>
                                <input class="form-control" id="numbers_heading" type="text" name="numbers_heading" value="{{ old('numbers_heading', 'Our Journey In Numbers') }}" placeholder="e.g. Our Journey In Numbers">
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Counter Items</h5>
                                    <button type="button" id="btn-add-number" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="numbers-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 200px;">Icon</th>
                                                <th>Label (Count Text)</th>
                                                <th style="width: 150px;">Number</th>
                                                <th style="width: 130px;">Suffix</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="numbers-tbody">
                                            @php $oldNumberTexts = old('number_text', ['']); @endphp
                                            @foreach ($oldNumberTexts as $i => $oldText)
                                                <tr class="number-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 number-icon-input" type="file" name="number_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <div class="img-preview number-icon-preview"></div>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="number_text[]" value="{{ $oldText }}" placeholder="e.g. No. Of Clients"></td>
                                                    <td><input class="form-control" type="text" name="number_value[]" value="{{ old('number_value')[$i] ?? '' }}" placeholder="e.g. 1000"></td>
                                                    <td><input class="form-control" type="text" name="number_suffix[]" value="{{ old('number_suffix')[$i] ?? '' }}" placeholder="e.g. + or Cr+"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-number" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Serial numbers (01, 02…) are added automatically. Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('leadership-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        /* ---------------- CKEditor management for leader descriptions ---------------- */
        const leaderEditors = new Map();

        function initLeaderEditor(textarea) {
            if (!window.ClassicEditor || !textarea || leaderEditors.has(textarea)) return;
            ClassicEditor.create(textarea)
                .then(function (editor) { leaderEditors.set(textarea, editor); })
                .catch(function (err) { console.error(err); });
        }

        function destroyLeaderEditor(textarea) {
            const ed = leaderEditors.get(textarea);
            if (ed) { ed.destroy().catch(function () {}); leaderEditors.delete(textarea); }
        }

        document.querySelectorAll('.leader-editor').forEach(initLeaderEditor);

        /* ---------------- Leadership repeater ---------------- */
        const leadersTbody = document.getElementById('leaders-tbody');
        const addLeaderBtn = document.getElementById('btn-add-leader');

        function reindexLeaders() {
            leadersTbody.querySelectorAll('.leader-row').forEach(function (r, idx) {
                r.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function leaderTemplate() {
            const row = document.createElement('tr');
            row.className = 'leader-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 leader-image-input" type="file" name="leader_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview leader-image-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="leader_name[]" placeholder="e.g. Mr. Chintaman Dixit"></td>' +
                '<td><input class="form-control" type="text" name="leader_designation[]" placeholder="e.g. Founder"></td>' +
                '<td><textarea class="form-control leader-editor" name="leader_description[]" placeholder="Leadership bio / description"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-leader" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addLeaderBtn.addEventListener('click', function () {
            const row = leaderTemplate();
            leadersTbody.appendChild(row);
            initLeaderEditor(row.querySelector('.leader-editor'));
            reindexLeaders();
        });

        leadersTbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-leader');
            if (!removeBtn) return;
            const row = removeBtn.closest('.leader-row');
            const rows = leadersTbody.querySelectorAll('.leader-row');
            if (rows.length > 1) {
                destroyLeaderEditor(row.querySelector('.leader-editor'));
                row.remove();
            } else {
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                const ta = row.querySelector('.leader-editor');
                const ed = leaderEditors.get(ta);
                if (ed) ed.setData('');
                const prev = row.querySelector('.leader-image-preview');
                if (prev) prev.innerHTML = '';
            }
            reindexLeaders();
        });

        leadersTbody.addEventListener('change', function (e) {
            const input = e.target.closest('.leader-image-input');
            if (!input) return;
            previewImage(input, input.parentElement.querySelector('.leader-image-preview'));
        });

        /* ---------------- Numbers repeater ---------------- */
        const numbersTbody = document.getElementById('numbers-tbody');
        const addNumberBtn = document.getElementById('btn-add-number');

        function reindexNumbers() {
            numbersTbody.querySelectorAll('.number-row').forEach(function (r, idx) {
                r.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function numberTemplate() {
            const row = document.createElement('tr');
            row.className = 'number-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 number-icon-input" type="file" name="number_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview number-icon-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="number_text[]" placeholder="e.g. No. Of Clients"></td>' +
                '<td><input class="form-control" type="text" name="number_value[]" placeholder="e.g. 1000"></td>' +
                '<td><input class="form-control" type="text" name="number_suffix[]" placeholder="e.g. + or Cr+"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-number" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addNumberBtn.addEventListener('click', function () {
            numbersTbody.appendChild(numberTemplate());
            reindexNumbers();
        });

        numbersTbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-number');
            if (!removeBtn) return;
            const rows = numbersTbody.querySelectorAll('.number-row');
            if (rows.length > 1) {
                removeBtn.closest('.number-row').remove();
            } else {
                const row = removeBtn.closest('.number-row');
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                const prev = row.querySelector('.number-icon-preview');
                if (prev) prev.innerHTML = '';
            }
            reindexNumbers();
        });

        numbersTbody.addEventListener('change', function (e) {
            const input = e.target.closest('.number-icon-input');
            if (!input) return;
            previewImage(input, input.parentElement.querySelector('.number-icon-preview'));
        });

        /* ---------------- shared image preview ---------------- */
        function previewImage(input, preview) {
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
        }

        /* ---------------- sync CKEditor data into textareas before submit ---------------- */
        const form = document.querySelector('form.banner-form');
        if (form) {
            form.addEventListener('submit', function () {
                leaderEditors.forEach(function (editor, textarea) {
                    textarea.value = editor.getData();
                });
            });
        }
    });
</script>
</body>

</html>
