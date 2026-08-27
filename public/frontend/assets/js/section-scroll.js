/*
 * Scrolls to a page section when a menu item pointing at it is clicked.
 *
 * Every section stays visible — this only moves the page. Sections opt in with
 * an id plus data-section-tab:
 *
 *   <section id="current-openings" data-section-tab> ... </section>
 *
 * Any link ending in #current-openings then scrolls there, whether it is in the
 * header drop-down, in the page body, or arrives as a hash on a fresh load.
 *
 * The site runs GSAP ScrollSmoother, which hijacks the scroll position, so a
 * plain scrollIntoView lands in the wrong place. When the smoother is present we
 * hand the job to it and fall back to native scrolling when it is not.
 */
(function () {
  'use strict';

  function init() {
    var panels = Array.prototype.slice.call(document.querySelectorAll('[data-section-tab]'));
    if (!panels.length) { return; }

    /** Height of the sticky header, so a heading never hides underneath it. */
    function headerOffset() {
      var header = document.querySelector('header');
      var height = header ? header.offsetHeight : 0;
      return (height || 90) + 15;
    }

    function smoother() {
      return (window.ScrollSmoother && typeof window.ScrollSmoother.get === 'function')
        ? window.ScrollSmoother.get()
        : null;
    }

    function panelById(id) {
      for (var i = 0; i < panels.length; i++) {
        if (panels[i].id === id) { return panels[i]; }
      }
      return null;
    }

    function goTo(panel) {
      if (!panel) { return; }

      var offset = headerOffset();
      var smooth = smoother();

      if (smooth && typeof smooth.scrollTo === 'function') {
        // Align the section's top just below the header.
        smooth.scrollTo(panel, true, 'top ' + offset + 'px');
        return;
      }

      var rect = panel.getBoundingClientRect();
      var top = rect.top + (window.pageYOffset || document.documentElement.scrollTop) - offset;

      if (typeof window.scrollTo === 'function') {
        try {
          window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' });
        } catch (e) {
          window.scrollTo(0, Math.max(top, 0));
        }
      }
    }

    function hashPanel() {
      var hash = (window.location.hash || '').replace(/^#/, '');
      return hash ? panelById(hash) : null;
    }

    // Any link pointing at one of this page's sections.
    document.addEventListener('click', function (e) {
      var link = e.target.closest ? e.target.closest('a[href*="#"]') : null;
      if (!link) { return; }

      var href = link.getAttribute('href') || '';
      var hash = href.substring(href.indexOf('#') + 1);
      if (!hash) { return; }

      var panel = panelById(hash);
      if (!panel) { return; }

      // Links to another page are left to the browser; it will arrive with the
      // hash and the load handler below takes over.
      var path = link.pathname || '';
      if (path && path !== window.location.pathname) { return; }

      e.preventDefault();
      goTo(panel);

      if (window.history && window.history.replaceState) {
        window.history.replaceState(null, '', '#' + hash);
      }
    });

    // Clicking the same menu item twice, or using back/forward.
    window.addEventListener('hashchange', function () {
      goTo(hashPanel());
    });

    // Arriving with a hash. ScrollSmoother is created after this script runs,
    // so wait for the page to settle before moving.
    var pending = hashPanel();
    if (pending) {
      window.addEventListener('load', function () {
        setTimeout(function () { goTo(hashPanel()); }, 400);
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
