// Weave Animation Js
// const target = document.getElementById("tj-weave-anim");
// function splitTextToSpans(targetElement) {
//   if (targetElement) {
//     const text = targetElement.textContent;
//     targetElement.innerHTML = "";

//     for (let character of text) {
//       const span = document.createElement("span");
//       if (character === " ") {
//         span.innerHTML = "&nbsp;";
//       } else {
//         span.textContent = character;
//       }
//       targetElement.appendChild(span);
//     }
//   }
// }
// splitTextToSpans(target);

 window.addEventListener("load", function () {
    gsap.registerPlugin(ScrollTrigger);

    const mm = gsap.matchMedia();

    mm.add("(min-width: 992px)", () => {

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: ".proofs-section",
                start: "top top",
                end: "+=2500",
                scrub: 1,
                pin: false,
                pinSpacing: true,
                invalidateOnRefresh: true
            }
        });

        tl.to(".card-1", {
            x: -550,
            y: -250,
            rotation: -8
        }, 0);

        tl.to(".card-2", {
            x: 350,
            y: -180,
            rotation: 8
        }, 0);

        tl.to(".card-3", {
            x: -320,
            y: 250,
            rotation: 6
        }, 0);

        tl.to(".card-4", {
            x: 520,
            y: 280,
            rotation: -6
        }, 0);

        ScrollTrigger.refresh();
    });
});

  // Preloader js
  $(window).on("load", () => {
    const $preloader = $(".tj-preloader");

    if ($preloader.length) {
      setTimeout(() => {
        $preloader
          .removeClass("is-loading")
          .addClass("is-loaded")
          .delay(700)
          .fadeOut(400);
      }, 2000);
    }
  });

 //aos animation
  AOS.init({
    once: true
  })

  //hero banner
  $(".banner-slider").owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    smartSpeed: 1200,
    animateOut: "fadeOut",
    mouseDrag: true,
    touchDrag: true,
    nav: false,
    dots: false
  });

  ////////////////////////////
    // card add one by one
        gsap.registerPlugin(ScrollTrigger);
        let mm = gsap.matchMedia();
        mm.add("(min-width: 992px)", () => {
            const cards = gsap.utils.toArray(".cnt-portfolio-video-card");
            // Pin right image
            ScrollTrigger.create({
                trigger: ".why-wrap",
                start: "top top+=100",
                end: "bottom bottom",
                pin: ".sticky-img",
                pinSpacing: false
            });
            // Stack cards
            cards.forEach((card) => {
                ScrollTrigger.create({
                    trigger: card,
                    start: "top 100px",
                    endTrigger: ".why-wrap",
                    end: "bottom bottom",
                    pin: true,
                    pinSpacing: false
                });
            });

        });

//smooth scrolling
if($('#smooth-wrapper').length && $('#smooth-content').length){
    gsap.registerPlugin(ScrollTrigger, ScrollSmoother, TweenMax, ScrollToPlugin);
  
    gsap.config({
      nullTargetWarn: false,
    });
  
    let smoother = ScrollSmoother.create({
      smooth: 2,
      effects: true,
      smoothTouch: 0.1,
      normalizeScroll: false,
      ignoreMobileResize: true,
    });

  }

  //counters
  jQuery(document).ready(function ($) {
    $('.counter-number').counterUp({
        delay: 10,
        time: 1000
    });
  });

  $(document).ready(function() {
    var owl = $('.sebi');
    owl.owlCarousel({
      margin: 40,
      loop: true,
      dots: true,
      autoplay: true,
      autoplayTimeout: 4500,
      navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"], 
      responsive: {
        0: {
            items: 1
        },
        480: {
            items: 1
        },
        768: {
            items:2,
            nav: false,
        },
        992: {
            items: 3,
            nav: false,
        },
        1200: {
            items: 3,
            nav: false,
        },
        1440: {
            items: 3,
            nav: false,
        }
      }
    })
  })

  $(document).ready(function() {
    var owl = $('.nonsebi');
    owl.owlCarousel({
      margin: 40,
      loop: true,
      dots: true,
      autoplay: true,
      autoplayTimeout: 4500,
      navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"], 
      responsive: {
        0: {
            items: 1
        },
        480: {
            items: 1
        },
        768: {
            items:3,
            nav: false,
        },
        992: {
            items: 3,
            nav: false,
        },
        1200: {
            items: 4,
            nav: false,
        },
        1440: {
            items: 4,
            nav: false,
        }
      }
    })
  })

  const swiper = new Swiper('.landmarkSwiper', {
  slidesPerView: 'auto',
  spaceBetween: 24,
  loop: true,
  speed: 5000,

  autoplay: {
    delay: 0,
    disableOnInteraction: false,
    pauseOnMouseEnter: false,
    waitForTransition: true,
  },

  freeMode: {
    enabled: true,
    momentum: false,
    sticky: false,
  },

  allowTouchMove: true,

  // Important
  simulateTouch: true,
  grabCursor: false,

  breakpoints: {
    0: { slidesPerView: 1 },
    768: { slidesPerView: 2 },
    992: { slidesPerView: 4 }
  }
});

// Force autoplay to continue after any interaction
swiper.on('touchEnd', () => {
  swiper.autoplay.start();
});

swiper.on('click', () => {
  swiper.autoplay.start();
});


  window.addEventListener("load", function () {

    setTimeout(function () {

        ScrollTrigger.refresh();

    }, 1000);

});


  

// function homeSlider(){
    
//     // main-silder-swiper
//     if(jQuery('.main-silder-swiper').length > 0){
//         var swiper = new Swiper('.main-silder-swiper', {
//             speed: 1500,
//             parallax: true,
//             loop:true,
//             autoplay: {
//                delay: 3000,
//             },
//             navigation: {
//                 nextEl: '.swiper-button-next1',
//                 prevEl: '.swiper-button-prev1',
//             },
//         });
//     }
// }


// if(window.innerWidth <= 480){
//   document.querySelector(".schedule-btn").textContent = "Contact Us";
// }
  
// // main-silder-swiper
//     if(jQuery('.main-silder-swiper-04').length > 0){
//         var swiper = new Swiper('.main-silder-swiper-04', {
//             speed: 1500,
//             parallax: true,
//             loop:true,
//             autoplay:true,
//             pagination: {
//                 el: '.swiper-pagination',
//                 clickable: true,
//             },
//         });
//     }


// $(document).ready(function() {
//   var owl = $('.landmark');
//   owl.owlCarousel({
//     margin: 20,
//     loop: true,
//     dots: true,
//     autoplay: true,
//     autoplayTimeout: 4500,
//     navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"], 
//     responsive: {
//       0: {
//           items: 1
//       },
//       480: {
//           items: 1
//       },
//       768: {
//           items:2,
//           nav: false,
//       },
//       992: {
//           items: 3,
//           nav: false,
//       },
//       1200: {
//           items: 3,
//           nav: false,
//       },
//       1440: {
//           items: 3,
//           nav: false,
//       }
//     }
//   })
// })

// if (jQuery('.main-silder-swiper-04').length > 0) {
//     var swiper = new Swiper('.main-silder-swiper-04', {
//         speed: 1500,
//         parallax: true,
//         loop: true,
//         autoplay: {
//             delay: 3000,
//         },
//         centeredSlides: true,
//         slidesPerView: 'auto',
//         spaceBetween: 30, // You can adjust spacing
//         pagination: {
//             el: '.swiper-pagination',
//             clickable: true,
//         },
//     });
// }





