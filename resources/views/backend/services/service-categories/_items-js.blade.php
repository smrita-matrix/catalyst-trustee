<script>
    function previewIcon() {
        var input = document.getElementById('icon');
        var preview = document.getElementById('icon-preview');
        var existing = document.getElementById('existing_icon');
        var file = input.files[0];
        if (!file) return;
        var ext = file.name.split('.').pop().toLowerCase();
        if (['png','jpg','jpeg','webp','svg'].indexOf(ext) === -1) {
            alert('Please upload a valid image (png, jpg, jpeg, webp, svg).');
            input.value = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function (ev) {
            if (existing) existing.remove();
            preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">';
        };
        reader.readAsDataURL(file);
    }
</script>
