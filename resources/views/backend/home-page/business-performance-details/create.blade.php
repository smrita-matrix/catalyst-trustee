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
                  <h4>Add Business Performance</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('business-performance-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Business Performance</li>
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
                        <h4>Business Performance</h4>
                        <p class="f-m-light mt-1">Set the headings, categories (legend + colors) and the yearly transaction values.</p>
                    </div>
                    <div class="card-body">
                        @php
                            $defaultLabels = ['Debenture Trustee', 'Security Trustee', 'Securitization', 'Others'];
                            $defaultColors = ['#c9624c', '#4a7fb5', '#e8a838', '#3d8c6f'];
                            $catLabels = old('cat_label', $defaultLabels);
                            $catColors = old('cat_color', $defaultColors);
                            $catCount  = count($catLabels);
                        @endphp

                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('business-performance-details.store') }}" method="POST">
                            @csrf

                            <!-- Sub Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="sub_heading">Sub Heading</label>
                                <input class="form-control" id="sub_heading" type="text" name="sub_heading" value="{{ old('sub_heading', 'Catalyst delivers more than expected') }}" placeholder="e.g. Catalyst delivers more than expected">
                            </div>

                            <!-- Heading -->
                            <div class="col-lg-6">
                                <label class="form-label" for="heading">Heading</label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', 'Business Performance') }}" placeholder="e.g. Business Performance">
                            </div>

                            <!-- ============ Categories ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <h5 class="mb-3">Categories (Legend)</h5>
                                <div class="row g-3">
                                    @foreach ($catLabels as $i => $label)
                                        <div class="col-lg-3 col-sm-6">
                                            <label class="form-label">Category {{ $i + 1 }}</label>
                                            <div class="input-group">
                                                <input class="form-control cat-label-input" data-idx="{{ $i }}" type="text" name="cat_label[]" value="{{ $label }}" placeholder="Category name">
                                                <input class="form-control form-control-color flex-grow-0" style="width: 48px;" type="color" name="cat_color[]" value="{{ $catColors[$i] ?? '#cccccc' }}" title="Color">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- ============ Years (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Yearly Data</h5>
                                    <button type="button" id="btn-add-year" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="years-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 130px;">Year</th>
                                                @foreach ($catLabels as $i => $label)
                                                    <th class="cat-col-head" data-idx="{{ $i }}">{{ $label }}</th>
                                                @endforeach
                                                <th style="width: 90px;">Total</th>
                                                <th style="width: 55px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="years-tbody">
                                            @php $oldYears = old('year', ['']); @endphp
                                            @foreach ($oldYears as $r => $oldYear)
                                                <tr class="year-row">
                                                    <td class="row-index">{{ $r + 1 }}</td>
                                                    <td><input class="form-control" type="text" name="year[]" value="{{ $oldYear }}" placeholder="e.g. 2016-17"></td>
                                                    @for ($c = 0; $c < $catCount; $c++)
                                                        <td><input class="form-control year-value" type="number" step="any" name="value[{{ $c }}][]" value="{{ old('value')[$c][$r] ?? '' }}" placeholder="0"></td>
                                                    @endfor
                                                    <td class="row-total text-secondary fw-bold">0</td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-year" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Total per year is calculated automatically. Percentages on the site are derived from these values. Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('business-performance-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const tbody   = document.getElementById('years-tbody');
        const addBtn  = document.getElementById('btn-add-year');
        const catCount = {{ $catCount }};

        function reindex() {
            tbody.querySelectorAll('.year-row').forEach(function (r, idx) {
                r.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function recalcRow(row) {
            let total = 0;
            row.querySelectorAll('.year-value').forEach(function (inp) {
                const v = parseFloat(inp.value);
                if (!isNaN(v)) total += v;
            });
            row.querySelector('.row-total').textContent = total.toLocaleString();
        }

        function recalcAll() {
            tbody.querySelectorAll('.year-row').forEach(recalcRow);
        }

        function rowTemplate() {
            let cells = '';
            for (let c = 0; c < catCount; c++) {
                cells += '<td><input class="form-control year-value" type="number" step="any" name="value[' + c + '][]" placeholder="0"></td>';
            }
            const row = document.createElement('tr');
            row.className = 'year-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control" type="text" name="year[]" placeholder="e.g. 2016-17"></td>' +
                cells +
                '<td class="row-total text-secondary fw-bold">0</td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-year" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(rowTemplate());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-year');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.year-row');
            if (rows.length > 1) {
                removeBtn.closest('.year-row').remove();
            } else {
                const row = removeBtn.closest('.year-row');
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                recalcRow(row);
            }
            reindex();
        });

        tbody.addEventListener('input', function (e) {
            if (e.target.classList.contains('year-value')) {
                recalcRow(e.target.closest('.year-row'));
            }
        });

        // Keep the value-column headers in sync with the category labels
        document.querySelectorAll('.cat-label-input').forEach(function (input) {
            input.addEventListener('input', function () {
                const head = document.querySelector('.cat-col-head[data-idx="' + input.dataset.idx + '"]');
                if (head) head.textContent = input.value || ('Category ' + (parseInt(input.dataset.idx) + 1));
            });
        });

        recalcAll();
    });
</script>
</body>

</html>
