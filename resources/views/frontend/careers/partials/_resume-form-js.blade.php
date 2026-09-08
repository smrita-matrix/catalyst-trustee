  <script>
    // Client-side checks so mistakes are caught before the page reloads.
    // Every rule here is enforced again on the server.
    (function () {
      var form = document.getElementById('career-form');
      if (!form) { return; }

      // \u00C0-\u024F covers accented Latin letters; written as escapes so the
      // rule never depends on the file's character encoding.
      var LETTERS  = /^[A-Za-z\u00C0-\u024F\s.'-]+$/;
      var EMAIL    = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
      var PHONE    = /^[0-9+\s-]{7,20}$/;
      var MAX_CV   = 5 * 1024 * 1024;
      var CV_TYPES = ['pdf', 'doc', 'docx'];


      function problem(field) {
        var name  = field.getAttribute('name');
        var value = (field.value || '').trim();

        if (name === 'first_name' || name === 'last_name' || name === 'city') {
          if (!value) { return 'This field is required.'; }
          if (!LETTERS.test(value)) { return 'Letters only - no numbers or symbols.'; }
          return null;
        }
        if (name === 'email') {
          if (!value) { return 'Please enter your email address.'; }
          if (!EMAIL.test(value)) { return 'Please enter a valid email address.'; }
          return null;
        }
        if (name === 'phone') {
          if (!value) { return 'Please enter your phone number.'; }
          if (!PHONE.test(value)) { return 'Digits only, at least 7 of them.'; }
          return null;
        }
        if (name === 'position') {
          if (!value) { return 'Please choose a position.'; }
          return null;
        }
        if (name === 'resume') {
          if (!field.files || !field.files.length) { return 'Please attach your resume.'; }
          var file = field.files[0];
          var ext  = file.name.split('.').pop().toLowerCase();
          if (CV_TYPES.indexOf(ext) === -1) { return 'Only PDF, DOC or DOCX files are accepted.'; }
          if (file.size > MAX_CV) { return 'The file must be smaller than 5MB.'; }
          return null;
        }
        return null;
      }

      function showError(field, message) {
        clearError(field);
        field.classList.add('is-invalid');
        var span = document.createElement('span');
        span.className = 'field-error';
        span.textContent = message;
        field.parentNode.appendChild(span);
      }

      function clearError(field) {
        field.classList.remove('is-invalid');
        var existing = field.parentNode.querySelector('.field-error');
        if (existing) { existing.parentNode.removeChild(existing); }
      }

      var fields = ['first_name', 'last_name', 'email', 'phone', 'city', 'position', 'resume']
        .map(function (n) { return form.querySelector('[name="' + n + '"]'); })
        .filter(function (el) { return !!el; });

      // Re-check a field once the visitor has moved on from it.
      fields.forEach(function (field) {
        ['blur', 'change'].forEach(function (evt) {
          field.addEventListener(evt, function () {
            var message = problem(field);
            if (message) { showError(field, message); } else { clearError(field); }
          });
        });
      });

      form.addEventListener('submit', function (e) {
        var failed = [];

        fields.forEach(function (field) {
          var message = problem(field);
          if (message) {
            showError(field, message);
            failed.push(field);
          } else {
            clearError(field);
          }
        });

        if (failed.length) {
          e.preventDefault();

          // The message under each field says what is wrong, so the page
          // only needs to move to the first one.
          failed[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
          failed[0].focus({ preventScroll: true });
          return;
        }

        // Give feedback while the file uploads, and block a double submit.
        var button = form.querySelector('button[type="submit"]');
        if (button) {
          button.disabled = true;
          button.dataset.label = button.textContent;
          button.textContent = 'Submitting...';
        }
      });

      // "Apply Now" pre-selects that position and jumps to the form.
      document.querySelectorAll('.apply-now').forEach(function (link) {
        link.addEventListener('click', function () {
          var select = document.getElementById('position-select');
          if (select) {
            select.value = link.getAttribute('data-position') || '';
            clearError(select);
          }
        });
      });
    })();
  </script>
</body>

</html>
