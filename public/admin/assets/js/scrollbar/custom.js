(function () {
  var myElement = document.getElementById("simple-bar");
  if (!myElement) return;
  var sb = new SimpleBar(myElement, { autoHide: true });

  // Re-measure the sidebar after the page fully settles, so late layout changes
  // (rich-text editors, images, etc.) don't leave the menu clipped / blank.
  function recalc() { try { sb.recalculate(); } catch (e) {} }
  window.addEventListener("load", function () {
    recalc();
    setTimeout(recalc, 400);
    setTimeout(recalc, 1200);
  });
})();
