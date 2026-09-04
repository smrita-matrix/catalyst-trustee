/*
 * Search and paging for a Public Notice table.
 *
 * Pages using the table design can hold a great many rows - the credit
 * ratings page has 179 - so they are shown a page at a time, with a search
 * box above. The two work together: searching looks at every row, not just
 * the ones on screen, and the pages are rebuilt around whatever it finds.
 */
(function () {
  'use strict';

  function init() {
    var input = document.getElementById('notice-table-filter');
    var table = document.getElementById('notice-table');
    if (!input || !table) { return; }

    var rows    = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
    var count   = document.querySelector('[data-table-count]');
    var empty   = document.querySelector('[data-table-empty]');
    var pager   = document.querySelector('[data-table-pager]');
    var numbers = document.querySelector('[data-page-numbers]');
    var prev    = document.querySelector('[data-page-prev]');
    var next    = document.querySelector('[data-page-next]');
    var perBox  = document.getElementById('notice-table-per');

    var total   = rows.length;
    var perPage = perBox ? readPerPage() : 25;
    var page    = 1;
    var matches = rows.slice();

    // Work from a lower-cased copy so the search does not re-read the page
    // on every keystroke.
    var haystacks = rows.map(function (r) {
      return (r.textContent || '').toLowerCase().replace(/\s+/g, ' ');
    });

    function readPerPage() {
      var v = perBox.value;
      return v === 'all' ? Infinity : parseInt(v, 10) || 25;
    }

    function pageCount() {
      return Math.max(1, Math.ceil(matches.length / perPage));
    }

    /* Which rows are on screen: the matching ones, cut down to this page. */
    function draw() {
      var pages = pageCount();
      if (page > pages) { page = pages; }
      if (page < 1) { page = 1; }

      var start = perPage === Infinity ? 0 : (page - 1) * perPage;
      var end   = perPage === Infinity ? matches.length : start + perPage;
      var shown = matches.slice(start, end);
      var onPage = new Set(shown);

      rows.forEach(function (r) { r.hidden = !onPage.has(r); });

      if (count) {
        var range = 'Showing ' + (start + 1) + '–' + Math.min(end, matches.length)
                  + ' of ' + matches.length;

        count.textContent = matches.length === 0
          ? 'no entries'
          : (matches.length === total
              ? range + ' entries'
              : range + ' matching entries');
      }

      if (empty) { empty.hidden = matches.length !== 0; }

      drawPager(pages);
    }

    /* A short run of page numbers around the current one, so a long list does
       not turn into a hundred buttons. */
    function windowOf(pages) {
      var out = [];
      var from = Math.max(1, page - 2);
      var to   = Math.min(pages, from + 4);
      from = Math.max(1, to - 4);

      if (from > 1) {
        out.push(1);
        if (from > 2) { out.push('gap'); }
      }
      for (var i = from; i <= to; i++) { out.push(i); }
      if (to < pages) {
        if (to < pages - 1) { out.push('gap'); }
        out.push(pages);
      }
      return out;
    }

    function drawPager(pages) {
      if (!pager) { return; }

      // Rebuild the numbers even when the pager is about to be hidden,
      // otherwise a search that fits on one page leaves the previous page
      // still marked as the current one.
      if (numbers) {
        numbers.innerHTML = '';
        windowOf(pages).forEach(function (n) {
          if (n === 'gap') {
            var dots = document.createElement('span');
            dots.className = 'notice-page-gap';
            dots.textContent = '…';
            numbers.appendChild(dots);
            return;
          }

          var b = document.createElement('button');
          b.type = 'button';
          b.className = 'notice-page-num' + (n === page ? ' is-current' : '');
          b.textContent = n;
          if (n === page) { b.setAttribute('aria-current', 'page'); }
          b.addEventListener('click', function () { goTo(n); });
          numbers.appendChild(b);
        });
      }

      if (prev) { prev.disabled = page === 1; }
      if (next) { next.disabled = page === pages; }

      // One page of results needs no paging.
      pager.hidden = pages < 2;
    }

    /* Move to a page and put the top of the table back in view, so the reader
       is not left halfway down the previous page. */
    function goTo(n) {
      page = n;
      draw();

      var top = table.getBoundingClientRect().top + window.pageYOffset - 120;
      var smoother = window.ScrollSmoother && window.ScrollSmoother.get
        ? window.ScrollSmoother.get() : null;

      if (smoother) {
        smoother.scrollTo(table, true, 'top 120px');
      } else {
        try { window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' }); }
        catch (e) { window.scrollTo(0, Math.max(top, 0)); }
      }
    }

    function filter() {
      var term = input.value.trim().toLowerCase();

      matches = term === ''
        ? rows.slice()
        : rows.filter(function (r, i) { return haystacks[i].indexOf(term) !== -1; });

      // A new search starts at the beginning of its own results.
      page = 1;
      draw();
    }

    var timer = null;
    input.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(filter, 120);
    });

    if (perBox) {
      perBox.addEventListener('change', function () {
        perPage = readPerPage();
        page = 1;
        draw();
      });
    }

    if (prev) { prev.addEventListener('click', function () { if (page > 1) { goTo(page - 1); } }); }
    if (next) { next.addEventListener('click', function () { if (page < pageCount()) { goTo(page + 1); } }); }

    draw();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
