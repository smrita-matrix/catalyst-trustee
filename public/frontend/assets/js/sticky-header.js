/*
 * Keeps the header in view once the reader has scrolled past it.
 *
 * The header sits at the top of the page and scrolls away with it. Past the
 * first stretch of the page it is pinned to the top of the screen instead, so
 * the menu is always within reach on the long pages - the notices lists run to
 * a hundred rows and more.
 *
 * The header sits outside the wrapper the page scrolls within, so pinning it
 * is straightforward; the position is read from the smoother where it is
 * running, because that is what the reader is actually looking at.
 */
(function () {
  'use strict';

  var PIN_AFTER = 140;   // roughly the height of the header itself
  var RELEASE   = 90;    // let it go a little earlier, so it does not flicker

  function init() {
    var header = document.querySelector('header');
    if (!header) { return; }

    function scrolled() {
      var s = (window.ScrollSmoother && window.ScrollSmoother.get)
        ? window.ScrollSmoother.get()
        : null;

      return s ? s.scrollTop() : (window.pageYOffset || document.documentElement.scrollTop || 0);
    }

    var pinned = false;

    function update() {
      var y = scrolled();

      if (!pinned && y > PIN_AFTER) {
        pinned = true;
        header.classList.add('sticky-menu');
      } else if (pinned && y < RELEASE) {
        pinned = false;
        header.classList.remove('sticky-menu');
      }
    }

    var waiting = false;
    function onScroll() {
      if (waiting) { return; }
      waiting = true;
      window.requestAnimationFrame(function () { waiting = false; update(); });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    // The smoother moves the page itself, so a scroll event does not always
    // follow. Ticking alongside it keeps the header in step.
    if (window.gsap && window.gsap.ticker) {
      window.gsap.ticker.add(update);
    }

    update();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
