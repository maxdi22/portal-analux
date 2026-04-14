var jwsThemeModule;
(function ($) {
  "use strict";
  jwsThemeModule = (function () {
    return {
      jws_script: jws_script,
      init: function () {
        this.login_form();
        this.header_sticky();
        this.jwsCompare();
        this.jwsWishlist();
        this.car_single_tabs();
        this.search_product();
        this.post_gallery();
        this.post_related();
        this.menu_mobile();
        this.scrollTop();
        this.menu_list();
        this.mobile_default();
        this.jws_theme_countdown();
        this.menu_offset();
        this.video_popup();
        this.jws_theme_newsletter();
        this.init_jws_notices();
      },

      init_jws_notices: function () {
        $(document).on("click", ".show_filter_shop", function () {
          $("#jws-shop-topbar").slideToggle();
          $(this).toggleClass("active");
          $(".jws-filter-modal").fadeIn().addClass("open");
          $(".sidebar-sideout").addClass("opened").toggleClass("open");
        });
        $(document).on("click", ".modal-close , .modal-overlay", function () {
          $(".jws-filter-modal").fadeOut().removeClass("open");
          $(".show_filter_shop").removeClass("active");
        });
        $("body").on("click", ".jws-icon-cross", function (e) {
          e.preventDefault();
          var _this = $(this).parents('[role="alert"]');
          _this.remove();
        });
      },
      jws_theme_newsletter: function () {
        var _send_mail = $(".jws-newsletter-popup");
        if (_send_mail.length === 0 || typeof Cookies === undefined) return;
        var _ckie_popup = Cookies.get("ckie_popup");
        if (_ckie_popup !== "true") {
          setTimeout(function () {
            jws_content_newsletter();
          }, 6000);
        }

        $(".sub-new-nothank").on("click", function () {
          Cookies.set("ckie_popup", true, { expires: 1, path: "/" });
          setTimeout(function () {
            $.magnificPopup.close();
          }, 300);
        });

        function jws_content_newsletter() {
          $.magnificPopup.open({
            items: {
              src: ".jws-newsletter-popup",
            },
            type: "inline",
            mainClass: "mfp-fade",
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: true,
            overflowY: "scroll",
            callbacks: {
              beforeOpen: function () {
                this.st.mainClass = "quick-view-main";
              },
              open: function () {
                $(window).trigger("resize");
              },
            },
          });
        }
      },

      jws_theme_countdown: function () {
        var $tb_countdown_js = $(".jws-sale-time");
        if ($tb_countdown_js.length > 0) {
          $tb_countdown_js.each(function () {
            var $this = $(this);
            var $countdown_time = $this.data("countdown");
            var $current_time = new Date().getTime();
            var $dateEnd = new Date($countdown_time * 1000);

            if ($countdown_time > $current_time) {
              return;
            }
            // Update the count down every 1 second
            setInterval(function () {
              // Get today's date and time
              var $current_time = new Date().getTime();
              // Find the distance between now and the count down date
              var distance = $dateEnd - $current_time;
              // Time calculations for days, hours, minutes and seconds
              var days = Math.floor(distance / (1000 * 60 * 60 * 24));
              var hours = Math.floor(
                (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
              );
              var minutes = Math.floor(
                (distance % (1000 * 60 * 60)) / (1000 * 60)
              );
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);
              if (days < 1) {
                $this.html(
                  '<div class="jws-box-countdown"><span class="wrapper"><span>0</span> <p>' +
                    $this.data("d") +
                    "</p></span></div><span>:</span>" +
                    '<div class="jws-box-countdown"><span class="wrapper"><span>0</span> <p>' +
                    $this.data("h") +
                    "</p></span></div><span>:</span>" +
                    '<div class="jws-box-countdown"><span class="wrapper"><span>0</span> <p>' +
                    $this.data("m") +
                    "</p></span></div><span>:</span>" +
                    '<div class="jws-box-countdown"><span class="wrapper"><span>0</span> <p>' +
                    $this.data("s") +
                    "</p></span></div>"
                );
                return false;
              }
              $this.html(
                '<div class="jws-box-countdown"><span class="wrapper"><span>' +
                  days +
                  "</span> <p>" +
                  $this.data("d") +
                  "</p></span></div><span>:</span>" +
                  '<div class="jws-box-countdown"><span class="wrapper"><span>' +
                  hours +
                  "</span> <p>" +
                  $this.data("h") +
                  "</p></span></div><span>:</span>" +
                  '<div class="jws-box-countdown"><span class="wrapper"><span>' +
                  minutes +
                  "</span> <p>" +
                  $this.data("m") +
                  "</p></span></div><span>:</span>" +
                  '<div class="jws-box-countdown"><span class="wrapper"><span>' +
                  seconds +
                  "</span> <p>" +
                  $this.data("s") +
                  "</p></span></div>"
              );
            }, 1000);
          });
        }
      },
      menu_list: function () {
        $(document).on(
          "click",
          'body[data-elementor-device-mode="mobile"] .jws-menu-list.toggle-mobile .menu-list-title',
          function () {
            $(this).next("ul").slideToggle();
          }
        );
      },
      post_related: function () {
        var rtl = false;
        if ($("body").hasClass("rtl")) {
          rtl = true;
        }
        $(".post_related_slider").not(".slick-initialized").slick({
          dots: false,
          arrows: true,
          swipeToSlide: true,
          prevArrow:
            '<span class="jws-carousel-btn prev-item"><i class="jws-icon-caret-right-thin"></i></span>',
          nextArrow:
            '<span class="jws-carousel-btn next-item "><i class="jws-icon-caret-right-thin"></i></span>',
          rtl: rtl,
        });
      },

      /* Car form for autocomplete input fields*/
      search_product: function () {
        if (typeof $.fn.devbridgeAutocomplete == "undefined") return;
        var escapeRegExChars = function (value) {
          return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
        };
        $("form.jws-ajax-search").each(function () {
          var $this = $(this),
            number = parseInt($this.data("count")),
            thumbnail = parseInt($this.data("thumbnail")),
            postCat = $this.find('[name="product_cat"]'),
            $results = $this.parent().find(".jws-search-results"),
            postType = $this.data("post_type"),
            url = jws_script.ajax_url + "?action=jws_ajax_search",
            price = parseInt($this.data("price"));
          url += "&post_type=" + postType;
          $results.on("click", ".view-all-results", function () {
            $this.submit();
          });
          $(document).on("click", ".jws-reset-search", function () {
            console.log("ok");
            $this.find('[type="text"]').val("").change();
          });
          $this.find('[type="text"]').devbridgeAutocomplete({
            serviceUrl: url,
            appendTo: $results,
            onSelect: function (suggestion) {
              if (suggestion.permalink.length > 0)
                window.location.href = suggestion.permalink;
            },
            onSearchStart: function (query) {
              if (!$this.find(".form-loader .loader").length) {
                $this
                  .find(".form-loader")
                  .append(
                    '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
                  );
              }
              $this.addClass("search-loading");
            },
            beforeRender: function (container) {
              if (container[0].childElementCount > 2)
                $(container).append(
                  '<div class="view-all-results"><span>View all results</span></div>'
                );
            },
            onSearchComplete: function (query, suggestions) {
              $this.removeClass("search-loading");
            },
            formatResult: function (suggestion, currentValue) {
              if (currentValue == "&") currentValue = "&#038;";
              var pattern = "(" + escapeRegExChars(currentValue) + ")",
                returnValue = "";
              if (thumbnail && suggestion.thumbnail) {
                returnValue +=
                  ' <div class="suggestion-thumb">' +
                  suggestion.thumbnail +
                  "</div>";
              }
              returnValue +=
                '<div class="suggestion_content"><h4 class="suggestion-title result-title">' +
                suggestion.value +
                "</h4>";
              if (suggestion.no_found)
                returnValue =
                  '<div class="suggestion-title no-found-msg">' +
                  suggestion.value +
                  "</div>";
              if (price && suggestion.price) {
                returnValue +=
                  ' <div class="suggestion-price price">' +
                  suggestion.price +
                  "</div></div>";
              }
              return returnValue;
            },
          });
          if (postCat.length) {
            var searchForm = $this
                .find('[type="text"]')
                .devbridgeAutocomplete(),
              serviceUrl = jws_script.ajax_url + "?action=jws_ajax_search";
            if (number > 0) serviceUrl += "&number=" + number;
            serviceUrl += "&post_type=" + postType;
            postCat.on("cat_selected", function () {
              if (postCat.val() != "") {
                searchForm.setOptions({
                  serviceUrl: serviceUrl + "&product_cat=" + postCat.val(),
                });
              } else {
                searchForm.setOptions({
                  serviceUrl: serviceUrl,
                });
              }
              searchForm.hide();
              searchForm.onValueChange();
            });
          }
          $("body").on("click", function () {
            //$this.find('[type="text"]').devbridgeAutocomplete('hide');
          });
          $(".jws-search-results").on("click", function (e) {
            e.stopPropagation();
          });
        });
        $(".input-dropdown")
          .eq(0)
          .each(function () {
            var dd = $(this);
            var btn = dd.find("> a");
            var input = dd.find("> input");
            var list = dd.find("> .list-wrapper");
            var form_skin = dd.parents(".jws-search-form");

            list.on("click", "a", function (e) {
              e.preventDefault();

              var value = $(this).data("val");
              var label = $(this).text();
              list.find(".active").removeClass("active");
              $(this).addClass("active");
              btn.text(label);
              input.val(value).trigger("cat_selected");
            });
          });
      },
      car_single_tabs: function () {
        $(document).on(
          "click",
          ".form-filter-product-cars span[data-tabs]",
          function () {
            var tabs_main = $(this).parents(".form-filter-product-cars");
            tabs_main.find("span[data-tabs]").removeClass("active");
            tabs_main.find(".tabcontent").hide();
            var tab = $(this).data("tabs");
            $(this).addClass("active");
            $("#" + tab).show();
          }
        );
      },
      jwsCompare: function () {
        var cookiesName = "jws_compare_list";

        if (jwsThemeModule.jws_script.is_multisite) {
        }
        var $body = $("body"),
          $widget = $(".jws-compare-info-widget"),
          compareCookie = Cookies.get(cookiesName);
        if ($widget.length > 0) {
          try {
            var ids = JSON.parse(compareCookie);
            $widget.find(".compare-count").text(ids.length);
          } catch (e) {
            console.log("cant parse cookies json");
          }
        }
        // Add to compare action
        $body.on(
          "click",
          "a.jws-compare-btn, a.jws-comparesg-btn",
          function (e) {
            var $this = $(this),
              id = $this.data("id");
            if ($this.hasClass("added")) return true;
            e.preventDefault();
            $this.addClass("loading");
            $.ajax({
              url: jwsThemeModule.jws_script.ajax_url,
              data: {
                action: "jws_add_to_compare",
                id: id,
              },
              dataType: "json",
              method: "GET",
              success: function (response) {
                if (response.table) {
                  updateCompare(response);
                } else {
                  console.log(
                    "something wrong loading compare data ",
                    response
                  );
                }
              },
              error: function () {
                console.log(
                  "We cant add to compare. Something wrong with AJAX response. Probably some PHP conflict."
                );
              },
              complete: function () {
                $this.removeClass("loading").addClass("added");
              },
            });
          }
        );
        // Remove from compare action
        $body.on("click", ".jws-compare-remove", function (e) {
          var $table = $(".jws-compare-table");
          e.preventDefault();
          var $this = $(this),
            id = $this.data("id");
          $table.addClass("loading");
          $this.addClass("loading");
          $.ajax({
            url: jwsThemeModule.jws_script.ajax_url,
            data: {
              action: "jws_remove_from_compare",
              id: id,
            },
            dataType: "json",
            method: "GET",
            success: function (response) {
              if (response.table) {
                updateCompare(response);
              } else {
                console.log("something wrong loading compare data ", response);
              }
            },
            error: function () {
              console.log(
                "We cant remove product compare. Something wrong with AJAX response. Probably some PHP conflict."
              );
            },
            complete: function () {
              $table.removeClass("loading");
              $this.addClass("loading");
            },
          });
        });
        // Elements update after ajax
        function updateCompare(data) {
          if ($widget.length > 0) {
            $widget.find(".compare-count").text(data.count);
          }
          if ($(".jws-compare-table").length > 0) {
            $(".jws-compare-table").replaceWith(data.table);
          }
        }
      },
      /**
       *-------------------------------------------------------------------------------------------------------------------------------------------
       * jws wishlist functions
       *-------------------------------------------------------------------------------------------------------------------------------------------
       */
      jwsWishlist: function () {
        var cookiesName = "jws_wishlist_list";
        if (jwsThemeModule.jws_script.is_multisite) {
          cookiesName += "_" + jwsThemeModule.jws_script.current_blog_id;
        }
        var $body = $("body"),
          $widget = $(".jws_wishlist"),
          wishlistCookie = Cookies.get(cookiesName);
        if ($widget.length > 0) {
          try {
            var ids = JSON.parse(wishlistCookie);
            $(".jws_wishlist_count").text(ids.length);
          } catch (e) {
            console.log("cant parse cookies json");
          }
        }
        // Add to wishlist action
        $body.on(
          "click",
          "a.jws-wishlist-btn , a.jws-wishlistsg-btn",
          function (e) {
            var $this = $(this),
              id = $this.data("id");
            if ($this.hasClass("added")) return true;
            e.preventDefault();
            if (!$this.find(".loader").length) {
              $this.append(
                '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
              );
            }
            $this.addClass("loading");
            $.ajax({
              url: jwsThemeModule.jws_script.ajax_url,
              data: {
                action: "jws_add_to_wishlist",
                id: id,
              },
              dataType: "json",
              method: "GET",
              success: function (response) {
                if (response.table) {
                  updateWishlist(response);
                } else {
                  console.log(
                    "something wrong loading wishlist data ",
                    response
                  );
                }
              },
              error: function () {
                console.log(
                  "We cant add to wishlist. Something wrong with AJAX response. Probably some PHP conflict."
                );
              },
              complete: function () {
                $this.removeClass("loading").addClass("added");
                $(".jws_toolbar_wishlist").addClass("dots");
                setTimeout(function () {
                  $(".jws_toolbar_wishlist").removeClass("dots");
                }, 3000);
              },
            });
          }
        );
        // Remove from wishlist action
        $body.on("click", ".jws-wishlist-remove", function (e) {
          var $table = $(".jws-wishlist-table");
          e.preventDefault();
          var $this = $(this),
            table = $this.parents(".jws-wishlist-table"),
            id = $this.data("id");
          if (!table.find(".loader").length) {
            table.append(
              '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
            );
          }

          table.addClass("loading");
          $.ajax({
            url: jwsThemeModule.jws_script.ajax_url,
            data: {
              action: "jws_remove_from_wishlist",
              id: id,
            },
            dataType: "json",
            method: "GET",
            success: function (response) {
              if (response.table) {
                updateWishlist(response);
              } else {
                console.log("something wrong loading wishlist data ", response);
              }
            },
            error: function () {
              console.log(
                "We cant remove product wishlist. Something wrong with AJAX response. Probably some PHP conflict."
              );
            },
            complete: function () {
              table.removeClass("loading");
            },
          });
        });
        // Elements update after ajax
        function updateWishlist(data) {
          if ($widget.length > 0) {
            $(".jws_wishlist_count").text(data.count);
          }
          if ($(".jws-wishlist-table").length > 0) {
            $(".jws-wishlist-table").replaceWith(data.table);
          }
        }
      },
      header_sticky: function () {
        if ($(".cafe-row-sticky")[0]) {
          $(".cafe-row-sticky").each(function () {
            var $this = $(this);
            var $sidebar = $(".jws_sticky_move");
            var $parent = $(this).parent();
            var current_width = 0;
            $(window)
              .on("load resize", function () {
                if (current_width != $(window).width()) {
                  current_width = $(window).outerWidth();
                  $parent.height("");
                  if (
                    current_width > 1024.98 &&
                    $this.hasClass("desktop-sticky")
                  ) {
                    $parent.height($this.outerHeight());
                  } else if (
                    current_width < 1024.98 &&
                    current_width > 768.98 &&
                    $this.hasClass("tablet-sticky")
                  ) {
                    $parent.height($this.outerHeight());
                  } else if (
                    current_width < 768.98 &&
                    $this.hasClass("mobile-sticky")
                  ) {
                    $parent.height($this.outerHeight());
                  } else {
                    $this.removeClass("is-sticky");
                    $this
                      .find(".elementor-widget-clever-site-logo")
                      .removeClass("header-is-sticky");
                  }
                }
              })
              .trigger("resize");
            var HeaderTop = $parent.offset().top - $("body").offset().top;
            var old_top_position = 0;
            $(window).on("scroll", function () {
              var top = $(window).scrollTop();
              if ($this.hasClass("cafe-scroll-up-sticky")) {
                top = top - $parent.outerHeight();
                if (old_top_position > top && top > $parent.outerHeight() * 3) {
                  $this.not(".active-sticky").addClass("active-sticky");
                  $this.removeClass("no-active-sticky");
                  $sidebar.removeClass("no-active-sticky");
                } else {
                  $this.removeClass("active-sticky");
                  if ($this.hasClass("is-sticky")) {
                    $this.addClass("no-active-sticky");
                    $sidebar.addClass("no-active-sticky");
                  }
                }
                old_top_position = top;
              }
              if (current_width > 1024.98 && $this.hasClass("desktop-sticky")) {
                if (HeaderTop < top) {
                  $this.not(".is-sticky").addClass("is-sticky");
                  $this
                    .find(
                      ".elementor-widget-clever-site-logo:not(.header-is-sticky)"
                    )
                    .addClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .removeClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideUp();
                } else {
                  $this.removeClass("is-sticky");
                  $this.removeClass("no-active-sticky");
                  $sidebar.removeClass("no-active-sticky");
                  $this
                    .find(".elementor-widget-clever-site-logo")
                    .removeClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .addClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideDown();
                }
              } else if (
                current_width < 1024.98 &&
                current_width > 768.98 &&
                $this.hasClass("tablet-sticky")
              ) {
                if (HeaderTop < top) {
                  $this.not(".is-sticky").addClass("is-sticky");
                  $this
                    .find(".elementor-widget-clever-site-logo")
                    .addClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .removeClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideUp();
                } else {
                  $this.removeClass("is-sticky");
                  $this.removeClass("no-active-sticky");
                  $sidebar.removeClass("no-active-sticky");
                  $this
                    .find(".elementor-widget-clever-site-logo")
                    .removeClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .addClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideDown();
                }
              } else if (
                current_width < 768.98 &&
                $this.hasClass("mobile-sticky")
              ) {
                if (HeaderTop < top) {
                  $this.not(".is-sticky").addClass("is-sticky");
                  $this
                    .find(
                      ".elementor-widget-clever-site-logo:not(.header-is-sticky)"
                    )
                    .addClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .removeClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideUp();
                } else {
                  $this.removeClass("is-sticky");
                  $this.removeClass("no-active-sticky");
                  $sidebar.removeClass("no-active-sticky");
                  $this
                    .find(".elementor-widget-clever-site-logo.header-is-sticky")
                    .removeClass("header-is-sticky");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .addClass("toggle-active");
                  $(".cafe-wrap-menu .toggle .arrow.on-scroll")
                    .parents(".cafe-wrap-menu")
                    .find(".wrap-menu-inner")
                    .slideDown();
                }
              }
            });
          });
        }
      },
      post_share: function () {
        $(".post-share .social_label").on("click", function () {
          var parents = $(this).parents(".post-share");
          parents.toggleClass("opened");
          if (parents.hasClass("opened")) {
            parents
              .find("a")
              .delay(100)
              .each(function (i) {
                $(this)
                  .delay(100 * i)
                  .queue(function () {
                    $(this).addClass("show");
                    $(this).dequeue();
                  });
              });
          } else {
            parents.find("a").removeClass("show");
          }
        });
      },
      /* ## Theme popup */
      mobile_default: function () {
        $("body").on("click", ".jws-tiger-mobile,.overlay", function () {
          $(this).parents(".elemetor-menu-mobile").toggleClass("active");
        });
      },
      /* ## Theme popup */
      handlePopup: function (data) {
        $(data).each(function () {
          // Activate popup
          $(this).addClass("visible");
          $(this).find(".btn-loading-disabled").addClass("btn-loading");
        });
      },
      scrollTop: function () {
        //Check to see if the window is top if not then display button
        $(window).on("scroll", function () {
          if ($(this).scrollTop() > 100) {
            $(".backToTop").addClass("totop-show");
          } else {
            $(".backToTop").removeClass("totop-show");
          }
        });
        //Click event to scroll to top
        $(".backToTop").on("click", function () {
          $("html, body").animate(
            {
              scrollTop: 0,
            },
            1000
          );
          return false;
        });
      },
      /**
       *-------------------------------------------------------------------------------------------------------------------------------------------
       * video popup
       *-------------------------------------------------------------------------------------------------------------------------------------------
       */
      video_popup: function () {
        $(".video_format")
          .eq(0)
          .each(function () {
            $(".video_format").magnificPopup({
              delegate: "a",
              type: "image",
              removalDelay: 500, //delay removal by X to allow out-animation
              callbacks: {
                beforeOpen: function () {
                  this.st.mainClass = "mfp-zoom-in";
                },
                elementParse: function (item) {
                  (item.type = "iframe"),
                    (item.iframe = {
                      patterns: {
                        youtube: {
                          index: "youtube.com/", // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
                          id: "v=", // String that splits URL in a two parts, second part should be %id%
                          // Or null - full URL will be returned
                          // Or a function that should return %id%, for example:
                          // id: function(url) { return 'parsed id'; }
                          src: "//www.youtube.com/embed/%id%?autoplay=1", // URL that will be set as a source for iframe.
                        },
                        vimeo: {
                          index: "vimeo.com/",
                          id: "/",
                          src: "//player.vimeo.com/video/%id%?autoplay=1",
                        },
                      },
                    });
                },
              },
            });
          });
      },
      /**
       *-------------------------------------------------------------------------------------------------------------------------------------------
       * post fomart
       *--------o-----------------------------------------------------------------------------------------------------------------------------------
       */
      post_gallery: function () {
        var rtl = false;
        if ($("body").hasClass("rtl")) {
          rtl = true;
        }
        $(".post-image-slider").not(".slick-initialized").slick({
          dots: false,
          arrows: true,
          prevArrow:
            '<span class="jws-carousel-btn prev-item"><i class="jws-icon-arrow-right-thin"></i></span>',
          nextArrow:
            '<span class="jws-carousel-btn next-item "><i class="jws-icon-arrow-right-thin"></i></span>',
          rtl: rtl,
        });
      },
      menu_offset: function () {
        var setOffset = function (li, $menu) {
          var $dropdown = li;
          var dropdownWidth = $dropdown.outerWidth();
          var dropdownOffset = $menu.offset();
          var toRight;
          var viewportWidth;
          var dropdownOffsetRight;
          viewportWidth = $(document).width();
          if (!dropdownWidth || !dropdownOffset) {
            return;
          }

          if ($dropdown.hasClass("mega_menu_full_width")) {
            dropdownOffsetRight =
              viewportWidth - dropdownOffset.left - dropdownWidth;
            var extraSpace = 0;
            var dropdownOffsetLeft;

            dropdownOffsetLeft = dropdownOffsetRight;

            $dropdown.css({
              left: -dropdownOffset.left - extraSpace,
            });
          }
        };
        $(
          ".elementor_jws_menu_layout_menu_horizontal li.menu-item-design-mega_menu_full_width"
        ).each(function () {
          var $menu = $(this);
          $menu.find(" > .sub-menu-dropdown").each(function () {
            setOffset($(this), $menu);
          });
        });
      },
      menu_mobile: function () {
        var dropDownCat = $(
            ".elementor_jws_menu_layout_menu_vertical .menu-item-has-children ,.elementor_jws_menu_layout_menu_vertical .menu_has_shortcode"
          ),
          elementIcon =
            '<button class="btn-sub-menu jws-icon-caret-down-thin"></button>';
        $(elementIcon).insertAfter(dropDownCat.find("> a"));
        if (dropDownCat.hasClass("active")) {
          dropDownCat.addClass("active");
        }
        $(document).on("click", ".btn-sub-menu", function (e) {
          e.preventDefault();

          $(this).parent().siblings().removeClass("active");
          $(this)
            .parent()
            .siblings()
            .find("> ul,.sub-menu-dropdown")
            .slideUp(320);

          $(this).parent().find("> ul").slideToggle(320);
          $(this).parent().find(".sub-menu-dropdown").slideToggle(320);

          if ($(this).parent().hasClass("active")) {
            $(this).parent().removeClass("active");
          } else {
            $(this).parent().addClass("active");
          }
        });
      },
      login_form: function () {
        var rtl = false;
        if ($("body").hasClass("rtl")) {
          rtl = true;
        }
        $(".jws-open-login:not(.logged)").on("click", function (e) {
          event.preventDefault();
          $(".jws-form-login-popup").addClass("open");
          $(".jws-offcanvas").removeClass("jws-offcanvas-show");
          $(".jws-offcanvas-trigger").removeClass("active");
        });
        $(".jws-close , .jws-form-overlay").on("click", function (e) {
          $(".jws-form-login-popup").removeClass("open");
        });
        $(".jws_toolbar_search").on("click", function (e) {
          e.preventDefault();
          $(".form_content_popup").addClass("open");
        });

        $(".jws-login-form").each(function () {
          var $this = $(this);

          $this.find(".form-contaier").not(".slick-initialized").slick({
            swipeToSlide: true,
            dots: false,
            arrows: false,
            adaptiveHeight: true,
            infinite: false,
            swipe: false,
            rtl: rtl,
          });
          $(this)
            .find("form[name=loginpopopform]")
            .on("submit", function (event) {
              event.preventDefault();

              var valid = true,
                email_valid = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/gim;
              $(this)
                .find("input.required")
                .each(function () {
                  // Check empty value
                  if (!$(this).val()) {
                    $(this).addClass("invalid");
                    valid = false;
                  }
                  // Uncheck
                  if ($(this).is(":checkbox") && !$(this).is(":checked")) {
                    $(this).addClass("invalid");
                    valid = false;
                  }
                  // Check email format
                  if ("email" === $(this).attr("type")) {
                    if (!email_valid.test($(this).val())) {
                      $(this).addClass("invalid");
                      valid = false;
                    }
                  }
                });
              $(this)
                .find("input.required")
                .on("focus", function () {
                  $(this).removeClass("invalid");
                });
              if (!valid) {
                return valid;
              }
              var form = $(this),
                $elem = $this.find(".jws-login-container"),
                wp_submit = $elem.find("input[type=submit]").val();
              if (!$elem.find(".loader").length) {
                $elem.append(
                  '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
                );
              }
              $elem.addClass("loading");
              $elem.find(".jws-login .popup-message").slideUp();
              $elem.find(".message").slideDown().remove();
              setTimeout(function () {
                $this.find(".form-contaier")[0].slick.animateHeight();
              }, 500);
              var data = {
                action: "jws_login_ajax",
                data: form.serialize() + "&wp-submit=" + wp_submit,
              };
              $.post(
                jwsThemeModule.jws_script.ajax_url,
                data,
                function (response) {
                  if (response.data.code == "1") {
                    if (response.data.redirect) {
                      if (window.location.href == response.data.redirect) {
                        location.reload();
                      } else {
                        window.location.href = response.data.redirect;
                      }
                    } else {
                      location.reload();
                    }

                    $elem
                      .find(".jws-login .popup-message")
                      .removeClass("woocommerce-info")
                      .addClass("woocommerce-message");
                  } else {
                    $elem
                      .find(".jws-login .popup-message")
                      .addClass("woocommerce-info");
                  }
                  $elem
                    .find(".jws-login .popup-message")
                    .html(response.data.message)
                    .slideDown();
                  $elem.removeClass("loading");

                  setTimeout(function () {
                    $this.find(".form-contaier")[0].slick.animateHeight();
                  }, 500);
                }
              );
              return false;
            });
          $(this)
            .find("form[name=registerformpopup]")
            .on("submit", function (e) {
              e.preventDefault();
              var valid = true,
                email_valid = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/gim;
              $(this)
                .find("input.required")
                .each(function () {
                  // Check empty value
                  if (!$(this).val()) {
                    $(this).addClass("invalid");
                    valid = false;
                  }
                  // Uncheck
                  if ($(this).is(":checkbox") && !$(this).is(":checked")) {
                    $(this).addClass("invalid");
                    valid = false;
                  }
                  // Check email format
                  if ("email" === $(this).attr("type")) {
                    if (!email_valid.test($(this).val())) {
                      $(this).addClass("invalid");
                      valid = false;
                    }
                  }
                });
              $(this)
                .find("input.required")
                .on("focus", function () {
                  $(this).removeClass("invalid");
                });
              if (!valid) {
                return valid;
              }
              var $form = $(this),
                data = {
                  action: "jws_register_ajax",
                  data:
                    $form.serialize() +
                    "&wp-submit=" +
                    $form.find("input[type=submit]").val(),
                  register_security: $form.find("#register_security").val(),
                },
                $elem = $("#jws-login-form .jws-login-container");
              if (!$elem.find(".loader").length) {
                $elem.append(
                  '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
                );
              }
              $elem.addClass("loading");
              $elem.find(".jws-register .popup-message").slideUp();
              $elem.find(".message").slideDown().remove();
              setTimeout(function () {
                $this.find(".form-contaier")[0].slick.animateHeight();
              }, 500);
              $.ajax({
                type: "POST",
                url: jwsThemeModule.jws_script.ajax_url,
                data: data,
                success: function (response) {
                  $elem.removeClass("loading");
                  console.log(response);
                  if (response.data.code == "1") {
                    if (response.data.redirect) {
                      if (window.location.href == response.data.redirect) {
                        location.reload();
                      } else {
                        window.location.href = response.data.redirect;
                      }
                    } else {
                      location.reload();
                    }

                    $elem
                      .find(".jws-register .popup-message")
                      .removeClass("woocommerce-info")
                      .addClass("woocommerce-message");
                  } else {
                    $elem
                      .find(".jws-register .popup-message")
                      .addClass("woocommerce-info");
                  }
                  $elem
                    .find(".jws-register .popup-message")
                    .html(response.data.message)
                    .slideDown();
                  setTimeout(function () {
                    $this.find(".form-contaier")[0].slick.animateHeight();
                  }, 500);
                },
              });
            });
          /* Check Strong Passwoed */
          $(this)
            .find('.jws-register input[name="password"]')
            .on("keyup", function () {
              checkpassword($(this).val());
              $(".slick-list").css("height", "auto");
            });

          function checkpassword(password) {
            var strength = 0,
              meter = $(".meter"),
              meter_text = $(".text-meter"),
              password_hint = $(".jws-password-hint");
            if (password.match(/[a-z]+/)) {
              strength += 1;
            }
            if (password.match(/[A-Z]+/) && password.length >= 8) {
              strength += 1;
            }
            if (password.match(/[0-9]+/) && password.length >= 12) {
              strength += 1;
            }
            if (password.match(/[$@#&!]+/) && password.length >= 14) {
              strength += 1;
            }
            if (password.length > 0) {
              meter.show();
              password_hint.show();
            } else {
              meter.hide();
              password_hint.hide();
            }
            switch (strength) {
              case 0:
                meter_text.html("");
                meter.attr("meter", "0");
                break;
              case 1:
                meter_text.html(jwsThemeModule.jws_script.metera);
                meter.attr("meter", "1");
                break;
              case 2:
                meter_text.html(jwsThemeModule.jws_script.meterb);
                meter.attr("meter", "2");
                break;
              case 3:
                meter_text.html(jwsThemeModule.jws_script.meterc);
                meter.attr("meter", "3");
                password_hint.hide();
                break;
              case 4:
                meter_text.html(jwsThemeModule.jws_script.meterd);
                meter.attr("meter", "4");
                password_hint.hide();
                break;
            }
          }
          $(this)
            .find(".change-form.login")
            .on("click", function (e) {
              e.preventDefault();
              $this.addClass("in-login");
              $this.removeClass("in-register");
              $this.find(".form-contaier").slick("slickGoTo", 0);
            });
          $(this)
            .find(".change-form.register")
            .on("click", function (e) {
              e.preventDefault();
              $this.removeClass("in-login");
              $this.addClass("in-register");
              $this.find(".form-contaier").slick("slickGoTo", 1);
            });
          $(this)
            .find(".toggle-password2")
            .on("click", function () {
              $(this).toggleClass("jws-icon-eye-slash-light");
              $(this)
                .parents("form")
                .find('input[type="password"]')
                .addClass("change-type");
              if (
                $(this).parents("form").find(".change-type").attr("type") ==
                "password"
              ) {
                $(this)
                  .parents("form")
                  .find(".change-type")
                  .attr("type", "text");
              } else {
                $(this)
                  .parents("form")
                  .find(".change-type")
                  .attr("type", "password");
              }
            });
        });
        var recaptcha7;
        var recaptcha8;
        $(window).on("load", function () {
          if (
            document.getElementById("recaptcha7") &&
            typeof goole_captcha_api_obj !== "undefined"
          ) {
            recaptcha7 = grecaptcha.render("recaptcha7", {
              sitekey: goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
              theme: "light",
            });
          }
          if (
            document.getElementById("recaptcha8") &&
            typeof goole_captcha_api_obj !== "undefined"
          ) {
            recaptcha8 = grecaptcha.render("recaptcha8", {
              sitekey: goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
              theme: "light",
            });
          }
        });
      },
      menu_nav: function () {
        var mainMenu = $(".elementor_jws_menu_layout_menu_horizontal").find(
            ".nav"
          ),
          lis = mainMenu.find(" > li.menu-item-design-mega_menu");
        mainMenu.on("hover", " > li.menu-item-design-mega_menu", function () {
          setOffset($(this));
        });
        var setOffset = function (li) {
          var dropdown = li.find(" > .sub-menu-dropdown");
          dropdown.attr("style", "");
          var dropdownWidth = dropdown.outerWidth(),
            dropdownOffset = dropdown.offset(),
            screenWidth = $(window).width(),
            viewportWidth = screenWidth,
            extraSpace = 10;
          if (!dropdownWidth || !dropdownOffset) return;
          if (
            dropdownOffset.left + dropdownWidth >= viewportWidth &&
            li.hasClass("menu-item-design-mega_menu")
          ) {
            // If right point is not in the viewport
            var toRight = dropdownOffset.left + dropdownWidth - viewportWidth;
            dropdown.css({
              left: -toRight - extraSpace,
            });
          }
        };
        lis.each(function () {
          setOffset($(this));
          $(this).addClass("with-offsets");
        });
        //mega menu
        var mega_item = mainMenu.find(
          " > li.menu-item-design-mega_menu_full_width"
        );
        var menu_item = mainMenu.find(" > li.menu-item");
        if (mega_item.length > 0) {
          $(".jws_header").addClass("has-mega-full");
        }

        if ($(".hover-background-sticky").length > 0) {
          $(".jws_header").addClass("has-mega-full");
          if (
            $(".elementor_jws_menu_layout_menu_horizontal").hasClass(
              "elementor-jws-menu-change-background-yes"
            )
          ) {
            menu_item.mouseenter(function () {
              $(".jws_header.has-mega-full").addClass("mega-has-hover");
            });
            menu_item.mouseleave(function () {
              $(".jws_header.has-mega-full").removeClass("mega-has-hover");
            });
          }
        }
        if (
          $(".elementor_jws_menu_layout_menu_horizontal").hasClass(
            "elementor-jws-menu-change-background-yes"
          )
        ) {
          mega_item.mouseenter(function () {
            $(".jws_header.has-mega-full").addClass("mega-has-hover");
          });
          mega_item.mouseleave(function () {
            $(".jws_header.has-mega-full").removeClass("mega-has-hover");
          });
        }
      },
    };
  })();
  $(document).ready(function () {
    jwsThemeModule.init();
  });
  $.fn.isInViewport = function () {
    let elementTop = $(this).offset().top;
    let elementBottom = elementTop + $(this).outerHeight();
    let viewportTop = $(window).scrollTop();
    let viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };

  $(window).on("resize", function (e) {
    jwsThemeModule.menu_offset();
  });

  $(document).ready(function () {
    setTimeout(function () {
      $(".load-template").each(function () {
        $(this)
          .parent()
          .html(JSON.parse($(this).html()));
      });
    }, "700");
  });

  $.fn.gallery_popup = function (option) {
    if (typeof $.fn.magnificPopup == "undefined") return;
    option.find("a.jws-popup-global").magnificPopup({
      type: "image",
      gallery: {
        enabled: true,
      },
      removalDelay: 500, //delay removal by X to allow out-animation
      mainClass: "gallery-global mfp-zoom-in mfp-img-mobile",
      callbacks: {
        open: function () {
          //overwrite default prev + next function. Add timeout for css3 crossfade animation
          $.magnificPopup.instance.next = function () {
            var self = this;
            self.wrap.removeClass("mfp-image-loaded");
            setTimeout(function () {
              $.magnificPopup.proto.next.call(self);
            }, 120);
          };
          $.magnificPopup.instance.prev = function () {
            var self = this;
            self.wrap.removeClass("mfp-image-loaded");
            setTimeout(function () {
              $.magnificPopup.proto.prev.call(self);
            }, 120);
          };
        },
        imageLoadComplete: function () {
          var self = this;
          setTimeout(function () {
            self.wrap.addClass("mfp-image-loaded");
          }, 16);
        },
      },
    });
  };

  $.fn.jws_countdown = function (selector) {
    if ($.fn.countdown) {
      //$(selector).each(function () {
      var $this = selector,
        untilDate = $this.attr("data-until"),
        compact = $this.attr("data-compact"),
        dateFormat = !$this.attr("data-format")
          ? "DHMS"
          : $this.attr("data-format"),
        newLabels = !$this.attr("data-labels-short")
          ? ["Years", "Months", "Weeks", "Days", "Hours", "Minutes", "Seconds"]
          : ["Years", "Months", "Weeks", "Days", "Hours", "Mins", "Secs"],
        newLabels1 = !$this.attr("data-labels-short")
          ? ["Year", "Month", "Week", "Day", "Hour", "Minute", "Second"]
          : ["Year", "Month", "Week", "Day", "Hour", "Min", "Sec"];

      $this.data("countdown") && $this.countdown("destroy");

      if ($(this).hasClass("user-tz")) {
        $this.countdown({
          until: !$this.attr("data-relative") ? new Date(untilDate) : untilDate,
          format: dateFormat,
          padZeroes: true,
          compact: compact,
          compactLabels: [" y", " m", " w", " days, "],
          timeSeparator: " : ",
          labels: newLabels,
          labels1: newLabels1,
          serverSync: new Date($(this).attr("data-time-now")),
        });
      } else {
        $this.countdown({
          until: !$this.attr("data-relative") ? new Date(untilDate) : untilDate,
          format: dateFormat,
          padZeroes: true,
          compact: compact,
          compactLabels: [" y", " m", " w", " days, "],
          timeSeparator: " : ",
          labels: newLabels,
          labels1: newLabels1,
        });
      }
      //});
    }
  };
})(jQuery);
