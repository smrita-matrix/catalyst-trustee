/*
 * Photo sliders for the "Life at Catalyst" stories.
 *
 * The approved design carries four copies of this code, one per story, each
 * tied to its own numbered class. Stories here are added from the dashboard
 * and there can be any number of them, so this runs once over every slider on
 * the page and keeps each one's counter and progress bar to itself.
 */
jQuery(function ($) {
  'use strict';

  var AUTOPLAY = 6000;

  $('.inc-day-slider-sec').each(function () {
    var $section = $(this);
    var $slider = $section.find('.inc-day-slider');

    if (!$slider.length) { return; }

    var $current = $section.find('[data-current]');
    var $total = $section.find('[data-total]');
    var $progress = $section.find('[data-progress]');
    var totalSlides = $slider.find('.item').length;

    function pad(n) {
      return n < 10 ? '0' + n : '' + n;
    }

    // Restart the bar from zero. Clearing the animation and reading a layout
    // value in between forces the browser to start it again rather than
    // carrying on from where it was.
    function runProgress() {
      if (!$progress.length) { return; }

      $progress.removeClass('is-running').css('animation', 'none');
      void $progress[0].offsetWidth;
      $progress.css('animation', '')
               .css('animation-duration', AUTOPLAY + 'ms')
               .addClass('is-running');
    }

    $current.text('01');
    $total.text(pad(totalSlides));

    // One photo needs no controls, and autoplay on a single slide just makes
    // the progress bar loop for no reason.
    var single = totalSlides < 2;

    $slider.owlCarousel({
      items: 1,
      loop: !single,
      margin: 0,
      nav: !single,
      dots: false,
      autoplay: !single,
      autoplayTimeout: AUTOPLAY,
      autoplayHoverPause: true,
      smartSpeed: 900,
      navText: [
        '<i class="glyphicon glyphicon-chevron-left"></i>',
        '<i class="glyphicon glyphicon-chevron-right"></i>'
      ]
    });

    $slider.on('changed.owl.carousel', function (e) {
      if (typeof e.item.index === 'undefined' || !totalSlides) { return; }

      $current.text(pad((e.item.index % totalSlides) + 1));
      $total.text(pad(totalSlides));
      runProgress();
    });

    if (!single) { runProgress(); }
  });
});
