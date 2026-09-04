/*
 * Filters a Public Notice table as the visitor types.
 *
 * Pages using the table layout can hold a great many rows - the credit ratings
 * page has 179 - so scrolling to find one issuer is impractical. This hides
 * rows that do not match and keeps the count in step.
 */
(function () {
  'use strict';

  function init() {
    var input = document.getElementById('notice-table-filter');
    var table = document.getElementById('notice-table');
    if (!input || !table) { return; }

    var rows  = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
    var count = document.querySelector('[data-table-count]');
    var empty = document.querySelector('[data-table-empty]');
    var total = rows.length;

    // Work from a lower-cased copy so the search does not re-read the page
    // on every keystroke.
    var haystacks = rows.map(function (r) {
      return (r.textContent || '').toLowerCase().replace(/\s+/g, ' ');
    });

    function apply() {
      var term = input.value.trim().toLowerCase();
      var shown = 0;

      rows.forEach(function (row, i) {
        var match = term === '' || haystacks[i].indexOf(term) !== -1;
        row.hidden = !match;
        if (match) { shown++; }
      });

      if (count) {
        count.textContent = term === ''
          ? total + ' entries'
          : shown + ' of ' + total + ' entries';
      }

      if (empty) { empty.hidden = shown !== 0; }
    }

    var timer = null;
    input.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(apply, 120);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
