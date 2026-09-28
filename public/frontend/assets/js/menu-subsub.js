/*
 * Opens the sub-list under a Public Notice menu entry when it is clicked.
 *
 * "SEBI Compliance by Debenture Trustee" and entries like it carry their own
 * list of pages. Showing all of them at once made that column very long, so the
 * list stays closed until its heading is clicked.
 *
 * The heading is a link with no address, so clicking it never changes the page
 * or leaves a "#" behind.
 */
(function () {
  'use strict';

  function init() {
    var toggles = document.querySelectorAll('.subsub-toggle');
    if (!toggles.length) { return; }

    /*
     * The open list floats over the menu rather than sitting in it, so two
     * things have to be arranged as it opens.
     *
     * It is lined up with the heading that opened it, so the list appears
     * beside the words that were clicked rather than at the top of the menu,
     * which reads as belonging to something else.
     *
     * And the menu is told how tall to be while it is open, or the last few
     * entries are cut off by the bottom of it.
     */
    /**
     * Where the list can open without landing on anything.
     *
     * Nearest to its own heading is best, so the two read as one thing. The
     * catch is the column next door: a list opening beside the first column
     * would cover the second. But the columns are short and the list opens
     * level with its heading, so once the heading is below the bottom of a
     * column there is nothing there to cover.
     *
     * So it starts beside its own column and is pushed right only past the
     * columns it would actually run into.
     */
    function nearestClearLeft(li, panel, menuBox, top) {
      var ownColumn = li.closest('.list-item');
      var gap = 26;
      var left = ownColumn.getBoundingClientRect().right - menuBox.left + gap;
      var height = panel.getBoundingClientRect().height;

      document.querySelectorAll('.notice-mega .list-item').forEach(function (col) {
        if (col === ownColumn) { return; }

        var box = col.getBoundingClientRect();
        var colLeft = box.left - menuBox.left;
        var colRight = box.right - menuBox.left;
        if (colRight <= left) { return; }             // already behind us

        // Only what the column actually holds counts, not the empty room
        // underneath it.
        var last = col.querySelector('ul');
        var colBottom = (last ? last.getBoundingClientRect().bottom : box.bottom) - menuBox.top;

        var clashes = top < colBottom && (top + height) > (box.top - menuBox.top);
        if (clashes) { left = Math.max(left, colRight + gap); }
      });

      // Never past the edge of the menu.
      var widest = menuBox.width - panel.getBoundingClientRect().width - gap;
      return Math.min(left, Math.max(widest, 0));
    }

    /*
     * The open list floats over the menu rather than sitting in it, so it is
     * placed as it opens: level with the heading that opened it, and as close
     * to that heading as it can go without covering anything.
     *
     * The menu is also told how tall to be while it is open, or the last few
     * entries are cut off by the bottom of it.
     */
    function placeBeside(li) {
      var menu   = li.closest('.sub-menu');
      var panel  = li.querySelector('.sebi-compliance-subsub-menu-custom-sec');
      var toggle = li.querySelector('.subsub-toggle');
      if (!menu || !panel || !toggle) { return; }

      // On a narrow screen the list sits under its heading in the ordinary
      // flow, so there is nothing to place.
      if (getComputedStyle(panel).position !== 'absolute') {
        panel.style.top = '';
        panel.style.left = '';
        return;
      }

      var menuBox = menu.getBoundingClientRect();
      var top = Math.max(Math.round(toggle.getBoundingClientRect().top - menuBox.top) - 10, 0);

      panel.style.top = top + 'px';
      panel.style.right = 'auto';
      panel.style.left = Math.round(nearestClearLeft(li, panel, menuBox, top)) + 'px';

      var needed = panel.getBoundingClientRect().bottom - menuBox.top;
      menu.style.minHeight = Math.ceil(needed + 40) + 'px';
    }

    function releaseMenu(li) {
      var menu  = li.closest('.sub-menu');
      var panel = li.querySelector('.sebi-compliance-subsub-menu-custom-sec');
      if (menu) { menu.style.minHeight = ''; }
      if (panel) { panel.style.top = ''; panel.style.left = ''; panel.style.right = ''; }
    }

    function close(li) {
      li.classList.remove('is-open');
      releaseMenu(li);
      var t = li.querySelector('.subsub-toggle');
      if (t) { t.setAttribute('aria-expanded', 'false'); }
    }

    toggles.forEach(function (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();          // keep the drop-down itself open

        var li = toggle.closest('.has-subsub');
        if (!li) { return; }

        var opening = !li.classList.contains('is-open');

        // Only one list open at a time within the same column.
        var list = li.parentElement;
        if (list) {
          list.querySelectorAll('.has-subsub.is-open').forEach(close);
        }

        if (opening) {
          li.classList.add('is-open');
          toggle.setAttribute('aria-expanded', 'true');
          placeBeside(li);
        }
      });
    });

    // Leaving the menu closes whatever was left open, so it starts fresh.
    document.querySelectorAll('.menu-item-has-children').forEach(function (parent) {
      parent.addEventListener('mouseleave', function () {
        parent.querySelectorAll('.has-subsub.is-open').forEach(close);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
