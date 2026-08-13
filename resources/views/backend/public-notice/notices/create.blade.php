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
                <div class="col-6"><h4>Add Notices</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('notices.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Notices</li>
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
                        <h4>Add Notices to a Section</h4>
                        <p class="f-m-light mt-1 mb-0">Pick one <b>Section</b>, then add its notices below. The columns change to match the section you chose, so only what's needed is shown.</p>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation custom-input banner-form" novalidate action="{{ route('notices.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4 mb-4">
                                <div class="col-lg-5">
                                    <label class="form-label">Section <span class="txt-danger">*</span></label>
                                    <select class="form-control" name="section" id="section-select">
                                        @foreach (\App\Models\Notice::SECTIONS as $val => $label)
                                            <option value="{{ $val }}" {{ old('section', $section) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-secondary" id="section-hint"></small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Notices</b>
                                <button type="button" id="btn-add-notice" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>

                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle" id="notices-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th class="col-period" style="width: 160px;">Period <span class="text-secondary">(date/month)</span></th>
                                            <th class="col-date" style="width: 140px;">Date</th>
                                            <th style="width: 220px;">Title <span class="txt-danger">*</span></th>
                                            <th class="col-desc">Description</th>
                                            <th style="width: 200px;">Document (PDF) <span class="text-secondary">(upload)</span></th>
                                            <th style="width: 70px;">Order</th>
                                            <th style="width: 45px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="notices-tbody">
                                        @php $rows = old('title', ['']); @endphp
                                        @foreach ($rows as $i => $r)
                                        <tr class="notice-row">
                                            <td class="row-index">{{ $i + 1 }}</td>
                                            <td class="col-period"><input class="form-control" type="text" name="period[]" value="{{ old('period.'.$i) }}" placeholder="e.g. September 2025"></td>
                                            <td class="col-date"><input class="form-control" type="text" name="notice_date[]" value="{{ old('notice_date.'.$i) }}" placeholder="16.01.2025"></td>
                                            <td><input class="form-control" type="text" name="title[]" value="{{ old('title.'.$i) }}" placeholder="Company / notice name"></td>
                                            <td class="col-desc"><textarea class="form-control" name="description[]" rows="2" placeholder="Property / notice description">{{ old('description.'.$i) }}</textarea></td>
                                            <td>
                                                <input class="form-control mb-2 notice-doc-input" type="file" name="document_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                                <div class="notice-doc-name small text-muted"></div>
                                            </td>
                                            <td><input class="form-control" type="number" name="sort_order[]" value="{{ old('sort_order.'.$i, 0) }}" placeholder="0"></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-notice" title="Remove"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-secondary"><i class="fa fa-info-circle"></i> Only <b>Title</b> is required. Lower "Order" numbers appear first. Empty rows (no Title) are ignored.</small>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-3">
                                <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const tbody   = document.getElementById('notices-tbody');
        const addBtn  = document.getElementById('btn-add-notice');
        const select  = document.getElementById('section-select');
        const hint    = document.getElementById('section-hint');
        const table   = document.getElementById('notices-table');

        const HINTS = {
            bomsc: 'Grouped by Period (shown as a date pill). Fields: Period, Title, Document.',
            boc:   'Grouped by Period into collapsible months. Fields: Period, Title, Document.',
            auc:   'Auction cards. Fields: Date, Title, Description, Document.'
        };

        // Show/hide columns based on the chosen section
        function applySection() {
            const s = select.value;
            const isAuc = (s === 'auc');
            table.querySelectorAll('.col-period').forEach(function (el) { el.style.display = isAuc ? 'none' : ''; });
            table.querySelectorAll('.col-date').forEach(function (el) { el.style.display = isAuc ? '' : 'none'; });
            table.querySelectorAll('.col-desc').forEach(function (el) { el.style.display = isAuc ? '' : 'none'; });
            hint.textContent = HINTS[s] || '';
        }
        select.addEventListener('change', applySection);

        function reindex() {
            tbody.querySelectorAll('.notice-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function noticeRow() {
            const row = document.createElement('tr');
            row.className = 'notice-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td class="col-period"><input class="form-control" type="text" name="period[]" placeholder="e.g. September 2025"></td>' +
                '<td class="col-date"><input class="form-control" type="text" name="notice_date[]" placeholder="16.01.2025"></td>' +
                '<td><input class="form-control" type="text" name="title[]" placeholder="Company / notice name"></td>' +
                '<td class="col-desc"><textarea class="form-control" name="description[]" rows="2" placeholder="Property / notice description"></textarea></td>' +
                '<td><input class="form-control mb-2 notice-doc-input" type="file" name="document_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp"><div class="notice-doc-name small text-muted"></div></td>' +
                '<td><input class="form-control" type="number" name="sort_order[]" value="0" placeholder="0"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-notice" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(noticeRow()); reindex(); applySection(); });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-notice');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.notice-row');
            if (rows.length > 1) {
                removeBtn.closest('.notice-row').remove();
            } else {
                const row = removeBtn.closest('.notice-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                const name = row.querySelector('.notice-doc-name'); if (name) name.textContent = '';
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.notice-doc-input');
            if (!input) return;
            const nameBox = input.parentElement.querySelector('.notice-doc-name');
            const file = input.files[0];
            if (nameBox) nameBox.textContent = file ? file.name : '';
        });

        applySection();
    });
</script>
</body>

</html>
