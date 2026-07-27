<script>
    document.addEventListener('DOMContentLoaded', function () {

        function reindex(tbody) {
            tbody.querySelectorAll('.member-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function memberRow(prefix) {
            const row = document.createElement('tr');
            row.className = 'member-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 member-image-input" type="file" name="' + prefix + '_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><input type="hidden" name="' + prefix + '_existing_image[]" value=""><div class="img-preview member-image-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="' + prefix + '_name[]" placeholder="Name"></td>' +
                '<td><input class="form-control" type="text" name="' + prefix + '_designation[]" placeholder="Designation"></td>' +
                '<td><textarea class="form-control" name="' + prefix + '_description[]" rows="3" placeholder="Bio / description"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-member" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        // Add More
        document.querySelectorAll('.btn-add-member').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const tbody = document.getElementById(btn.dataset.target);
                tbody.appendChild(memberRow(btn.dataset.prefix));
                reindex(tbody);
            });
        });

        // Remove + preview (delegated on each member tbody)
        document.querySelectorAll('#board-tbody, #team-tbody').forEach(function (tbody) {
            tbody.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.btn-remove-member');
                if (!removeBtn) return;
                const rows = tbody.querySelectorAll('.member-row');
                if (rows.length > 1) {
                    removeBtn.closest('.member-row').remove();
                } else {
                    const row = removeBtn.closest('.member-row');
                    row.querySelectorAll('input, textarea').forEach(function (el) { if (el.type !== 'hidden') el.value = ''; });
                    const hidden = row.querySelector('input[type="hidden"]');
                    if (hidden) hidden.value = '';
                    const prev = row.querySelector('.member-image-preview');
                    if (prev) prev.innerHTML = '';
                }
                reindex(tbody);
            });

            tbody.addEventListener('change', function (e) {
                const input = e.target.closest('.member-image-input');
                if (!input) return;
                const preview = input.parentElement.querySelector('.member-image-preview');
                if (!preview) return;
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
    });
</script>
