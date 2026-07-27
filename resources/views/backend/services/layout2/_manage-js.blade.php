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
        document.querySelectorAll('.rich-editor').forEach(initEditor);
        var form = document.querySelector('form.banner-form');
        if (form) form.addEventListener('submit', function () { editors.forEach(function (e) { e.ta.value = e.inst.getData(); }); });

        /* ---- image previews ---- */
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('single-image-input')) return;
            var wrap = e.target.closest('.col-lg-6, .col-lg-4') || e.target.parentElement;
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
    });
</script>
