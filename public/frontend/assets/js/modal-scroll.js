/*
 * Keeps the page still when a pop-up is opened.
 *
 * The page glides to a stop after scrolling: what you see trails a little
 * behind where the page has actually been scrolled to, and catches up over
 * the next moment. Opening a pop-up freezes the page underneath, but that
 * catching-up carries on out of sight - so closing the pop-up left the
 * reader somewhere further down than where they clicked, which looked like
 * the page jumping away and coming back.
 *
 * Settling the glide the instant a pop-up opens fixes it: nothing moves on
 * screen, and the reader is returned to exactly where they were.
 */
(function () {
  'use strict';

  function smoother() {
    return (window.ScrollSmoother && window.ScrollSmoother.get)
      ? window.ScrollSmoother.get()
      : null;
  }

  function settle() {
    var s = smoother();
    if (!s) { return; }

    // Reading and writing the same value ends the glide where it stands.
    try { s.scrollTop(s.scrollTop()); } catch (e) { /* nothing to settle */ }
  }

  function init() {
    if (!window.jQuery) { return; }

    window.jQuery(document)
      .on('show.bs.modal', settle)
      .on('hidden.bs.modal', settle);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
