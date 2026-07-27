<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ================= Service Category -> Product cascade ================= */
        (function () {
            var catSel  = document.getElementById('category_id');
            var prodSel = document.getElementById('product_id');
            if (!catSel || !prodSel) return;

            var allOptions = Array.prototype.slice.call(prodSel.querySelectorAll('option[data-service]'));

            function filterProducts(keepSelected) {
                var catId = catSel.value;
                var current = prodSel.value;

                // clear all product options except placeholder
                allOptions.forEach(function (opt) { if (opt.parentNode) opt.parentNode.removeChild(opt); });

                var matched = allOptions.filter(function (opt) {
                    return !catId || opt.getAttribute('data-service') === catId;
                });
                matched.forEach(function (opt) { prodSel.appendChild(opt); });

                // keep selection if still valid, else reset
                if (keepSelected && matched.some(function (o) { return o.value === current; })) {
                    prodSel.value = current;
                } else {
                    prodSel.value = '';
                }
            }

            filterProducts(true);                    // initial (edit) load
            catSel.addEventListener('change', function () { filterProducts(false); });
        })();

        /* ================= Rich text editors (points) ================= */
        var editors = []; // { textarea, instance }

        function initEditor(textarea) {
            if (typeof ClassicEditor === 'undefined' || !textarea || textarea.dataset.ckInit) return;
            textarea.dataset.ckInit = '1';
            ClassicEditor
                .create(textarea, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .then(function (instance) { editors.push({ textarea: textarea, instance: instance }); })
                .catch(function (err) { console.error(err); });
        }

        function destroyEditor(textarea) {
            for (var i = editors.length - 1; i >= 0; i--) {
                if (editors[i].textarea === textarea) {
                    editors[i].instance.destroy().catch(function () {});
                    editors.splice(i, 1);
                }
            }
        }

        // init all existing rich fields
        document.querySelectorAll('.rich-editor').forEach(initEditor);

        // sync every editor back into its textarea before submit
        var form = document.querySelector('form.banner-form');
        if (form) {
            form.addEventListener('submit', function () {
                editors.forEach(function (e) { e.textarea.value = e.instance.getData(); });
            });
        }

        /* ================= generic image preview ================= */
        function previewFile(input) {
            var wrap = input.closest('.col-lg-6, .col-lg-4, td') || input.parentElement;
            var preview = wrap.querySelector('.img-preview');
            if (!preview) return;
            var file = input.files[0];
            if (!file) return;
            var ext = file.name.split('.').pop().toLowerCase();
            if (['png','jpg','jpeg','webp','svg'].indexOf(ext) === -1) {
                alert('Please upload a valid image (png, jpg, jpeg, webp, svg).');
                input.value = '';
                return;
            }
            var reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('single-image-input') || e.target.classList.contains('repeater-image-input')) {
                previewFile(e.target);
            }
        });

        /* ================= Why Catalyst cards repeater ================= */
        var cardsBody = document.getElementById('cards-tbody');
        var addCard   = document.getElementById('btn-add-card');

        function reindexCards() {
            cardsBody.querySelectorAll('.card-row').forEach(function (row, i) {
                row.querySelector('.row-index').textContent = i + 1;
            });
        }
        addCard.addEventListener('click', function () {
            var row = document.createElement('tr');
            row.className = 'card-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 repeater-image-input" type="file" name="why_card_icon[]" accept=".png,.jpg,.jpeg,.webp,.svg"><input type="hidden" name="why_card_existing_icon[]" value=""><div class="img-preview repeater-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="why_card_title[]" placeholder="Card title"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-card"><i class="fa fa-trash"></i></button></td>';
            cardsBody.appendChild(row);
            reindexCards();
        });
        cardsBody.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-card');
            if (!btn) return;
            var rows = cardsBody.querySelectorAll('.card-row');
            if (rows.length > 1) { btn.closest('.card-row').remove(); }
            else {
                var row = btn.closest('.card-row');
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                row.querySelector('.repeater-preview').innerHTML = '';
            }
            reindexCards();
        });

        /* ================= Recognition certificates repeater ================= */
        var certsBody = document.getElementById('certs-tbody');
        var addCert   = document.getElementById('btn-add-cert');

        function reindexCerts() {
            certsBody.querySelectorAll('.cert-row').forEach(function (row, i) {
                row.querySelector('.row-index').textContent = i + 1;
            });
        }
        addCert.addEventListener('click', function () {
            var row = document.createElement('tr');
            row.className = 'cert-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 repeater-image-input" type="file" name="certificate_image[]" accept=".jpg,.jpeg,.png,.webp"><input type="hidden" name="certificate_existing_image[]" value=""><div class="img-preview repeater-preview"></div></td>' +
                '<td><input class="form-control" type="text" name="certificate_alt[]" placeholder="Alt / caption"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-cert"><i class="fa fa-trash"></i></button></td>';
            certsBody.appendChild(row);
            reindexCerts();
        });
        certsBody.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-cert');
            if (!btn) return;
            var rows = certsBody.querySelectorAll('.cert-row');
            if (rows.length > 1) { btn.closest('.cert-row').remove(); }
            else {
                var row = btn.closest('.cert-row');
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                row.querySelector('.repeater-preview').innerHTML = '';
            }
            reindexCerts();
        });

        /* ================= Services Offered tabs repeater ================= */
        var tabsWrap = document.getElementById('tabs-wrap');
        var addTab   = document.getElementById('btn-add-tab');

        function reindexTabs() {
            tabsWrap.querySelectorAll('.tab-item').forEach(function (item, i) {
                item.querySelector('.tab-index').textContent = 'Tab ' + (i + 1);
            });
        }
        addTab.addEventListener('click', function () {
            var item = document.createElement('div');
            item.className = 'tab-item border rounded p-3 mb-3';
            item.innerHTML =
                '<div class="d-flex justify-content-between align-items-center mb-2"><b class="tab-index"></b>' +
                '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button></div>' +
                '<div class="row g-3">' +
                '<div class="col-lg-4"><label class="form-label">Tab Title</label>' +
                '<input class="form-control" type="text" name="tab_title[]" placeholder="e.g. Advisory">' +
                '<label class="form-label mt-2">Image</label>' +
                '<input class="form-control repeater-image-input" type="file" name="tab_image[]" accept=".jpg,.jpeg,.png,.webp">' +
                '<input type="hidden" name="tab_existing_image[]" value=""><div class="img-preview repeater-preview mt-2"></div></div>' +
                '<div class="col-lg-8"><label class="form-label">Points <span class="text-secondary">(use the bullet-list button in the editor)</span></label>' +
                '<textarea class="form-control rich-editor" name="tab_points[]" rows="6" placeholder="Add bullet points"></textarea></div>' +
                '</div>';
            tabsWrap.appendChild(item);
            reindexTabs();
            initEditor(item.querySelector('.rich-editor'));
        });
        tabsWrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-tab');
            if (!btn) return;
            var items = tabsWrap.querySelectorAll('.tab-item');
            var item = btn.closest('.tab-item');
            var ta = item.querySelector('.rich-editor');
            if (items.length > 1) {
                if (ta) destroyEditor(ta);
                item.remove();
            } else {
                item.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                if (ta) { destroyEditor(ta); ta.value = ''; ta.dataset.ckInit = ''; initEditor(ta); }
                item.querySelector('.repeater-preview').innerHTML = '';
            }
            reindexTabs();
        });
    });
</script>
