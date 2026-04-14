(function ($) {
  "use strict";

  var jws_dropdown_text = function ($scope, $) {
    $scope
      .find(".jws_dropdown_text")
      .eq(0)
      .each(function () {});
  };

  var jws_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_slider_element")
      .eq(0)
      .each(function () {
        $(this)
          .find(".jws_slider")
          .not(".slick-initialized")
          .slick({
            prevArrow:
              '<span class="jws-carousel-btn prev-item"><i class="jws-icon-caret-left-thin"></i></span>',
            nextArrow:
              '<span class="jws-carousel-btn next-item "><i class="jws-icon-caret-right-thin"></i></span>',
            swipeToSlide: true,
            fade: true,
            cssEase: "linear",
            appendDots: $(".slider-dots-box"),
            dotsClass: "slider-dots",
            rtl: rtl,
          })
          .on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $(".slider-dots-box button").html("");
          })
          .trigger("beforeChange")
          .on("afterChange", function (event, slick, currentSlide) {
            $(".slider-dots-box button").html("");
            $(
              ".slider-dots-box .slick-active button"
            ).html(`<svg class="progress-svg" width="13" height="13">
            		<g transform="translate(6.5,6.5)">
                  <circle class="circle-go" r="6" cx="0" cy="0"></circle>
            		</g>
                </svg>`);
          })
          .trigger("afterChange");
      });
  };

  var jws_product_group = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_product_group_element")
      .eq(0)
      .each(function () {
        var $this = $(this);
        $this
          .find(".active > .jws_product_slider")
          .not(".slick-initialized")
          .slick({
            swipeToSlide: true,
            dots: false,
            arrows: false,
            infinite: false,
            swipe: false,
            rtl: rtl,
            responsive: [
              {
                breakpoint: 991,
                settings: {
                  swipe: true,
                },
              },
              {
                breakpoint: 600,
                settings: {
                  swipe: true,
                },
              },
              {
                breakpoint: 480,
                settings: {
                  swipe: true,
                },
              },
            ],
          });

        $scope.find(".jws_product_maps_inner").each(function () {
          var mapper = $(this);
          mapper.find("[data-pin]").on("click", function (e) {
            e.preventDefault();
            var number = $(this).data("pin"),
              slider = $(this).parents(".jws_product_maps_item"),
              tab = slider.data("tab");
            slider.find("[data-pin]").removeClass("active");
            slider.find("[data-pin=" + number + "]").addClass("active");
            $this
              .find('[data-slider="' + tab + '"]')
              .slick("slickGoTo", parseInt(number));
          });
        });

        $(this)
          .find(".jws_product_maps_inner")
          .not(".slick-initialized")
          .slick({
            swipeToSlide: true,
            dots: false,
            arrows: false,
            infinite: false,
            swipe: false,
            rtl: rtl,
          })
          .on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $this.find("[data-index]").removeClass("active");
            $this.find("[data-index=" + nextSlide + "]").addClass("active");
          });

        $this.find("[data-index]").on("click", function (e) {
          e.preventDefault();
          var number = $(this).data("index");
          $("[data-pin]").removeClass("active");
          $("[data-pin='0']").addClass("active");
          var start_slider = $this
            .find(".jws_product_slider_wap_item:eq( " + number + " )")
            .find(".jws_product_slider");
          if (start_slider.hasClass("slick-initialized")) {
            start_slider.slick("unslick");
          }
          $this.find("[data-index]").removeClass("active");
          $this.find("[data-index=" + number + "]").addClass("active");
          $this.find(".jws_product_slider_wap_item").hide();
          $this
            .find(".jws_product_slider_wap_item:eq( " + number + " )")
            .fadeIn();
          start_slider.not(".slick-initialized").slick({
            swipeToSlide: true,
            dots: false,
            arrows: false,
            infinite: false,
            swipe: false,
            rtl: rtl,
            responsive: [
              {
                breakpoint: 991,
                settings: {
                  swipe: true,
                },
              },
              {
                breakpoint: 600,
                settings: {
                  swipe: true,
                },
              },
              {
                breakpoint: 480,
                settings: {
                  swipe: true,
                },
              },
            ],
          });
          $this
            .find(".jws_product_maps_inner")
            .slick("slickGoTo", parseInt(number));
        });
      });
  };

  var jws_carousel = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws-carousel")
      .eq(0)
      .each(function () {
        var $this = $(this);
        $(this)
          .find(".carousel")
          .not(".slick-initialized")
          .slick({
            prevArrow:
              '<span class="jws-carousel-btn prev-item"><i class="jws-icon-caret-left-thin"></i></span>',
            nextArrow:
              '<span class="jws-carousel-btn next-item "><i class="jws-icon-caret-right-thin"></i></span>',
            swipeToSlide: true,
            appendDots: $this.find(".slider-dots-box"),
            dotsClass: "slider-dots",
            rtl: rtl,
          })
          .on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $this.find(".slider-dots-box button").html("");
          })
          .trigger("beforeChange")
          .on("afterChange", function (event, slick, currentSlide) {
            $this.find(".slider-dots-box button").html("");
            $this.find(
              ".slider-dots-box .slick-active button"
            ).html(`<svg class="progress-svg" width="13" height="13">
            		<g transform="translate(6.5,6.5)">
                  <circle class="circle-go" r="6" cx="0" cy="0"></circle>
            		</g>
                </svg>`);
          })
          .trigger("afterChange");
      });
  };

  var jws_text_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".texts_slider")
      .eq(0)
      .each(function () {
        $(this).not(".slick-initialized").slick({
          infinite: true,
          centerMode: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          variableWidth: true,
          cssEase: "linear",
          autoplay: true,
          autoplaySpeed: 0,
          infinite: true,
          speed: 10000,
          arrows: false,
          dots: false,
          pauseOnHover: false,
          pauseOnFocus: false,
          rtl: rtl,
        });
      });
  };

  var product_tabs_filter = function ($scope, $) {
    $scope
      .find(".jws-wrap")
      .eq(0)
      .each(function () {
        var wrap = $(this);
        if (wrap.hasClass("metro")) {
          wrap.find(".products-tab").isotope({
            itemSelector: ".product-item",
            layoutMode: "masonry",
            transitionDuration: "0.3s",
            masonry: {
              // use outer width of grid-sizer for columnWidth
              columnWidth: ".grid-sizer",
            },
          });
        }
        wrap.find(".jws-ajax-load a.ajax-load").on("click", function (e) {
          e.preventDefault();
          var $this = $(this),
            intervalID;
          var key = $this.data("value");
          if ($this.hasClass("active")) {
            return;
          }
          clearInterval(intervalID);
          wrap.addClass("jws-animated-products");
          $this.parents(".jws-ajax-load").find("a").removeClass("active");
          $this.addClass("active");
          if ($this.hasClass("opened")) {
            wrap
              .find(".products-tab")
              .html(wrap.find(".products-tab").data(key));
            if (wrap.hasClass("jws-carousel")) {
              jws_carousel($scope, $);
            }
            var iter = 0;
            intervalID = setInterval(function () {
              wrap.find(".product-item").eq(iter).addClass("jws-animated");
              iter++;
            }, 100);
            return;
          }
          $this.addClass("opened");
          wrap.addClass("loading");
          if (!wrap.find(".loader").length) {
            wrap.append(
              '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
            );
          }
          var data = wrap.data("args");
          data.action = "jws_ajax_product_filter";
          if ($this.data("type") == "product_cat") {
            data.filter_categories = $this.data("value");
          }
          if ($this.data("type") == "asset_type") {
            data.asset_type = $this.data("value");
          }
          $.ajax({
            url: wrap.data("url"),
            data: data,
            type: "POST",
            dataType: "json",
          })
            .success(function (response) {
              wrap.removeClass("loading");
              let content = response.items;
              wrap.find(".products-tab").html(content);
              wrap.find(".products-tab").data(key, content);
              if (wrap.hasClass("jws-carousel")) {
                jws_carousel($scope, $);
              }
              var iter = 0;
              intervalID = setInterval(function () {
                wrap.find(".product-item").eq(iter).addClass("jws-animated");
                iter++;
              }, 100);
            })
            .error(function (ex) {
              console.log(ex);
            });
        });

        wrap
          .find(".jws-products-load-more")
          .off("click")
          .on("click", function (e) {
            e.preventDefault();
            var $this = $(this),
              data = wrap.data("args"),
              paged = wrap.data("paged");
            paged++;
            loadProducts2(data, paged, wrap, $this);
          });

        var loadProducts2 = function (data, paged, wrap, btn) {
          var intervalID,
            total = wrap.find(".product-item").length,
            iter = total;
          clearInterval(intervalID);
          data.action = "jws_ajax_product_filter";
          data.paged = paged;
          btn.addClass("loading");
          wrap.find(".product-item").addClass("jws-animated");
          wrap.addClass("jws-animated-products");

          btn.append(
            '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
          );
          $.ajax({
            url: wrap.data("url"),
            data: data,
            method: "POST",
            dataType: "json",
            success: function (response) {
              if (response.items) {
                wrap.find(".products-tab").append(response.items);
                intervalID = setInterval(function () {
                  wrap.find(".product-item").eq(iter).addClass("jws-animated");
                  iter++;
                }, 100);
                wrap.data("paged", paged);
              }

              if (response.status == "no-more-posts") {
                btn.hide();
              }
            },
            error: function (data) {
              console.log("ajax error");
              console.log(data);
            },
            complete: function () {
              btn.removeClass("loading");
              $(".loader").remove();
            },
          });
        };
      });
  };

  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * video popup
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var demo_filter = function ($scope, $) {
    $scope
      .find(".jws_demo_element")
      .eq(0)
      .each(function () {
        //Check to see if the window is top if not then display button
        $scope.find(".jws_demo_element .jws_demo_item").each(function () {
          var btn = $(this).find(".jws_image_content_inner");
          $(this)
            .find(".jws_image a")
            .on("scroll", function () {
              if ($(this).scrollTop() > 100) {
                btn.fadeOut("slow");
              } else {
                btn.fadeIn("slow");
              }
            });
          //Click event to scroll to top
          $(this)
            .find(".jws_column_content")
            .on("mouseleave", function () {
              $(this).find(".jws_image a").animate(
                {
                  scrollTop: 0,
                },
                800
              );
              return false;
            });
        });
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * video popup
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var video_popup = function ($scope, $) {
    $scope
      .find(".jws_video_popup")
      .eq(0)
      .each(function () {
        $(this).find(".jws_video_popup_inner").lightGallery();
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * testimonials_slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var testimonials_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_testimonials_slider_wrap")
      .eq(0)
      .each(function () {
        var asNavFor = "",
          asNavFor2 = "";
        if ($(this).hasClass("layout1")) {
          asNavFor = ".testimonials_slider_thumbnail";
          asNavFor2 = ".testimonials_slider";
        }

        $(this)
          .find(".testimonials_slider")
          .not(".slick-initialized")
          .slick({
            prevArrow: $(this).find(".prev-item"),
            nextArrow: $(this).find(".next-item"),
            swipeToSlide: true,
            asNavFor: asNavFor,
            rtl: rtl,
          });

        if (
          $(this).find(".testimonials_slider").hasClass("slider_layout_layout1")
        ) {
          $(this)
            .find(".testimonials_slider_thumbnail")
            .not(".slick-initialized")
            .slick({
              swipeToSlide: true,
              asNavFor: asNavFor2,
              focusOnSelect: true,
              arrows: false,
              rtl: rtl,
            });
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Blog Filter
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var blog_filter = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws-blog-element")
      .eq(0)
      .each(function () {
        var $this = $(this);
        var $container = $this.find(".blog_content");
        // set vars
        function postslider() {
          $container.not(".slick-initialized").slick({
            prevArrow: $this.find(".nav_left"),
            nextArrow: $this.find(".nav_right"),
            swipeToSlide: true,
            rtl: rtl,
          });
        }
        if ($container.hasClass("jws_blog_slider")) {
          postslider();
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * team_slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var team_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_team_element")
      .eq(0)
      .each(function () {
        $(this)
          .find(".jws_team_slider")
          .not(".slick-initialized")
          .slick({
            prevArrow: $(this).find(".nav_left"),
            nextArrow: $(this).find(".nav_right"),
            swipeToSlide: true,
            rtl: rtl,
          });
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * services_slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var services_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws-services-element")
      .eq(0)
      .each(function () {
        $(this).find(".jws-services-slider").not(".slick-initialized").slick({
          swipeToSlide: true,
          rtl: rtl,
        });
      });
  };

  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * gallery Filter
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_gallery = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_gallery_element")
      .eq(0)
      .each(function () {
        var $this = $(this),
          $container = $this.find(".jws_gallery"),
          $filter = $this.find(".gallery_tabs");
        $(".jws_gallery").lightGallery({
          thumbnail: true,
          selector: ".jws_gallery_item .jws-popup-global",
        });
        //init flickity
        var pageDots = false;
        if ($container.hasClass("has-dots")) {
          pageDots = true;
        }
        if ($container.hasClass("slider")) {
          $container.not(".slick-initialized").slick({
            prevArrow: $this.find(".nav_left"),
            nextArrow: $this.find(".nav_right"),
            swipeToSlide: true,
            rtl: rtl,
          });
        }
        if (!$container.hasClass("slider")) {
          $(window).on("load", function () {
            if ($container.hasClass("metro")) {
              $container.isotope({
                itemSelector: ".jws_gallery_item",
                layoutMode: "masonry",
                transitionDuration: "0.3s",
                masonry: {
                  // use outer width of grid-sizer for columnWidth
                  columnWidth: ".grid-sizer",
                },
              });
            } else {
              $container.isotope({
                itemSelector: ".jws_gallery_item",
                layoutMode: "masonry",
                transitionDuration: "0.3s",
              });
            }
          });
        }
        $filter.find("a").on("click touchstart", function (e) {
          var $t = $(this),
            selector = $t.data("filter");
          // Don't proceed if already selected
          if ($t.hasClass("filter-active")) return false;
          $filter.find("a").removeClass("filter-active");
          $t.addClass("filter-active");
          filterAnimateStart(selector);
          e.stopPropagation();
          e.preventDefault();
        });

        function filterAnimateStart(filterValue) {
          var anime_ = anime;
          anime_.remove(".jws_gallery_item");
          anime_({
            targets: ".jws_gallery_item",
            translateX: -30,
            opacity: 0,
            easing: "easeInOutQuint",
            duration: 500,
            delay: function delay(el, i) {
              return i * 60;
            },
            begin: function begin(anime_) {
              $(".jws_gallery").data("lightGallery").destroy(true);
              $(anime_.animatables).each(function (i, el) {
                var $element = $(el.target);
                $element.css({
                  transition: "none",
                });
              });
            },
            complete: function complete() {
              if (filterValue !== "*") {
                $container.slick("slickUnfilter");
                $container.find(".jws_gallery_item").each(function () {
                  $(this).removeClass("slide-shown");
                });
                $(filterValue).addClass("slide-shown");
                $container.slick("slickFilter", ".slide-shown");
              } else {
                $container.find(".jws_gallery_item").each(function () {
                  $(this).removeClass("slide-shown");
                });
                $container.slick("slickUnfilter");
              }
              filterItems(filterValue);
            },
          });
        }

        function filterItems(filterValue) {
          //use data-filter attribute & class for filtering
          var slider = $container;
          var slide = slider.find(".jws_gallery_item");
          if (filterValue == "*") {
            // if all show all
            slide.removeClass("hidden");
            slide.addClass("flickity");
            $(".jws_gallery").lightGallery({
              thumbnail: true,
              selector: ".jws_gallery_item .jws-popup-global",
            });
          } else {
            //set active slide
            var active = $(filterValue).removeClass("hidden");
            // show only slide with the same class as the button "attr('data-filter')"
            slide.addClass("flickity");
            slide.not(active).removeClass("flickity");
            slide.not(active).addClass("hidden");
            // destroy slider so we can rebuild with new filters
            $(".jws_gallery").lightGallery({
              thumbnail: true,
              selector: filterValue.replace("*", "") + " .jws-popup-global",
            });
          }
          filterAnimateComplete();
        }

        function filterAnimateComplete() {
          var anime_ = anime;
          anime_.remove(".jws_gallery_item");
          anime_({
            targets: ".jws_gallery_item",
            translateX: 0,
            opacity: 1,
            easing: "easeOutQuint",
            delay: function delay(el, i) {
              return i * 60;
            },
            complete: function complete(anime_) {
              $(anime_.animatables).each(function (i, el) {
                var element = $(el.target);
                element.css({
                  transition: "",
                  transform: "",
                  opacity: "",
                });
              });
            },
          });
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * banner slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_banner = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws-banner-element")
      .eq(0)
      .each(function () {
        var $this = $(this),
          $container = $this.find(".jws-banner");
        if ($container.hasClass("layout6")) {
          const $listItems = $container.find(".jws-banner-item");
          const $photos = $container.find(".jws-banner-image");

          $photos.first().addClass("active");
          $listItems.first().addClass("active");
          // Must have equal number of listItems and Imgs for this to work
          $listItems.each(function (i) {
            $(this).on("mouseenter", () => {
              $photos.removeClass("active"); // Remove 'active' class from all photos
              $photos.eq(i).addClass("active");
              $listItems.removeClass("active");
              $(this).addClass("active");
            });

            $(this).on("mouseleave", () => {
              $photos.removeClass("active"); // Remove 'active' class from all photos
              $photos.first().addClass("active"); // Add 'active' class back to the first photo
              $(this).removeClass("active");
              $listItems.first().addClass("active");
            });
          });
        }
        if ($container.hasClass("slider")) {
          $container
            .not(".slick-initialized")
            .slick({
              prevArrow: $this.find(".prev-item"),
              nextArrow: $this.find(".next-item"),
              swipeToSlide: true,
              appendDots: $this.find(".slider-dots-box"),
              cssEase: "linear",
              dotsClass: "slider-dots",
              rtl: rtl,
            })
            .on(
              "beforeChange",
              function (event, slick, currentSlide, nextSlide) {
                $this.find(".slider-dots-box button").html("");
              }
            )
            .trigger("beforeChange")
            .on("afterChange", function (event, slick, currentSlide) {
              $this.find(".slider-dots-box button").html("");
              $this.find(
                ".slider-dots-box .slick-active button"
              ).html(`<svg class="progress-svg" width="13" height="13">
            		<g transform="translate(6.5,6.5)">
                  <circle class="circle-go" r="6" cx="0" cy="0"></circle>
            		</g>
                </svg>`);
            })
            .trigger("afterChange");
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * shopvideo slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_shop_video = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws-products-slider-element")
      .eq(0)
      .each(function () {
        var $this = $(this),
          $container = $this.find(".jws-products-slider");

        if ($container.hasClass("slider")) {
          $container
            .not(".slick-initialized")
            .slick({
              prevArrow: $this.find(".prev-item"),
              nextArrow: $this.find(".next-item"),
              swipeToSlide: true,
              appendDots: $this.find(".custom_dots"),
              cssEase: "linear",
              dotsClass: "slider-dots",
              rtl: rtl,
            })
            .on(
              "beforeChange",
              function (event, slick, currentSlide, nextSlide) {
                $this.find(".slider-dots-box button").html("");
              }
            )
            .trigger("beforeChange")
            .on("afterChange", function (event, slick, currentSlide) {
              $this.find(".slider-dots-box button").html("");
              $this.find(
                ".slider-dots-box .slick-active button"
              ).html(`<svg class="progress-svg" width="13" height="13">
            		<g transform="translate(6.5,6.5)">
                  <circle class="circle-go" r="6" cx="0" cy="0"></circle>
            		</g>
                </svg>`);
            })
            .trigger("afterChange");
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Tabs
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_tabs = function ($scope, $) {
    $scope
      .find(".jws_tab_wrap")
      .eq(0)
      .each(function () {
        var $this = $(this);
        /** Line magic tabs filter **/
        if ($this.find(".tab_nav").length) {
          $this.find(".tab_nav").append("<li id='magic_line'></li>");
          var $magicLine = $this.find("#magic_line");
          $magicLine
            .width($this.find(".current").width())
            .height($this.find(".current").height())
            .css("left", $this.find(".current a").position().left)
            .data("origLeft", $magicLine.position().left)
            .data("origWidth", $magicLine.width())
            .data("origHeight", $magicLine.height());
          if ($this.find(".tab_nav_container").hasClass("layout_layout2")) {
            $magicLine
              .css(
                "top",
                $this.find(".current a").position().top +
                  $this.find(".current").height() -
                  $magicLine.height()
              )
              .data("origBottom", $magicLine.position().top);
          } else {
            $magicLine
              .css("top", $this.find(".current a").position().top)
              .data("origTop", $magicLine.position().top);
          }
          $(window).on("resize", function () {
            $magicLine
              .width($this.find(".current").width())
              .height($this.find(".current").height())
              .css("left", $this.find(".current a").position().left)
              .data("origLeft", $magicLine.position().left)
              .data("origWidth", $magicLine.width())
              .data("origHeight", $magicLine.height());
            if ($this.find(".tab_nav_container").hasClass("layout_layout2")) {
              $magicLine
                .css(
                  "top",
                  $this.find(".current a").position().top +
                    $this.find(".current").height() -
                    $magicLine.height()
                )
                .data(
                  "origBottom",
                  $magicLine.position().top +
                    $this.find(".current").height() -
                    $magicLine.height()
                );
            } else {
              $magicLine
                .css("top", $this.find(".current a").position().top)
                .data("origTop", $magicLine.position().top);
            }
          });
          $this.find(".tab_nav li a").on("click", function () {
            $(document).trigger("resize");
            $magicLine
              .data("origLeft", $(this).position().left)
              .data("origWidth", $(this).parent().width())
              .data("origHeight", $(this).parent().height());
            if ($this.find(".tab_nav_container").hasClass("layout_layout2")) {
              $magicLine.data(
                "origBottom",
                $(this).position().top +
                  $this.find(".current").height() -
                  $magicLine.height()
              );
            } else {
              $magicLine.data("origTop", $(this).position().top);
            }
            return false;
          });
          /*Magicline hover animation*/
          $this
            .find(".tab_nav li")
            .find("a")
            .on("click", function () {
              if ($this.find(".tab_nav_container").hasClass("layout_layout2")) {
                $magicLine.css({
                  left: $magicLine.data("origLeft"),
                  top: $magicLine.data("origBottom"),
                  width: $magicLine.data("origWidth"),
                  height: $magicLine.data("origHeight"),
                });
              } else {
                $magicLine.css({
                  left: $magicLine.data("origLeft"),
                  top: $magicLine.data("origTop"),
                  width: $magicLine.data("origWidth"),
                  height: $magicLine.data("origHeight"),
                });
              }
            });
        }
        $this.find(".tab_nav li a").on("click", function (e) {
          e.preventDefault();
          var tab_id = $(this).attr("data-tab");
          $this.find(".tab_nav li a").parent().removeClass("current");
          $this.find(".jws_tab_item").removeClass("current");
          $(this).parent().addClass("current");
          $this.find("#" + tab_id).addClass("current");
        });
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Process Tabs
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_process_tabs = function ($scope, $) {
    $scope
      .find(".jws_progress.layout_tab")
      .eq(0)
      .each(function () {
        $(".process_nav .progress_item a").on("click", function (e) {
          e.preventDefault();
          var tab_id = $(this).attr("data-tab");
          $(".process_nav .progress_item a").parent().removeClass("current");
          $(".process_content .progress_item").removeClass("current");
          $(this).parent().addClass("current");
          $("#" + tab_id).addClass("current");
        });
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Process Hover
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_process_hover = function ($scope, $) {
    $scope
      .find(".jws_progress")
      .eq(0)
      .each(function () {
        var $this = $(this);
        if (
          $this.hasClass("layout_list_hover") ||
          $this.hasClass("layout_grid_animation")
        ) {
          $this.find(".progress_item").hover(function () {
            $(".progress_item").removeClass("active");
            $(this).addClass("active");
          });
        }
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Process Slider
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var jws_process_slider = function ($scope, $) {
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    $scope
      .find(".jws_progress.layout_slider")
      .eq(0)
      .each(function () {
        var $this = $(this),
          beforeslideNumber,
          beforetotalSlides,
          nav = $this.find(".slider-nav");
        $this
          .find(".process_slider")
          .not(".slick-initialized")
          .slick({
            slide: ".progress_item",
            arrows: true,
            dots: true,
            prevArrow: '<span class="slick-prev lnr lnr-chevron-left"></span>',
            nextArrow: '<span class="slick-next lnr lnr-chevron-right"></span>',
            appendArrows: nav,
            appendDots: nav,
            dotsClass: "custom_paging",
            swipeToSlide: true,
            rtl: rtl,
            customPaging: function (slider, i) {
              var slideNumber = i + 1,
                totalSlides = slider.slideCount;
              if (slideNumber < 10) {
                beforeslideNumber = "0" + slideNumber;
              } else {
                beforeslideNumber = slideNumber;
              }
              if (totalSlides < 10) {
                beforetotalSlides = "0" + totalSlides;
              } else {
                beforetotalSlides = totalSlides;
              }
              return (
                '<a class="custom-dot" role="button"><span class="string">' +
                beforeslideNumber +
                '</span>/<span class="total">' +
                beforetotalSlides +
                "</span></a>"
              );
            },
          });
      });
  };
  var blogLoadMore = function ($scope, $) {
    $scope
      .find(".jws-blog-element")
      .eq(0)
      .each(function () {
        loadmore_btn($(this));
      });
  };
  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Load more button for blog
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */

  var loadmore_btn = function ($scope) {
    var $element = $scope.find("[data-ajaxify=true]");
    var options = $element.data("ajaxify-options");
    if ($element.length < 1) return false;
    var wap = options.wrapper;
    $(document.body).on("click", ".jws-load-more", function (e) {
      e.preventDefault();
      var $this = $(this);
      $(this).append(
        '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
      );
      $(this).addClass("loading");
      var url = $this.attr("href");

      if ("?" == url.slice(-1)) {
        url = url.slice(0, -1);
      }

      url = url.replace(/%2C/g, ",");

      $.get(
        url,
        function (res) {
          var $newItemsWrapper = $(res).find(options.wrapper);
          var $newItems = $newItemsWrapper.find(options.items);

          $newItems.imagesLoaded(function () {
            $(wap).append($newItems);
            if (!$(wap).hasClass("jws_blog_slider")) {
              $(wap).isotope("appended", $newItems);
            } // Calling function for the new items

            $this.find(".loader").remove();
          });

          $this
            .parents(".jws_pagination")
            .html($(res).find(wap).next(".jws_pagination").html());
          console.log($(res).find(".jws_pagination").html());
        },
        "html"
      );
    });
  };

  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Search
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var search = function ($scope, $) {
    if ("undefined" == typeof $scope) return;
    $scope
      .find(".jws_search")
      .eq(0)
      .each(function () {
        var s = $(this);
        var openClass = "open",
          button = s.find("> button");
        s.find(button).on("click", function (e) {
          e.preventDefault();
          if (!$(".form_content_popup").hasClass(openClass)) {
            $(".form_content_popup").addClass(openClass);
            setTimeout(function () {
              $(".form_content_popup input.s").focus();
            }, 100);
            return false;
          } else {
            $(".form_content_popup").removeClass(openClass);
          }
        });
        $(".close-form ").on("click", function (e) {
          $(".form_content_popup").removeClass(openClass);
        });
        s.find(".form_content_popup").appendTo(document.body);
        $(".form_content_popup").each(function () {
          if ($(".form_content_popup").length > 1) {
            $(this).remove();
          }
        });
      });
  };

  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Google Map
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */
  var WidgetjwsGoogleMapHandler = function ($scope) {
    if ("undefined" == typeof $scope) return;
    var selector = $scope.find(".jws-google-map").eq(0),
      locations = selector.data("locations"),
      map_style =
        selector.data("custom-style") != ""
          ? selector.data("custom-style")
          : "",
      predefined_style =
        selector.data("predefined-style") != ""
          ? selector.data("predefined-style")
          : "",
      info_window_size =
        selector.data("max-width") != "" ? selector.data("max-width") : "",
      animate = selector.data("animate"),
      auto_center = selector.data("auto-center"),
      maker_offset = selector.data("offset"),
      map_options = selector.data("map_options"),
      i = "",
      bounds = new google.maps.LatLngBounds(),
      marker_cluster = [],
      className = "map_pin_jws";
    var animation;
    if ("drop" == animate) {
      animation = google.maps.Animation.DROP;
    } else if ("bounce" == animate) {
      animation = google.maps.Animation.BOUNCE;
    }

    function _typeof(obj) {
      var _typeof;
      if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
        _typeof = function _typeof(obj) {
          return typeof obj;
        };
      } else {
        _typeof = function _typeof(obj) {
          return obj &&
            typeof Symbol === "function" &&
            obj.constructor === Symbol &&
            obj !== Symbol.prototype
            ? "symbol"
            : typeof obj;
        };
      }
      return _typeof(obj);
    }

    function CustomMarker(latlng, map, className) {
      this.latlng_ = latlng;
      this.className = className; // Once the LatLng and text are set, add the overlay to the map.  This will
      // trigger a call to panes_changed which should in turn call draw.
      this.setMap(map);
    }
    if (
      (typeof google === "undefined" ? "undefined" : _typeof(google)) !==
        _typeof(undefined) &&
      _typeof(google.maps) !== _typeof(undefined)
    ) {
      CustomMarker.prototype = new google.maps.OverlayView();
      CustomMarker.prototype.draw = function () {
        var me = this; // Check if the div has been created.
        var div = this.div_,
          divChild,
          divChild2;
        if (!div) {
          // Create a overlay text DIV
          div = this.div_ = document.createElement("DIV");
          div.className = this.className;
          divChild = document.createElement("div");
          div.appendChild(divChild);
          divChild2 = document.createElement("div");
          div.appendChild(divChild2);
          google.maps.event.addDomListener(div, "click", function () {
            google.maps.event.trigger(me, "click");
          }); // Then add the overlay to the DOM
          var panes = this.getPanes();
          panes.overlayImage.appendChild(div);
        } // Position the overlay
        var point = this.getProjection().fromLatLngToDivPixel(this.latlng_);
        if (point) {
          div.style.left = point.x + "px";
          div.style.top = point.y + "px";
        }
      };
      CustomMarker.prototype.remove = function () {
        // Check if the overlay was on the map and needs to be removed.
        if (this.div_) {
          this.div_.parentNode.removeChild(this.div_);
          this.div_ = null;
        }
      };
      CustomMarker.prototype.getPosition = function () {
        return this.latlng_;
      };
    }
    var skins = {
      silver:
        '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]',
      retro:
        '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]',
      dark: '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]',
      night:
        '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]',
      aubergine:
        '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]',
      magnesium:
        '[{"featureType":"all","stylers":[{"saturation":0},{"hue":"#e7ecf0"}]},{"featureType":"road","stylers":[{"saturation":-70}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"visibility":"simplified"},{"saturation":-60}]}]',
      classic_blue:
        '[{"featureType":"all","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#FFBB00"},{"saturation":43.400000000000006},{"lightness":37.599999999999994},{"gamma":1}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"saturation":"-40"},{"lightness":"36"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"saturation":"-77"},{"lightness":"28"}]},{"featureType":"landscape.natural","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#00FF6A"},{"saturation":-1.0989010989011234},{"lightness":11.200000000000017},{"gamma":1}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"saturation":"-24"},{"lightness":"61"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"hue":"#FFC200"},{"saturation":-61.8},{"lightness":45.599999999999994},{"gamma":1}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway.controlled_access","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":51.19999999999999},{"gamma":1}]},{"featureType":"road.local","elementType":"all","stylers":[{"hue":"#ff0300"},{"saturation":-100},{"lightness":52},{"gamma":1}]},{"featureType":"road.local","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#0078FF"},{"saturation":-13.200000000000003},{"lightness":2.4000000000000057},{"gamma":1}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]}]',
      aqua: '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]',
      earth:
        '[{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]',
    };
    if ("undefined" != typeof skins[predefined_style]) {
      map_style = JSON.parse(skins[predefined_style]);
    }
    (function initMap() {
      var latlng = new google.maps.LatLng(locations[0][0], locations[0][1]);
      map_options.center = latlng;
      map_options.styles = map_style;
      if (false == map_options.gestureHandling) {
        map_options.gestureHandling = "none";
      }
      var map = new google.maps.Map(
        $scope.find(".jws-google-map")[0],
        map_options
      );
      var infowindow = new google.maps.InfoWindow();
      var marker;
      for (i = 0; i < locations.length; i++) {
        var title = locations[i][3];
        var description = locations[i][4];
        var images_info = locations[i][5];
        var icon_size = parseInt(locations[i][8]);
        var icon_type = locations[i][6];
        var icon = "";
        var icon_url = locations[i][7];
        var enable_iw = locations[i][2];
        var click_open = locations[i][9];
        var lat = locations[i][0];
        var lng = locations[i][1];
        if ("undefined" === typeof locations[i]) {
          return;
        }
        if ("" != lat.length && "" != lng.length) {
          if ("custom" == icon_type) {
            icon = {
              url: icon_url,
            };
            if (!isNaN(icon_size)) {
              icon.scaledSize = new google.maps.Size(icon_size, icon_size);
              icon.origin = new google.maps.Point(0, 0);
              icon.anchor = new google.maps.Point(icon_size / 2, icon_size);
            }
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat, lng),
              map: map,
              title: title,
              icon: icon,
              animation: animation,
            });
          } else if ("html" == icon_type) {
            marker = new CustomMarker(
              new google.maps.LatLng(lat, lng),
              map,
              className
            );
          } else {
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat, lng),
              map: map,
              title: title,
              icon: icon,
              animation: animation,
            });
          }
          if ("undefined" !== typeof maker_offset) {
            map.panBy(0, maker_offset);
          }
          if (locations.length > 1) {
            // Extend the bounds to include each marker's position
            bounds.extend(marker.position);
          }
          marker_cluster[i] = marker;
          if (enable_iw && "iw_open" == click_open) {
            var has_image = "";
            if (images_info != "") {
              has_image = " has-image";
            }
            var content_string =
              '<div class="jws-infowindow-content' +
              has_image +
              '"><div class="info-left"><div class="jws-infowindow-title">' +
              title +
              "</div>";
            if ("" != description.length) {
              content_string +=
                '<div class="jws-infowindow-description">' +
                description +
                "</div></div>";
            }
            if (images_info != "") {
              content_string +=
                '<div class="info-right"><img src="' + images_info + '"></div>';
            }
            content_string += "</div>";
            if ("" != info_window_size) {
              var width_val = parseInt(info_window_size);
              infowindow = new google.maps.InfoWindow({
                content: content_string,
                maxWidth: width_val,
              });
            } else {
              infowindow = new google.maps.InfoWindow({
                content: content_string,
              });
            }
            infowindow.open(map, marker);
          }
          // Adding close event for info window
          google.maps.event.addListener(
            map,
            "click",
            (function (infowindow) {
              return function () {
                infowindow.close();
              };
            })(infowindow)
          );
          if (enable_iw && "" != locations[i][3]) {
            google.maps.event.addListener(
              marker,
              "click",
              (function (marker, i) {
                return function () {
                  var infowindow = new google.maps.InfoWindow();
                  var content_string =
                    '<div class="jws-infowindow-content"><div class="jws-infowindow-title">' +
                    locations[i][3] +
                    "</div>";
                  if ("" != locations[i][4].length) {
                    content_string +=
                      '<div class="jws-infowindow-description">' +
                      locations[i][4] +
                      "</div>";
                  }
                  content_string += "</div>";
                  infowindow.setContent(content_string);
                  if ("" != info_window_size) {
                    var width_val = parseInt(info_window_size);
                    var InfoWindowOptions = {
                      maxWidth: width_val,
                    };
                    infowindow.setOptions({
                      options: InfoWindowOptions,
                    });
                  }
                  infowindow.open(map, marker);
                };
              })(marker, i)
            );
          }
        }
      }
      if (locations.length > 1) {
        if ("center" == auto_center) {
          // Now fit the map to the newly inclusive bounds.
          map.fitBounds(bounds);
        }
        // Restore the zoom level after the map is done scaling.
        var listener = google.maps.event.addListener(map, "idle", function () {
          map.setZoom(map_options.zoom);
          google.maps.event.removeListener(listener);
        });
      }
    })();
  };
  /**
   * Table handler Function.
   *
   */
  var jws_table = function ($scope, $) {
    if ("undefined" == typeof $scope) {
      return;
    }
    // Define variables.
    var node_id = $scope.data("id");
    var jws_table = $scope.find(".jws-table");
    var jws_table_id = $scope.find("#jws-table-id-" + node_id);
    var searchable = false;
    var showentries = false;
    var sortable = false;
    if (0 == jws_table_id.length) return;
    //Search entries
    var search_entry = $(
      ".elementor-element-" + node_id + " #" + jws_table_id[0].id
    ).data("searchable");
    if ("yes" == search_entry) {
      searchable = true;
    }
    //Show entries select
    var show_entry = $(
      ".elementor-element-" + node_id + " #" + jws_table_id[0].id
    ).data("show-entry");
    if ("yes" == show_entry) {
      showentries = true;
    }
    //Sort entries
    var sort_table = $(
      ".elementor-element-" + node_id + " #" + jws_table_id[0].id
    ).data("sort-table");
    if ("yes" == sort_table) {
      $(
        ".elementor-element-" + node_id + " #" + jws_table_id[0].id + " th"
      ).css({
        cursor: "pointer",
      });
      sortable = true;
    }
    var search_string = jws_script.search_str;
    var length_string = jws_script.table_length_string;
    if (searchable || showentries || sortable) {
      $("#" + jws_table_id[0].id).DataTable({
        paging: showentries,
        searching: searchable,
        ordering: sortable,
        info: false,
        oLanguage: {
          sSearch: search_string,
          sLengthMenu: length_string,
        },
      });
      var div_entries = $scope.find(".dataTables_length");
      div_entries.addClass("jws-tbl-entry-wrapper jws-table-info");
      var div_search = $scope.find(".dataTables_filter");
      div_search.addClass("jws-tbl-search-wrapper jws-table-info");
      $scope
        .find(".jws-table-info")
        .wrapAll('<div class="jws-advance-heading"></div>');
    }

    function coloumn_rules() {
      if ($(window).width() > 767) {
        $(jws_table).addClass("jws-column-rules");
        $(jws_table).removeClass("jws-no-column-rules");
      } else {
        $(jws_table).removeClass("jws-column-rules");
        $(jws_table).addClass("jws-no-column-rules");
      }
    }
    // Listen for events.
    window.addEventListener("load", coloumn_rules);
    window.addEventListener("resize", coloumn_rules);
  };
  /**
   * Menu Style.
   *
   */
  var jws_menu_style = function ($scope, $) {
    if ("undefined" == typeof $scope) {
      return;
    }
    $scope
      .find(".jws_main_menu")
      .eq(0)
      .each(function () {
        var $this = $(this);
        $(this)
          .find(".elementor-icon-list-item.active")
          .parents(".nav > li")
          .addClass("current-menu-item");
        if (
          $this
            .closest(".elementor-widget-jws_menu_nav")
            .hasClass("elementor-before-menu-skin-animation-line")
        ) {
          var main = $this.find(".jws_main_menu_inner"),
            curent_item = main.find(
              "> ul > li.current-menu-item , > ul > li.current-menu-ancestor"
            ),
            curent_item_sub = main.find(
              "ul li.current-menu-item , .elementor-icon-list-item.active"
            );
          if (main.find("> ul > li.current-menu-item").length == 0) {
            if (curent_item_sub.length > 0) {
              curent_item = curent_item_sub.parents(".nav > li");
            } else {
              curent_item = main.find("> ul > li:first-child");
            }
          }
        }
        /** Menu toggle **/
        $this.find(".click-show-menu-v").on("click", function () {
          $this.find(".menu-toggle").toggleClass("open");
        });
      });
    //mega menu
    var mainMenu = $(".elementor_jws_menu_layout_menu_horizontal").find(".nav");
    var mega_item = mainMenu.find(
      " > li.menu-item-design-mega_menu_full_width"
    );
    var menu_item = mainMenu.find(" > li.menu-item");
    if (mega_item.length > 0) {
      $(".jws_header").addClass("has-mega-full");
    }

    if ($(".hover-background-sticky").length > 0) {
      $(".jws_header").addClass("has-mega-full");

      menu_item.mouseenter(function () {
        $(".jws_header.has-mega-full").addClass("mega-has-hover");
      });
      menu_item.mouseleave(function () {
        $(".jws_header.has-mega-full").removeClass("mega-has-hover");
      });
    }
    mega_item.mouseenter(function () {
      $(".jws_header.has-mega-full").addClass("mega-has-hover");
    });

    mega_item.mouseleave(function () {
      $(".jws_header.has-mega-full").removeClass("mega-has-hover");
    });
  };

  var tooltip = function ($scope, $) {
    $scope
      .find(".jws-tooltip-list")
      .eq(0)
      .each(function () {
        $(this)
          .find("button")
          .on("click", function () {
            var item = $(this).parents("li");
            item.toggleClass("active").siblings().removeClass("active");
          });
      });
  };
  var instagram = function ($scope, $) {
    $scope
      .find(".jws-instagram")
      .eq(0)
      .each(function () {
        var $this = $(this);
        var height_start = $this.find(".jws-instagram-item.col-xl-4").height();
        $this.find(".instagram-wap").css("height", height_start);
        if ($(this).hasClass("metro")) {
          setTimeout(function () {
            $this.find(".loader").remove();
            $this
              .find(".instagram-wap")
              .removeClass("loading")
              .isotope({
                itemSelector: ".jws-instagram-item",
                layoutMode: "masonry",
                transitionDuration: "0.3s",
                masonry: {
                  // use outer width of grid-sizer for columnWidth
                  columnWidth: ".grid-sizer",
                },
              });
          }, 2000);
        }
      });
  };

  var initSection = function ($obj) {
    var $container = $obj.children(".elementor-container"),
      dot_class = $container.find(".slider-dots-box"),
      $events,
      current_side;
    var rtl = false;
    if ($("body").hasClass("rtl")) {
      rtl = true;
    }
    if ($container.hasClass("jws_section_slider")) {
      var item_length = $container.find(".elementor-top-column").length - 1;
      let blocked = false;
      let blockTimeout = null;
      let prevDeltaY = 0;
      $container.eq(0).each(function () {
        var $this = $(this);
        var verticalSwiping = false;
        var window_offset;
        if ($container.hasClass("slick_wheel")) {
          slider_wheel();
          $(window).on("scroll", function () {
            window_offset = $container.offset().top - $(window).scrollTop();
            if (window_offset == 0) {
              $this.css("pointer-events", "auto");
            }
          });

          verticalSwiping = true;
        }

        var data_slick = $container.data("slick");

        $this
          .not(".slick-initialized")
          .slick({
            prevArrow: $(this).find(".nav_left"),
            nextArrow: $(this).find(".nav_right"),
            swipeToSlide: true,
            fade: false,
            slide: ".elementor-element",
            appendDots: dot_class,
            dotsClass: "slider-dots",
            verticalSwiping: verticalSwiping,
            rtl: rtl,
          })
          .on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $(".slider-dots-box button").html("");
            current_side = $this.find("[data-slick-index='" + nextSlide + "']");
            $events = "no";
            section_change(current_side, $events);
          })
          .on("afterChange", function (event, slick, currentSlide) {
            current_side = $this.find(
              "[data-slick-index='" + currentSlide + "']"
            );
            $events = "next";
            section_change(current_side, $events);
            if (
              item_length == currentSlide &&
              $container.hasClass("slick_wheel")
            ) {
              if (!data_slick.infinite) {
                $this.css("pointer-events", "none");
              }
            }

            $(".slider-dots-box button").html("");
            $(
              ".slider-dots-box .slick-active button"
            ).html(`<svg class="progress-svg" width="13" height="13">
                		<g transform="translate(6.5,6.5)">
                      <circle class="circle-go" r="6" cx="0" cy="0"></circle>
                		</g>
                    </svg>`);
          })
          .trigger("afterChange");

        function slider_wheel() {
          $this.on("mousewheel DOMMouseScroll wheel", function (e) {
            let deltaY = e.originalEvent.deltaY;
            e.preventDefault();
            e.stopPropagation();

            clearTimeout(blockTimeout);
            blockTimeout = setTimeout(function () {
              blocked = false;
            }, 50);

            if (
              (deltaY > 0 && deltaY > prevDeltaY) ||
              (deltaY < 0 && deltaY < prevDeltaY) ||
              !blocked
            ) {
              blocked = true;
              prevDeltaY = deltaY;

              if (deltaY > 0) {
                $this.slick("slickNext");
              } else {
                $this.slick("slickPrev");
              }
            }
          });
        }
      });
      function section_change($slick, $events) {
        $slick.find('[data-element_type="widget"]').each(function () {
          var data = $(this).data("settings");
          var $this = $(this);
          if (data !== undefined) {
            $this.addClass("has_animated");
          }
          if (data !== undefined && data._animation_delay !== undefined) {
            if ($events == "next") {
              setTimeout(function () {
                $this.addClass("animated");
                $this.addClass(data._animation);
              }, data._animation_delay);
            } else {
              $this.removeClass("animated");
              $this.removeClass(data._animation);
            }
          } else if (data !== undefined) {
            if ($events == "next") {
              setTimeout(function () {
                $this.addClass("animated");
                $this.addClass(data._animation);
              }, 0);
            } else {
              $this.removeClass("animated");
              $this.removeClass(data._animation);
            }
          }
        });
      }
    }
  };

  var countdown = function ($scope, $) {
    $scope
      .find(".countdown-container")
      .eq(0)
      .each(function () {
        var $coundown = $(this).find(".countdown");
        $().jws_countdown($coundown);
      });
  };

  var category_list = function ($scope, $) {
    $scope
      .find(".jws-category-list")
      .eq(0)
      .each(function () {
        $(this)
          .find(".category-content")
          .not(".slick-initialized")
          .slick({
            prevArrow:
              '<span class="jws-carousel-btn prev-item"><i class="jws-icon-caret-left-thin"></i></span>',
            nextArrow:
              '<span class="jws-carousel-btn next-item "><i class="jws-icon-caret-right-thin"></i></span>',
            swipeToSlide: true,
            appendDots: $(".slider-dots-box"),
            dotsClass: "slider-dots",
          });
      });
  };

  /**
   *-------------------------------------------------------------------------------------------------------------------------------------------
   * Project Filter
   *-------------------------------------------------------------------------------------------------------------------------------------------
   */

  var project_filter = function ($scope, $) {
    $scope
      .find(".jws-project-element")
      .eq(0)
      .each(function () {
        var $this = $(this);
        var $container = $this.find(".project_content"),
          $filter = $this.find(".project_nav"),
          $item = $container.find(".jws_project_item");

        loadmore_btn($this);

        $container.isotope({
          itemSelector: ".jws_project_item",
          layoutMode: "masonry",
          transitionDuration: "0.7s",
        });

        $filter.find("a").on("click touchstart", function (e) {
          var $t = $(this),
            selector = $t.data("filter");
          // Don't proceed if already selected
          if ($t.hasClass("filter-active")) return false;

          $filter.find("a").removeClass("filter-active");
          $t.addClass("filter-active");
          $container.isotope({ filter: selector });

          e.stopPropagation();
          e.preventDefault();
        });
        $container.on("layoutComplete", function (event, laidOutItems) {
          var $items = $container.find(".jws_project_item");
          var time = 0;
          $items.each(function () {
            var item = jQuery(this);
            setTimeout(function () {
              item.addClass("fadeIn");
            }, time);
            time += 200;
          });
        });
      });
  };

  // Make sure you run this code under Elementor..

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/section",
      function ($element) {
        initSection($element);
      }
    );

    elementorFrontend.hooks.addAction("refresh_page_css", function (css) {
      var $obj = $("style#jws_elementor_custom_css");
      if (!$obj.length) {
        $obj = $('<style id="jws_elementor_custom_css"></style>').appendTo(
          "head"
        );
      }
      css = css.replace("/<script.*?/script>/s", "");
      $obj.html(css).appendTo("head");
    });

    var widgets = {
      "jws_video_popup.default": video_popup,
      "jws_testimonial_slider.default": testimonials_slider,
      "jws_blog.default": [blogLoadMore, blog_filter],
      "jws_tab.default": jws_tabs,
      "jws_map.default": WidgetjwsGoogleMapHandler,
      "jws_search.default": search,
      "jws_progress.default": [
        jws_process_slider,
        jws_process_tabs,
        jws_process_hover,
      ],
      "jws_team.default": [team_slider],
      "jws_table.default": jws_table,
      "jws_menu_nav.default": jws_menu_style,
      "jws_gallery.default": jws_gallery,
      "jws_demo.default": [demo_filter],
      "jws-product-advanced.default": [jws_carousel, product_tabs_filter],
      "jws_banner.default": jws_banner,
      "tooltip.default": tooltip,
      "jws_instagram.default": instagram,
      "jws_slider.default": jws_slider,
      "jws_product_group.default": jws_product_group,
      "jws-category-list.default": category_list,
      "jws_widget_countdown.default": countdown,
      "jws_text_slider.default": jws_text_slider,
      "jws_project.default": project_filter,
      "jws_dropdown_text.default": jws_dropdown_text,
      "jws_shop_video.default": jws_shop_video,
    };

    $.each(widgets, function (widget, callback) {
      if ("object" === typeof callback) {
        $.each(callback, function (index, cb) {
          elementorFrontend.hooks.addAction(
            "frontend/element_ready/" + widget,
            cb
          );
        });
      } else {
        elementorFrontend.hooks.addAction(
          "frontend/element_ready/" + widget,
          callback
        );
      }
    });
  });
})(jQuery);
