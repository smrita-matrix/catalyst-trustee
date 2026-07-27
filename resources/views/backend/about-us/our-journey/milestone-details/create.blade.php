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
                  <h4>Add Milestones</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('our-journey-milestone-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Milestones</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Our Journey &mdash; Milestones in Progress</h4>
                            <p class="f-m-light mt-1 mb-0">Add one or more timeline milestones. Click <b>Add More</b> to add another row.</p>
                        </div>
                        <button type="button" id="btn-add-milestone" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-plus"></i> Add More
                        </button>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation custom-input banner-form" novalidate action="{{ route('our-journey-milestone-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle" id="milestones-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 45px;">#</th>
                                            <th style="width: 130px;">Year <span class="txt-danger">*</span></th>
                                            <th style="width: 110px;">Order</th>
                                            <th style="width: 200px;">Icon</th>
                                            <th>Description</th>
                                            <th style="width: 55px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="milestones-tbody">
                                        @php $rows = old('year', ['']); @endphp
                                        @foreach ($rows as $i => $r)
                                        <tr class="milestone-row">
                                            <td class="row-index">{{ $i + 1 }}</td>
                                            <td><input class="form-control" type="text" name="year[]" value="{{ old('year.'.$i) }}" placeholder="1997"></td>
                                            <td><input class="form-control" type="number" name="sort_order[]" value="{{ old('sort_order.'.$i, 0) }}" placeholder="0"></td>
                                            <td>
                                                <input class="form-control mb-2 milestone-icon-input" type="file" name="icon_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                <div class="img-preview milestone-icon-preview"></div>
                                            </td>
                                            <td><textarea class="form-control" name="description[]" rows="2" placeholder="Milestone description">{{ old('description.'.$i) }}</textarea></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-milestone" title="Remove"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-secondary"><i class="fa fa-info-circle"></i> Lower "Order" numbers appear first on the timeline. Empty rows (no Year) are ignored.</small>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-3">
                                <a href="{{ route('our-journey-milestone-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const tbody  = document.getElementById('milestones-tbody');
        const addBtn = document.getElementById('btn-add-milestone');

        function reindex() {
            tbody.querySelectorAll('.milestone-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function milestoneRow() {
            const row = document.createElement('tr');
            row.className = 'milestone-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control" type="text" name="year[]" placeholder="1997"></td>' +
                '<td><input class="form-control" type="number" name="sort_order[]" value="0" placeholder="0"></td>' +
                '<td><input class="form-control mb-2 milestone-icon-input" type="file" name="icon_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview milestone-icon-preview"></div></td>' +
                '<td><textarea class="form-control" name="description[]" rows="2" placeholder="Milestone description"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-milestone" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(milestoneRow()); reindex(); });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-milestone');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.milestone-row');
            if (rows.length > 1) {
                removeBtn.closest('.milestone-row').remove();
            } else {
                const row = removeBtn.closest('.milestone-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                const prev = row.querySelector('.milestone-icon-preview'); if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.milestone-icon-input');
            if (!input) return;
            const preview = input.parentElement.querySelector('.milestone-icon-preview');
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
