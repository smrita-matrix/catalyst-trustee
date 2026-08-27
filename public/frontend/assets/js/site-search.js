/*
 * Header search modal with live results.
 *
 * The magnifier opens a dialog; results are fetched as the visitor types and
 * listed below the box. Everything happens in the modal — there is no separate
 * search page to navigate to.
 */
(function () {
  'use strict';

  var MIN_CHARS = 2;
  var DEBOUNCE  = 250;

  function init() {
    var toggle  = document.getElementById('site-search-toggle');
    var modal   = document.getElementById('site-search-modal');
    var form    = document.getElementById('site-search-form');
    var input   = document.getElementById('site-search-input');
    var results = document.getElementById('site-search-results');

    if (!toggle || !modal || !form || !input || !results) { return; }

    var timer   = null;
    var request = null;   // in-flight fetch, so a slow reply can't overwrite a newer one
    var seq     = 0;

    /* ---------------- open / close ---------------- */

    function open(e) {
      if (e) { e.preventDefault(); }

      // Some pages carry sections with a very high z-index, and a stale
      // stylesheet can miss the rules below. Setting them inline here means the
      // dialog is always visible and on top, whatever the CSS says.
      modal.style.display  = 'block';
      modal.style.position = 'fixed';
      modal.style.top      = '0';
      modal.style.left     = '0';
      modal.style.right    = '0';
      modal.style.bottom   = '0';
      modal.style.zIndex   = '2147483000';

      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      setTimeout(function () { input.focus(); }, 30);
    }

    function close() {
      modal.style.display = 'none';
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      clear();
    }

    function clear() {
      results.innerHTML = '';
      results.classList.remove('is-visible');
    }

    toggle.addEventListener('click', open);

    modal.querySelectorAll('[data-search-close]').forEach(function (el) {
      el.addEventListener('click', close);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) { close(); }
    });

    /* ---------------- rendering ---------------- */

    function note(text) {
      results.innerHTML = '<div class="site-search-note">' + text + '</div>';
      results.classList.add('is-visible');
    }

    function render(data) {
      var rows = data.results || [];

      if (!rows.length) {
        note('No results for &ldquo;' + escapeHtml(data.term) + '&rdquo;');
        return;
      }

      var html = rows.map(function (row) {
        return '<a href="' + escapeAttr(row.url) + '">'
             + escapeHtml(row.title)
             + '<small>' + escapeHtml(row.group) + '</small>'
             + '</a>';
      }).join('');

      // Everything lives in this modal, so say when the list is capped.
      if (data.total > rows.length) {
        html += '<div class="site-search-all">Showing ' + rows.length
             +  ' of ' + data.total + ' matches — keep typing to narrow it down</div>';
      }

      results.innerHTML = html;
      results.classList.add('is-visible');
    }

    function escapeHtml(value) {
      return String(value == null ? '' : value)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function escapeAttr(value) {
      return escapeHtml(value).replace(/'/g, '&#39;');
    }

    /* ---------------- fetching ---------------- */

    function lookup(term) {
      var mine = ++seq;

      if (window.AbortController && request) { request.abort(); }
      var controller = window.AbortController ? new AbortController() : null;
      request = controller;

      fetch('/search/suggest?q=' + encodeURIComponent(term), {
        headers: { 'Accept': 'application/json' },
        signal: controller ? controller.signal : undefined
      })
        .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
        .then(function (data) {
          if (mine !== seq) { return; }   // a newer keystroke already won
          render(data);
        })
        .catch(function (err) {
          if (err && err.name === 'AbortError') { return; }
          if (mine !== seq) { return; }
          note('Search is unavailable right now.');
        });
    }

    input.addEventListener('input', function () {
      var term = input.value.trim();

      clearTimeout(timer);

      if (term.length < MIN_CHARS) {
        seq++;            // cancel any pending render
        clear();
        return;
      }

      timer = setTimeout(function () { lookup(term); }, DEBOUNCE);
    });

    /* ---------------- keyboard ---------------- */

    input.addEventListener('keydown', function (e) {
      var links = Array.prototype.slice.call(results.querySelectorAll('a'));
      if (!links.length) { return; }

      var index = links.findIndex(function (a) { return a.classList.contains('is-active'); });

      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        if (index >= 0) { links[index].classList.remove('is-active'); }
        index = e.key === 'ArrowDown'
          ? (index + 1) % links.length
          : (index <= 0 ? links.length - 1 : index - 1);
        links[index].classList.add('is-active');
        if (typeof links[index].scrollIntoView === 'function') {
          links[index].scrollIntoView({ block: 'nearest' });
        }
        return;
      }

      if (e.key === 'Enter' && index >= 0) {
        e.preventDefault();
        window.location.href = links[index].getAttribute('href');
      }
    });

    // The modal is the whole search, so pressing Enter just re-runs it here
    // rather than navigating anywhere.
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var term = input.value.trim();
      if (term.length >= MIN_CHARS) {
        clearTimeout(timer);
        lookup(term);
      } else {
        input.focus();
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
