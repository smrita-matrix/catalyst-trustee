<script>
  /* The lifecycle strip.

     It shows four steps at a time on a wide screen and slides down to one on
     a phone. The two buttons step through it, and reaching either end simply
     disables the button rather than wrapping round, because the steps run in
     order and looping back would misread the sequence. */
  jQuery(function ($) {
    var $strip = $('.ptc-lifecycle-carousel');
    if (!$strip.length || !$.fn.owlCarousel) { return; }

    var $prev  = $('.ptc-life-prev');
    var $next  = $('.ptc-life-next');
    var total  = $strip.children('.ptc-life-slide').length;
    var at     = 0;

    $strip.owlCarousel({
      loop: false,
      margin: 20,
      nav: false,
      dots: true,
      autoplay: true,
      autoplayTimeout: 3500,
      autoplayHoverPause: true,
      smartSpeed: 700,
      responsive: { 0: { items: 1 }, 576: { items: 2 }, 992: { items: 4 } }
    });

    /** The furthest along the strip can actually go, given how many fit. */
    function lastStop() {
      var visible = $strip.find('.owl-item.active').length || 1;
      return Math.max(total - visible, 0);
    }

    function refresh() {
      $prev.prop('disabled', at <= 0);
      $next.prop('disabled', at >= lastStop());
    }

    $next.on('click', function () {
      if (at >= lastStop()) { return; }
      $strip.trigger('to.owl.carousel', [++at, 300]);
      refresh();
    });

    $prev.on('click', function () {
      if (at <= 0) { return; }
      $strip.trigger('to.owl.carousel', [--at, 300]);
      refresh();
    });

    // The strip also moves on its own and by dragging, so follow it.
    $strip.on('changed.owl.carousel', function (e) {
      at = e.item.index;
      refresh();
    });

    $(window).on('resize', refresh);
    refresh();
  });
</script>
