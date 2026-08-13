<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>Add Offices</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('contact.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Offices</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Add Office Locations</h4>
                        <p class="f-m-light mt-1 mb-0">Choose the <b>group</b>, then add offices below. Columns change to match the group. Only <b>City</b> is required.</p>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation custom-input" novalidate action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <div class="row g-4 mb-4">
                                <div class="col-lg-5">
                                    <label class="form-label">Group <span class="txt-danger">*</span></label>
                                    <select class="form-control" name="type" id="type-select">
                                        @foreach (\App\Models\ContactOffice::TYPES as $val => $label)
                                            <option value="{{ $val }}" {{ old('type', $type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Offices</b>
                                <button type="button" id="btn-add-office" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>

                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle" id="offices-table" style="min-width:1000px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th style="width:140px;">City <span class="txt-danger">*</span></th>
                                            <th class="col-main" style="width:150px;">Role</th>
                                            <th class="col-branch" style="width:110px;">Tag</th>
                                            <th>Address</th>
                                            <th class="col-main" style="width:160px;">Contact (phone)</th>
                                            <th style="width:180px;">Email</th>
                                            <th style="width:150px;">Map Link</th>
                                            <th style="width:70px;">Order</th>
                                            <th style="width:45px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="offices-tbody">
                                        @php $rows = old('city', ['']); @endphp
                                        @foreach ($rows as $i => $r)
                                        <tr class="office-row">
                                            <td class="row-index">{{ $i + 1 }}</td>
                                            <td><input class="form-control" type="text" name="city[]" value="{{ old('city.'.$i) }}" placeholder="Mumbai"></td>
                                            <td class="col-main"><input class="form-control" type="text" name="role[]" value="{{ old('role.'.$i) }}" placeholder="Corporate Office"></td>
                                            <td class="col-branch"><input class="form-control" type="text" name="tag[]" value="{{ old('tag.'.$i) }}" placeholder="PAN India"></td>
                                            <td><textarea class="form-control" name="address[]" rows="2" placeholder="Full address">{{ old('address.'.$i) }}</textarea></td>
                                            <td class="col-main"><input class="form-control" type="text" name="contact[]" value="{{ old('contact.'.$i) }}" placeholder="+91 22 4922 0555"></td>
                                            <td><input class="form-control" type="text" name="email[]" value="{{ old('email.'.$i) }}" placeholder="dt.mumbai@ctltrustee.com"></td>
                                            <td><input class="form-control" type="text" name="map_link[]" value="{{ old('map_link.'.$i) }}" placeholder="https://maps..."></td>
                                            <td><input class="form-control" type="number" name="sort_order[]" value="{{ old('sort_order.'.$i, 0) }}" placeholder="0"></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-office"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-3">
                                <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody  = document.getElementById('offices-tbody');
        const addBtn = document.getElementById('btn-add-office');
        const select = document.getElementById('type-select');
        const table  = document.getElementById('offices-table');

        function applyType() {
            const isMain = (select.value === 'main');
            table.querySelectorAll('.col-main').forEach(function (el){ el.style.display = isMain ? '' : 'none'; });
            table.querySelectorAll('.col-branch').forEach(function (el){ el.style.display = isMain ? 'none' : ''; });
        }
        select.addEventListener('change', applyType);

        function reindex() {
            tbody.querySelectorAll('.office-row').forEach(function (row, idx) { row.querySelector('.row-index').textContent = idx + 1; });
        }

        function officeRow() {
            const row = document.createElement('tr');
            row.className = 'office-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control" type="text" name="city[]" placeholder="Mumbai"></td>' +
                '<td class="col-main"><input class="form-control" type="text" name="role[]" placeholder="Corporate Office"></td>' +
                '<td class="col-branch"><input class="form-control" type="text" name="tag[]" placeholder="PAN India"></td>' +
                '<td><textarea class="form-control" name="address[]" rows="2" placeholder="Full address"></textarea></td>' +
                '<td class="col-main"><input class="form-control" type="text" name="contact[]" placeholder="+91 22 4922 0555"></td>' +
                '<td><input class="form-control" type="text" name="email[]" placeholder="email@ctltrustee.com"></td>' +
                '<td><input class="form-control" type="text" name="map_link[]" placeholder="https://maps..."></td>' +
                '<td><input class="form-control" type="number" name="sort_order[]" value="0" placeholder="0"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-office"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(officeRow()); reindex(); applyType(); });

        tbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-remove-office'); if (!btn) return;
            const rows = tbody.querySelectorAll('.office-row');
            if (rows.length > 1) { btn.closest('.office-row').remove(); }
            else { btn.closest('.office-row').querySelectorAll('input, textarea').forEach(function (el){ el.value=''; }); }
            reindex();
        });

        applyType();
    });
</script>
</body>

</html>
