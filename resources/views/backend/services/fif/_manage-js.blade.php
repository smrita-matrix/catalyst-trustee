<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---- rich editors ---- */
        var editors = [];
        var LIST_TOOLBAR = { toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','|','undo','redo'] };
        var TABLE_TOOLBAR = { toolbar: ['heading','|','bold','italic','|','insertTable','|','undo','redo'], table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] } };
        function initEditor(ta, config) {
            if (typeof ClassicEditor === 'undefined' || !ta || ta.dataset.ckInit) return;
            ta.dataset.ckInit = '1';
            ClassicEditor.create(ta, config || LIST_TOOLBAR)
                .then(function (inst) { editors.push({ ta: ta, inst: inst }); })
                .catch(function (e) { console.error(e); });
        }
        function destroyEditor(ta) {
            for (var i = editors.length - 1; i >= 0; i--) if (editors[i].ta === ta) { editors[i].inst.destroy().catch(function(){}); editors.splice(i,1); }
        }
        document.querySelectorAll('.rich-editor').forEach(function (ta) { initEditor(ta); });
        document.querySelectorAll('.rich-editor-table').forEach(function (ta) { initEditor(ta, TABLE_TOOLBAR); });
        var form = document.querySelector('form.banner-form');
        if (form) form.addEventListener('submit', function () { editors.forEach(function (e) { e.ta.value = e.inst.getData(); }); });

        /* ---- image previews ---- */
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('single-image-input')) return;
            var wrap = e.target.closest('.col-lg-6, .col-lg-4') || e.target.parentElement;
            var preview = wrap.querySelector('.img-preview');
            if (!preview) return;
            var file = e.target.files[0]; if (!file) return;
            var ext = file.name.split('.').pop().toLowerCase();
            if (['png','jpg','jpeg','webp','svg'].indexOf(ext) === -1) { alert('Please upload a valid image.'); e.target.value=''; return; }
            var reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        });

        /* ---- process tabs repeater ---- */
        var tabsWrap = document.getElementById('tabs-wrap');
        function reindexTabs() { tabsWrap.querySelectorAll('.tab-item').forEach(function (it, i) { it.querySelector('.tab-index').textContent = 'Tab ' + (i + 1); }); }
        document.getElementById('btn-add-tab').addEventListener('click', function () {
            var it = document.createElement('div');
            it.className = 'tab-item border rounded p-3 mb-3';
            it.innerHTML =
                '<div class="d-flex justify-content-between align-items-center mb-2"><b class="tab-index"></b>' +
                '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button></div>' +
                '<div class="row g-3">' +
                    '<div class="col-lg-8">' +
                        '<label class="form-label">Tab Title</label>' +
                        '<input class="form-control" type="text" name="tab_title[]" placeholder="e.g. Corpus">' +
                    '</div>' +
                    '<div class="col-lg-4">' +
                        '<label class="form-label">Image <span class="text-secondary">(optional)</span></label>' +
                        '<input class="form-control single-image-input" type="file" name="tab_image[]" accept=".jpg,.jpeg,.png,.webp,.svg">' +
                        '<input type="hidden" name="tab_existing_image[]" value="">' +
                        '<div class="img-preview mt-2"></div>' +
                    '</div>' +
                '</div>' +
                '<label class="form-label mt-2">Points <span class="text-secondary">(use the bullet-list button)</span></label>' +
                '<textarea class="form-control rich-editor" name="tab_points[]" rows="4"></textarea>';
            tabsWrap.appendChild(it);
            reindexTabs();
            initEditor(it.querySelector('.rich-editor'));
        });
        tabsWrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-tab'); if (!btn) return;
            var items = tabsWrap.querySelectorAll('.tab-item');
            var item = btn.closest('.tab-item');
            var ta = item.querySelector('.rich-editor');
            if (items.length > 1) { if (ta) destroyEditor(ta); item.remove(); }
            else { item.querySelectorAll('input, textarea').forEach(function (el){ el.value=''; }); if (ta) { destroyEditor(ta); ta.dataset.ckInit=''; initEditor(ta); } }
            reindexTabs();
        });
    });
</script>
