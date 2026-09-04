/*
 * Checks the grievance forms in the browser and puts each message directly
 * under the field it belongs to, so it is obvious what needs fixing.
 *
 * Every rule here is enforced again on the server. This only saves the visitor
 * a page reload; it is not what protects the data.
 *
 * One script serves both grievance pages: the rules are keyed by field name,
 * and a form is only checked on the fields it actually contains.
 */
(function () {
  'use strict';

  var LETTERS = /^[A-Za-zÀ-ɏ\s.'-]+$/;
  var EMAIL   = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  var PHONE   = /^[0-9+\s-]{7,20}$/;
  var PAN     = /^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/;

  /* name -> [label, check]. A check returns a message, or null when fine. */
  var RULES = {
    full_name: ['Full Name', function (v) {
      if (!v) { return 'Please enter your full name.'; }
      if (!LETTERS.test(v)) { return 'Letters only - no numbers or symbols.'; }
      return null;
    }],
    mobile: ['Mobile Number', function (v, field) {
      // Optional on the form that does not mark it required.
      if (!v) { return field.required ? 'Please enter your mobile number.' : null; }
      if (!PHONE.test(v)) { return 'Digits only, at least 7 of them.'; }
      return null;
    }],
    email: ['Email', function (v) {
      if (!v) { return 'Please enter your email address.'; }
      if (!EMAIL.test(v)) { return 'Please enter a valid email address.'; }
      return null;
    }],
    pan: ['PAN', function (v) {
      if (!v) { return 'Please enter your PAN.'; }
      if (!PAN.test(v)) { return 'Please enter a valid PAN, for example ABCDE1234F.'; }
      return null;
    }],
    isin: ['ISIN', function (v) {
      return v ? null : 'Please enter the ISIN.';
    }],
    issuer_name: ['Issuer Name', function (v) {
      return v ? null : 'Please enter the issuer name.';
    }],
    investment_details: ['Investment Details', function (v) {
      if (!v) { return 'Please enter your investment details.'; }
      if (v.length > 1000) { return 'Please keep this within 1000 characters.'; }
      return null;
    }],
    nature_of_complaint: ['Nature of Complaint', function (v) {
      if (!v) { return 'Please describe the nature of your complaint.'; }
      if (v.length > 1000) { return 'Please keep this within 1000 characters.'; }
      return null;
    }],
    address: ['Postal Address', function (v) {
      return v ? null : 'Please enter your full postal address.';
    }],
    bonds_held: ['No of Bonds held', function (v) {
      if (!v) { return 'Please enter the number of bonds held.'; }
      if (isNaN(v) || Number(v) < 1) { return 'Please enter a number of 1 or more.'; }
      return null;
    }],
    complaint_details: ['Details of Grievance', function (v) {
      if (!v) { return 'Please describe your grievance.'; }
      if (v.length > 1000) { return 'Please keep this within 1000 characters.'; }
      return null;
    }]
  };

  function init() {
    var forms = document.querySelectorAll('.grievances-form-box form');
    if (!forms.length) { return; }

    forms.forEach(function (form) {
      // Our messages replace the browser's own pop-ups.
      form.setAttribute('novalidate', 'novalidate');

      var fields = Object.keys(RULES)
        .map(function (n) { return form.querySelector('[name="' + n + '"]'); })
        .filter(Boolean);

      var boxes = form.querySelectorAll('[name="complaint_types[]"]');

      function holder(field) {
        return field.closest('.form-group') || field.parentNode;
      }

      function clear(field) {
        var box = holder(field);
        field.classList.remove('is-invalid');
        var old = box.querySelector('.field-error');
        if (old) { old.parentNode.removeChild(old); }
      }

      function show(field, message) {
        clear(field);
        field.classList.add('is-invalid');

        var span = document.createElement('span');
        span.className = 'field-error';
        span.textContent = message;
        holder(field).appendChild(span);
      }

      function problem(field) {
        var rule = RULES[field.getAttribute('name')];
        return rule ? rule[1]((field.value || '').trim(), field) : null;
      }

      // Tell people as they go, not only when they press Submit.
      fields.forEach(function (field) {
        ['blur', 'change'].forEach(function (evt) {
          field.addEventListener(evt, function () {
            var message = problem(field);
            if (message) { show(field, message); } else { clear(field); }
          });
        });
      });

      // At least one complaint type has to be ticked, where they exist.
      function tickedProblem() {
        if (!boxes.length) { return null; }

        var any = Array.prototype.some.call(boxes, function (b) { return b.checked; });

        return any ? null : 'Please tick at least one complaint particular.';
      }

      if (boxes.length) {
        boxes.forEach(function (b) {
          b.addEventListener('change', function () {
            var group = boxes[0].closest('.form-group');
            var old = group.querySelector('.field-error');
            if (!tickedProblem() && old) { old.parentNode.removeChild(old); }
          });
        });
      }

      form.addEventListener('submit', function (e) {
        var first = null;

        fields.forEach(function (field) {
          var message = problem(field);
          if (message) {
            show(field, message);
            if (!first) { first = field; }
          } else {
            clear(field);
          }
        });

        var ticks = tickedProblem();
        if (ticks) {
          var group = boxes[0].closest('.form-group');
          var old = group.querySelector('.field-error');
          if (old) { old.parentNode.removeChild(old); }

          var span = document.createElement('span');
          span.className = 'field-error';
          span.textContent = ticks;
          group.appendChild(span);

          if (!first) { first = boxes[0]; }
        }

        if (first) {
          e.preventDefault();

          // Bring the first problem into view. The site runs GSAP
          // ScrollSmoother, which moves the page itself, so hand it the job.
          var smooth = (window.ScrollSmoother && typeof window.ScrollSmoother.get === 'function')
            ? window.ScrollSmoother.get()
            : null;

          if (smooth && typeof smooth.scrollTo === 'function') {
            smooth.scrollTo(first, true, 'center center');
          } else if (typeof first.scrollIntoView === 'function') {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }

          try { first.focus({ preventScroll: true }); } catch (err) { first.focus(); }

          return;
        }

        var button = form.querySelector('button[type="submit"]');
        if (button) {
          button.disabled = true;
          button.textContent = 'Submitting...';
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
