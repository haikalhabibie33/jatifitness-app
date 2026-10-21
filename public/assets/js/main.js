(function (window, $) {
  "use strict";

  if (!$) {
    console.error("[main.js] jQuery NOT found. main.js stopped.");
    return;
  }

  function hasPlugin(pluginName) {
    return typeof $.fn !== "undefined" && typeof $.fn[pluginName] === "function";
  }

  function runSection(sectionName, callback) {
    try {
      callback();
    } catch (error) {
      console.error("[main.js][" + sectionName + "] error:", error);
    }
  }

  function debugPlugins() {
    console.log("[main.js] loaded");
    console.log("[debug] jQuery version:", $.fn.jquery);
    console.log("[debug] select count:", $("select").length);
    console.log("[debug] niceSelect exists:", hasPlugin("niceSelect"));
    console.log("[debug] slick exists:", hasPlugin("slick"));
    console.log("[debug] slicknav exists:", hasPlugin("slicknav"));
    console.log("[debug] magnificPopup exists:", hasPlugin("magnificPopup"));
    console.log("[debug] counterUp exists:", hasPlugin("counterUp"));
    console.log("[debug] datepicker exists:", hasPlugin("datepicker"));
    console.log("[debug] timepicker exists:", hasPlugin("timepicker"));
    console.log("[debug] snakeify exists:", hasPlugin("snakeify"));
    console.log("[debug] WOW exists:", typeof window.WOW !== "undefined");
  }

  $(function () {
    debugPlugins();

    /* =========================
       1. Preloader
    ========================= */
    runSection("preloader", function () {
      $(window).on("load", function () {
        $("#preloader-active").delay(450).fadeOut("slow");
        $("body").delay(450).css({
          overflow: "visible"
        });
      });
    });

    /* =========================
       2. Sticky Header + Scroll Up
    ========================= */
    runSection("sticky-scroll-up", function () {
      $(window).on("scroll", function () {
        var scroll = $(window).scrollTop();

        if (scroll < 400) {
          $(".header-sticky").removeClass("sticky-bar");
          $("#back-top").fadeOut(500);
        } else {
          $(".header-sticky").addClass("sticky-bar");
          $("#back-top").fadeIn(500);
        }
      });

      $("#back-top a").on("click", function () {
        $("body,html").animate(
          {
            scrollTop: 0
          },
          800
        );

        return false;
      });
    });

    /* =========================
       3. SlickNav Mobile Menu
    ========================= */
    runSection("slicknav", function () {
      var menu = $("ul#navigation");

      if (!menu.length) {
        console.warn("[slicknav] skipped: ul#navigation not found");
        return;
      }

      if (!hasPlugin("slicknav")) {
        console.error("[slicknav] plugin NOT found");
        return;
      }

      menu.slicknav({
        prependTo: ".mobile_menu",
        closedSymbol: "+",
        openedSymbol: "-"
      });

      console.log("[slicknav] initialized");
    });

    /* =========================
       4. Main Slider
    ========================= */
    runSection("main-slider", function () {
      var basicSlider = $(".slider-active");

      if (!basicSlider.length) {
        console.warn("[main-slider] skipped: .slider-active not found");
        return;
      }

      if (!hasPlugin("slick")) {
        console.error("[main-slider] slick plugin NOT found");
        return;
      }

      function doAnimations(elements) {
        var animationEndEvents =
          "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";

        elements.each(function () {
          var $this = $(this);
          var animationDelay = $this.data("delay");
          var animationType = "animated " + $this.data("animation");

          $this.css({
            "animation-delay": animationDelay,
            "-webkit-animation-delay": animationDelay
          });

          $this.addClass(animationType).one(animationEndEvents, function () {
            $this.removeClass(animationType);
          });
        });
      }

      basicSlider.on("init", function () {
        var firstAnimatingElements = $(".single-slider:first-child").find("[data-animation]");
        doAnimations(firstAnimatingElements);
      });

      basicSlider.on("beforeChange", function (e, slick, currentSlide, nextSlide) {
        var animatingElements = $(
          '.single-slider[data-slick-index="' + nextSlide + '"]'
        ).find("[data-animation]");

        doAnimations(animatingElements);
      });

      basicSlider.slick({
        autoplay: true,
        autoplaySpeed: 5000,
        dots: false,
        fade: true,
        arrows: false,
        prevArrow:
          '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
        nextArrow:
          '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true
            }
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false
            }
          }
        ]
      });

      console.log("[main-slider] initialized");
    });

    /* =========================
       5. Testimonial Slider
    ========================= */
    runSection("testimonial-slider", function () {
      var testimonial = $(".h1-testimonial-active");

      if (!testimonial.length) {
        console.warn("[testimonial] skipped: .h1-testimonial-active not found");
        return;
      }

      if (!hasPlugin("slick")) {
        console.error("[testimonial] slick plugin NOT found");
        return;
      }

      testimonial.slick({
        dots: false,
        infinite: true,
        speed: 1000,
        autoplay: true,
        loop: true,
        arrows: true,
        prevArrow:
          '<button type="button" class="slick-prev"><i class="ti-arrow-top-left"></i></button>',
        nextArrow:
          '<button type="button" class="slick-next"><i class="ti-arrow-top-right"></i></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true,
              dots: false,
              arrow: false
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false
            }
          }
        ]
      });

      console.log("[testimonial] initialized");
    });

    /* =========================
       6. Nice Select
    ========================= */
    runSection("nice-select", function () {
      var niceSelectElements = $("select").not("#select2");

      console.log("[niceSelect] target select count:", niceSelectElements.length);

      if (!niceSelectElements.length) {
        console.warn("[niceSelect] skipped: no select found");
        return;
      }

      if (!hasPlugin("niceSelect")) {
        console.error("[niceSelect] plugin NOT found");
        return;
      }

      niceSelectElements.niceSelect();

      console.log("[niceSelect] initialized");
      console.log("[niceSelect] generated .nice-select count:", $(".nice-select").length);
    });

    /* =========================
       7. Data Background
    ========================= */
    runSection("data-background", function () {
      $("[data-background]").each(function () {
        var background = $(this).attr("data-background");

        if (background) {
          $(this).css("background-image", "url(" + background + ")");
        }
      });

      console.log("[data-background] initialized");
    });

    /* =========================
       8. WOW Animation
    ========================= */
    runSection("wow", function () {
      if (typeof window.WOW === "undefined") {
        console.warn("[WOW] skipped: WOW not found");
        return;
      }

      new window.WOW().init();

      console.log("[WOW] initialized");
    });

    /* =========================
       9. Magnific Popup Image
    ========================= */
    runSection("magnific-popup-image", function () {
      var popUpImage = $(".single_gallery_part, .img-pop-up");

      if (!popUpImage.length) {
        console.warn("[magnificPopup:image] skipped: image popup target not found");
        return;
      }

      if (!hasPlugin("magnificPopup")) {
        console.error("[magnificPopup:image] plugin NOT found");
        return;
      }

      popUpImage.magnificPopup({
        type: "image",
        gallery: {
          enabled: true
        }
      });

      console.log("[magnificPopup:image] initialized");
    });

    /* =========================
       10. Magnific Popup Video
    ========================= */
    runSection("magnific-popup-video", function () {
      var popUpVideo = $(".popup-video");

      if (!popUpVideo.length) {
        console.warn("[magnificPopup:video] skipped: video popup target not found");
        return;
      }

      if (!hasPlugin("magnificPopup")) {
        console.error("[magnificPopup:video] plugin NOT found");
        return;
      }

      popUpVideo.magnificPopup({
        type: "iframe"
      });

      console.log("[magnificPopup:video] initialized");
    });

    /* =========================
       11. Counter Up
    ========================= */
    runSection("counter-up", function () {
      var counter = $(".counter");

      if (!counter.length) {
        console.warn("[counterUp] skipped: .counter not found");
        return;
      }

      if (!hasPlugin("counterUp")) {
        console.error("[counterUp] plugin NOT found");
        return;
      }

      counter.counterUp({
        delay: 10,
        time: 3000
      });

      console.log("[counterUp] initialized");
    });

    /* =========================
       12. Datepicker
    ========================= */
    runSection("datepicker", function () {
      var datepicker = $("#datepicker1");

      if (!datepicker.length) {
        console.warn("[datepicker] skipped: #datepicker1 not found");
        return;
      }

      if (!hasPlugin("datepicker")) {
        console.error("[datepicker] plugin NOT found");
        return;
      }

      datepicker.datepicker();

      console.log("[datepicker] initialized");
    });

    /* =========================
       13. Timepicker
    ========================= */
    runSection("timepicker", function () {
      var timepicker = $("#timepicker");

      if (!timepicker.length) {
        console.warn("[timepicker] skipped: #timepicker not found");
        return;
      }

      if (!hasPlugin("timepicker")) {
        console.error("[timepicker] plugin NOT found");
        return;
      }

      timepicker.timepicker();

      console.log("[timepicker] initialized");
    });

    /* =========================
       14. Snake Overlay
    ========================= */
    runSection("snakeify", function () {
      var snake = $(".snake");

      if (!snake.length) {
        console.warn("[snakeify] skipped: .snake not found");
        return;
      }

      if (!hasPlugin("snakeify")) {
        console.error("[snakeify] plugin NOT found");
        return;
      }

      snake.snakeify({
        speed: 200
      });

      console.log("[snakeify] initialized");
    });
  });
})(window, window.jQuery);