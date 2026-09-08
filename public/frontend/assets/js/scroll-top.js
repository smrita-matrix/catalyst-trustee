/*
 * "Back to top" button.
 *
 * Appears once the reader has gone past the first screen, and takes them
 * back to the very top - where the logo and menu are - rather than to the
 * first section below them.
 *
 * The page scrolls through a smoother, so the trip up is handed to it;
 * without that the button would jump instead of gliding. Where the smoother
 * is not running - a narrow screen, or if it fails to start - the browser's
 * own smooth scrolling is used instead.
 */
(function () {
  'use strict';

  var SHOW_AFTER = 400;   // roughly half a screen

  function init() {
    var button = document.getElementById('scroll-to-top');
    if (!button) { return; }

    function smoother() {
      return (window.ScrollSmoother && window.ScrollSmoother.get)
        ? window.ScrollSmoother.get()
        : null;
    }

    function scrolled() {
      var s = smoother();
      return s ? s.scrollTop() : (window.pageYOffset || document.documentElement.scrollTop || 0);
    }

    var showing = false;

    function update() {
      var past = scrolled() > SHOW_AFTER;
      if (past === showing) { return; }

      showing = past;
      button.classList.toggle('is-visible', past);
      button.setAttribute('aria-hidden', past ? 'false' : 'true');
      button.tabIndex = past ? 0 : -1;
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
    // follow. Ticking alongside it keeps the button in step.
    if (window.gsap && window.gsap.ticker) {
      window.gsap.ticker.add(update);
    }

    button.addEventListener('click', function (e) {
      e.preventDefault();

      var s = smoother();
      var gentle = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      if (s) {
        s.scrollTo(0, gentle);
        return;
      }

      try {
        window.scrollTo({ top: 0, behavior: gentle ? 'smooth' : 'auto' });
      } catch (err) {
        window.scrollTo(0, 0);
      }
    });

    update();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
