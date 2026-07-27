<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---- rich editors ---- */
        var editors = [];
        function initEditor(ta) {
            if (typeof ClassicEditor === 'undefined' || !ta || ta.dataset.ckInit) return;
            ta.dataset.ckInit = '1';
            ClassicEditor.create(ta, { toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','|','undo','redo'] })
                .then(function (inst) { editors.push({ ta: ta, inst: inst }); })
                .catch(function (e) { console.error(e); });
        }
        function destroyEditor(ta) {
            for (var i = editors.length - 1; i >= 0; i--) if (editors[i].ta === ta) { editors[i].inst.destroy().catch(function(){}); editors.splice(i,1); }
        }
        document.querySelectorAll('.rich-editor').forEach(initEditor);
        var form = document.querySelector('form.banner-form');
        if (form) form.addEventListener('submit', function () { editors.forEach(function (e) { e.ta.value = e.inst.getData(); }); });

        /* ---- image previews ---- */
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('single-image-input') && !e.target.classList.contains('repeater-image-input')) return;
            var wrap = e.target.closest('.col-lg-6, .col-lg-4, .col-lg-3') || e.target.parentElement;
            var preview = wrap.querySelector('.img-preview');
            if (!preview) return;
            var file = e.target.files[0];
            if (!file) return;
            var ext = file.name.split('.').pop().toLowerCase();
            if (['png','jpg','jpeg','webp','svg'].indexOf(ext) === -1) { alert('Please upload a valid image.'); e.target.value=''; return; }
            var reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        });

        /* ---- services tabs repeater ---- */
        var wrap = document.getElementById('tabs-wrap');
        var addBtn = document.getElementById('btn-add-tab');
        function reindex() { wrap.querySelectorAll('.tab-item').forEach(function (it, i) { it.querySelector('.tab-index').textContent = 'Tab ' + (i + 1); }); }
        addBtn.addEventListener('click', function () {
            var it = document.createElement('div');
            it.className = 'tab-item border rounded p-3 mb-3';
            it.innerHTML =
                '<div class="d-flex justify-content-between align-items-center mb-2"><b class="tab-index"></b>' +
                '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button></div>' +
                '<div class="row g-3">' +
                '<div class="col-lg-3"><label class="form-label">Icon</label>' +
                '<input class="form-control repeater-image-input" type="file" name="tab_icon[]" accept=".png,.jpg,.jpeg,.webp,.svg">' +
                '<input type="hidden" name="tab_existing_icon[]" value=""><div class="img-preview repeater-preview mt-2"></div></div>' +
                '<div class="col-lg-9"><label class="form-label">Tab Title</label>' +
                '<input class="form-control" type="text" name="tab_title[]" placeholder="e.g. Fund Registration">' +
                '<label class="form-label mt-2">Intro Text <span class="text-secondary">(optional)</span></label>' +
                '<textarea class="form-control" name="tab_description[]" rows="2"></textarea>' +
                '<label class="form-label mt-2">Points <span class="text-secondary">(use the bullet-list button)</span></label>' +
                '<textarea class="form-control rich-editor" name="tab_points[]" rows="4"></textarea></div>' +
                '</div>';
            wrap.appendChild(it);
            reindex();
            initEditor(it.querySelector('.rich-editor'));
        });
        wrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-tab');
            if (!btn) return;
            var items = wrap.querySelectorAll('.tab-item');
            var item = btn.closest('.tab-item');
            var ta = item.querySelector('.rich-editor');
            if (items.length > 1) { if (ta) destroyEditor(ta); item.remove(); }
            else {
                item.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                if (ta) { destroyEditor(ta); ta.dataset.ckInit=''; initEditor(ta); }
                item.querySelector('.repeater-preview').innerHTML = '';
            }
            reindex();
        });
    });
</script>
