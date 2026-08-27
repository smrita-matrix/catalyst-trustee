{{-- Shared add/edit body. $rows is the list of slides to pre-fill the repeater with. --}}
<!-- Heading -->
<div class="col-12">
    <label class="form-label" for="heading">Section Heading <span class="txt-danger">*</span></label>
    <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', $heading) }}" placeholder="e.g. Testimonials" required>
    <div class="invalid-feedback">Please enter the Heading.</div>
</div>

<!-- ============ Testimonials (table repeater) ============ -->
<div class="col-12">
    <hr class="mt-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Testimonials</h5>
        <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
            <i class="fa fa-plus"></i> Add More
        </button>
    </div>

    <div class="table-responsive custom-scrollbar">
        <table class="table table-bordered align-middle" id="items-table">
            <thead class="table-light">
                <tr>
                    <th style="width: 55px;">#</th>
                    <th>Testimonial Text <span class="txt-danger">*</span></th>
                    <th style="width: 200px;">Person Name</th>
                    <th style="width: 210px;">Designation</th>
                    <th style="width: 210px;">Company</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody id="items-tbody">
                @foreach ($rows as $i => $row)
                    <tr class="item-row">
                        <td class="row-index">{{ $i + 1 }}</td>
                        <td><textarea class="form-control" name="item_text[]" rows="4" placeholder="What the client said about Catalyst">{{ $row['text'] ?? '' }}</textarea></td>
                        <td><input class="form-control" type="text" name="item_name[]" value="{{ $row['name'] ?? '' }}" placeholder="e.g. Rakesh Punamiya"></td>
                        <td><input class="form-control" type="text" name="item_designation[]" value="{{ $row['designation'] ?? '' }}" placeholder="e.g. VP - Finance"></td>
                        <td><input class="form-control" type="text" name="item_company[]" value="{{ $row['company'] ?? '' }}" placeholder="e.g. JSW Energy Limited"></td>
                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <small class="text-secondary">
        <i class="fa fa-info-circle"></i>
        Each row becomes one slide in the carousel, in this order. Designation and Company appear together under the name.
        Long testimonials are fine — the card grows to fit. Empty rows are ignored.
    </small>
</div>

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
                '<td><textarea class="form-control" name="item_text[]" rows="4" placeholder="What the client said about Catalyst"></textarea></td>' +
                '<td><input class="form-control" type="text" name="item_name[]" placeholder="e.g. Rakesh Punamiya"></td>' +
                '<td><input class="form-control" type="text" name="item_designation[]" placeholder="e.g. VP - Finance"></td>' +
                '<td><input class="form-control" type="text" name="item_company[]" placeholder="e.g. JSW Energy Limited"></td>' +
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
                removeBtn.closest('.item-row').querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
            }
            reindex();
        });
    });
</script>
