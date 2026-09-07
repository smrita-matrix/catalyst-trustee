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
     * The open list floats over the menu rather than sitting in it, so the
     * menu is told how tall to be while it is open - otherwise the last few
     * entries are cut off by the bottom of the menu.
     */
    function fitMenuTo(li) {
      var menu  = li.closest('.sub-menu');
      var panel = li.querySelector('.sebi-compliance-subsub-menu-custom-sec');
      if (!menu || !panel) { return; }

      var needed = panel.getBoundingClientRect().bottom - menu.getBoundingClientRect().top;
      menu.style.minHeight = Math.ceil(needed + 40) + 'px';
    }

    function releaseMenu(li) {
      var menu = li.closest('.sub-menu');
      if (menu) { menu.style.minHeight = ''; }
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
          fitMenuTo(li);
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
