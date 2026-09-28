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
    function placeBeside(li) {
      var menu   = li.closest('.sub-menu');
      var panel  = li.querySelector('.sebi-compliance-subsub-menu-custom-sec');
      var toggle = li.querySelector('.subsub-toggle');
      if (!menu || !panel || !toggle) { return; }

      // On a narrow screen the list sits under its heading in the ordinary
      // flow, so there is nothing to line up.
      if (getComputedStyle(panel).position !== 'absolute') {
        panel.style.top = '';
        return;
      }

      var menuTop = menu.getBoundingClientRect().top;
      var rowTop  = toggle.getBoundingClientRect().top;

      panel.style.top = Math.max(Math.round(rowTop - menuTop) - 10, 0) + 'px';

      var needed = panel.getBoundingClientRect().bottom - menuTop;
      menu.style.minHeight = Math.ceil(needed + 40) + 'px';
    }

    function releaseMenu(li) {
      var menu  = li.closest('.sub-menu');
      var panel = li.querySelector('.sebi-compliance-subsub-menu-custom-sec');
      if (menu) { menu.style.minHeight = ''; }
      if (panel) { panel.style.top = ''; }
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
