<script>
  document.addEventListener('DOMContentLoaded', function () {

    /* The page content box, where a page that is simply something to read
       is written. CKEditor is already loaded across the dashboard. */
    var body = document.getElementById('editor');
    if (body && typeof ClassicEditor !== 'undefined' && !body.dataset.ckInit) {
      body.dataset.ckInit = '1';
      ClassicEditor
        .create(body, { toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'] })
        .then(function (inst) {
          document.querySelector('form.banner-form')
            .addEventListener('submit', function () { body.value = inst.getData(); });
        })
        .catch(function (e) { console.error(e); });
    }

    /* A preview of the banner picture once one is chosen. */
    document.addEventListener('change', function (e) {
      if (!e.target.classList.contains('single-image-input')) return;

      var wrap = e.target.closest('.col-lg-4, .col-lg-6') || e.target.parentElement;
      var preview = wrap.querySelector('.img-preview');
      var file = e.target.files[0];
      if (!preview || !file) return;

      var reader = new FileReader();
      reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
      reader.readAsDataURL(file);
    });

    /* The list of people to write to. Removing the last row empties it rather
       than taking it away, so there is always one to type into. */
    var wrap = document.getElementById('contacts-wrap');
    var add = document.getElementById('btn-add-contact');
    if (!wrap || !add) return;

    function renumber() {
      wrap.querySelectorAll('.contact-item').forEach(function (row, i) {
        row.querySelector('.contact-index').textContent = i + 1;
      });
    }

    add.addEventListener('click', function () {
      var first = wrap.querySelector('.contact-item');
      if (!first) return;

      var copy = first.cloneNode(true);
      copy.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
      copy.querySelectorAll('select').forEach(function (el) { el.selectedIndex = 0; });

      wrap.appendChild(copy);
      renumber();
    });

    wrap.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-remove-contact');
      if (!btn) return;

      var row = btn.closest('.contact-item');
      if (wrap.querySelectorAll('.contact-item').length > 1) {
        row.remove();
      } else {
        row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
        row.querySelectorAll('select').forEach(function (el) { el.selectedIndex = 0; });
      }

      renumber();
    });

    renumber();
  });
</script>
