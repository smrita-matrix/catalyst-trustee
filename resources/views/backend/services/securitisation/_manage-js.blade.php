<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---- rich editors ---- */
        var editors = [];
        var TOOLBAR = { toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','|','undo','redo'] };

        function initEditor(ta) {
            if (typeof ClassicEditor === 'undefined' || !ta || ta.dataset.ckInit) return;
            ta.dataset.ckInit = '1';
            ClassicEditor.create(ta, TOOLBAR)
                .then(function (inst) { editors.push({ ta: ta, inst: inst }); })
                .catch(function (e) { console.error(e); });
        }

        function destroyEditor(ta) {
            for (var i = editors.length - 1; i >= 0; i--) {
                if (editors[i].ta === ta) { editors[i].inst.destroy().catch(function () {}); editors.splice(i, 1); }
            }
        }

        document.querySelectorAll('.rich-editor').forEach(initEditor);

        // The editors hold their own copy of the text, so hand it back to the
        // textareas before the form is sent.
        var form = document.querySelector('form.banner-form');
        if (form) form.addEventListener('submit', function () {
            editors.forEach(function (e) { e.ta.value = e.inst.getData(); });
        });

        /* ---- picture previews ---- */
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('single-image-input')) return;
            var wrap = e.target.closest('td, .col-lg-4, .col-lg-6, .col-lg-3') || e.target.parentElement;
            var preview = wrap.querySelector('.img-preview');
            var file = e.target.files[0];
            if (!preview || !file) return;

            var ext = file.name.split('.').pop().toLowerCase();
            if (['png','jpg','jpeg','webp','svg'].indexOf(ext) === -1) {
                alert('Please choose a picture.'); e.target.value = ''; return;
            }

            var reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        });

        /**
         * Wire up one repeating list: its Add button, its Remove buttons and
         * the numbering down its left. Removing the last row empties it
         * instead of taking it away, so there is always one to type into.
         */
        function repeater(opts) {
            var wrap = document.getElementById(opts.wrap);
            var add  = document.getElementById(opts.add);
            if (!wrap || !add) return;

            function renumber() {
                wrap.querySelectorAll('.' + opts.item).forEach(function (it, i) {
                    var label = it.querySelector('.' + opts.index);
                    if (label) label.textContent = opts.label ? opts.label + ' ' + (i + 1) : (i + 1);
                });
            }

            add.addEventListener('click', function () {
                var first = wrap.querySelector('.' + opts.item);
                if (!first) return;

                var copy = first.cloneNode(true);
                copy.querySelectorAll('input, textarea').forEach(function (el) {
                    if (el.type !== 'button') el.value = '';
                });
                copy.querySelectorAll('.img-preview').forEach(function (p) { p.innerHTML = ''; });

                // A cloned editor is only markup; drop it and start a fresh one.
                copy.querySelectorAll('.ck').forEach(function (n) { n.remove(); });
                copy.querySelectorAll('.rich-editor').forEach(function (ta) {
                    ta.dataset.ckInit = '';
                    ta.style.display = '';
                    ta.value = '';
                });

                wrap.appendChild(copy);
                renumber();
                copy.querySelectorAll('.rich-editor').forEach(initEditor);
            });

            wrap.addEventListener('click', function (e) {
                var btn = e.target.closest('.' + opts.remove);
                if (!btn) return;

                var item = btn.closest('.' + opts.item);
                var rows = wrap.querySelectorAll('.' + opts.item);

                if (rows.length > 1) {
                    item.querySelectorAll('.rich-editor').forEach(destroyEditor);
                    item.remove();
                } else {
                    item.querySelectorAll('input, textarea').forEach(function (el) {
                        if (el.type !== 'button') el.value = '';
                    });
                    item.querySelectorAll('.img-preview').forEach(function (p) { p.innerHTML = ''; });
                    item.querySelectorAll('.rich-editor').forEach(function (ta) {
                        destroyEditor(ta); ta.dataset.ckInit = ''; initEditor(ta);
                    });
                }

                renumber();
            });

            renumber();
        }

        repeater({ wrap: 'blocks-wrap', add: 'btn-add-block', item: 'block-item', index: 'block-index', remove: 'btn-remove-block', label: 'Paragraph' });
        repeater({ wrap: 'glance-wrap', add: 'btn-add-glance', item: 'glance-item', index: 'glance-index', remove: 'btn-remove-glance' });
        repeater({ wrap: 'tabs-wrap',   add: 'btn-add-tab',    item: 'tab-item',    index: 'tab-index',    remove: 'btn-remove-tab', label: 'Tab' });
        repeater({ wrap: 'steps-wrap',  add: 'btn-add-step',   item: 'step-item',   index: 'step-index',   remove: 'btn-remove-step' });
    });
</script>
