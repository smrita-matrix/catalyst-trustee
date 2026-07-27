<script>
    function previewLogo() {
        const file = document.getElementById('logo_image').files[0];
        const preview = document.getElementById('logo-preview');
        const existing = document.getElementById('existing_logo');
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['svg', 'png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
            document.getElementById('logo_image').value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (ev) {
            if (existing) existing.remove();
            preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">';
        };
        reader.readAsDataURL(file);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tbody  = document.getElementById('services-tbody');
        const addBtn = document.getElementById('btn-add-service');

        function reindex() {
            tbody.querySelectorAll('.service-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function serviceRow() {
            const row = document.createElement('tr');
            row.className = 'service-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 service-icon-input" type="file" name="service_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg"><input type="hidden" name="service_existing_icon[]" value=""><div class="img-preview service-icon-preview"></div></td>' +
                '<td><textarea class="form-control" name="service_title[]" rows="2" placeholder="Service title"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-service" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(serviceRow()); reindex(); });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-service');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.service-row');
            if (rows.length > 1) {
                removeBtn.closest('.service-row').remove();
            } else {
                const row = removeBtn.closest('.service-row');
                row.querySelectorAll('input, textarea').forEach(function (el) { if (el.type !== 'hidden') el.value = ''; });
                const hidden = row.querySelector('input[type="hidden"]'); if (hidden) hidden.value = '';
                const prev = row.querySelector('.service-icon-preview'); if (prev) prev.innerHTML = '';
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.service-icon-input');
            if (!input) return;
            const preview = input.parentElement.querySelector('.service-icon-preview');
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
