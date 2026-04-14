(function ($) {
  "use strict";

  function jwsTheme() {
    var self = this;
    self.$window = $(window);
    self.$document = $(document);
    self.$html = $("html");
    self.$body = $("body");
    self.$widgetPanel = $(".jws_mini_cart");
    self.bind();
    self.widgetPanelPrep();
  }
  jwsTheme.prototype = {
    bind: function () {
      var self = this;
      /* Bind: Widget panel */
      if (self.$widgetPanel.length) {
        self.widgetPanelBind();
      }
    },
    /**
     *    Widget panel: Prepare
     */
    widgetPanelPrep: function () {
      var self = this;
      // Cart panel: Set Ajax state
      self.cartPanelAjax = null;
      // Cart panel: Bind quantity-input buttons
      self.quantityInputsBindButtons(self.$body);
      // Quantity inputs: Bind "blur" event
      self.$body.on("blur", "input.qty", function () {
        var $quantityInput = $(this),
          currentVal = parseFloat($quantityInput.val()),
          max = parseFloat($quantityInput.attr("max"));
        // Validate input values
        if (currentVal === "" || currentVal === "NaN") {
          currentVal = 0;
        }
        if (max === "NaN") {
          max = "";
        }
        // Make sure the value is not higher than the max value
        if (currentVal > max) {
          $quantityInput.val(max);
          currentVal = max;
        }
        // Is the quantity value more than 0?
        if (currentVal > 0) {
          self.widgetPanelCartUpdate($quantityInput);
        }
      });
      // Quantity inputs: Bind "jws_qty_change" event
      self.$document.on("jws_qty_change", function (event, quantityInput) {
        // Is the widget-panel open?
        self.widgetPanelCartUpdate($(quantityInput));
      });
    },
    /**
     *    Widget panel: Bind
     */
    widgetPanelBind: function () {
      var self = this;
      /* Bind: Cart panel - Remove product */
      self.$body.on(
        "click",
        ".jws-cart-panel .cart_list .remove",
        function (e) {
          e.preventDefault();
          self.widgetPanelCartRemoveProduct(this);
        }
      );
    },
    /**
     *    Check Quickview Variable
     */
    shopCheckVariationDetails: function ($variationDetailsWrap) {
      var $variationDetailsChildren = $variationDetailsWrap.children(),
        variationDetailsEmpty = true;
      if ($variationDetailsChildren.length) {
        // Check for variation detail elements
        for (var i = 0; i < $variationDetailsChildren.length; i++) {
          if ($variationDetailsChildren.eq(i).children().length) {
            variationDetailsEmpty = false;
            break;
          }
        }
      }
      if (variationDetailsEmpty) {
        $variationDetailsWrap.hide();
      } else {
        $variationDetailsWrap.show();
      }
    },
    /**
     *    Widget panel: Cart - Remove product
     */
    widgetPanelCartRemoveProduct: function (button) {
      var self = this,
        $button = $(button),
        $itemLi = $button.closest("li"),
        $itemUl = $itemLi.parent("ul"),
        cartItemKey = $button.data("cart-item-key");
      // Show thumbnail loader
      $itemLi.closest("li").addClass("loading");
      $itemLi
        .closest("li")
        .append(
          '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
        );
      self.cartPanelAjax = $.ajax({
        type: "POST",
        url: jws_script.ajax_url,
        data: {
          action: "jws_cart_panel_remove_product",
          cart_item_key: cartItemKey,
        },
        dataType: "json",
        // Note: Disabling these to avoid "origin policy" AJAX error in some cases
        //cache: false,
        //headers: {'cache-control': 'no-cache'},
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          console.log(
            "jws: AJAX error - widgetPanelCartRemoveProduct() - " + errorThrown
          );
          $itemLi.closest("li").removeClass("loading"); // Hide thumbnail loader
        },
        complete: function (response) {
          self.cartPanelAjax = null; // Reset Ajax state
          var json = response.responseJSON;
          if (json && json.status === "1") {
            // Fade-out cart item
            $itemLi.css({
              "-webkit-transition": "0.2s opacity ease",
              transition: "0.2s opacity ease",
              opacity: "0",
            });
            setTimeout(function () {
              // Slide-up cart item
              $itemLi.css("display", "block").slideUp(150, function () {
                $itemLi.remove();
                // Show "cart empty" elements?
                var $cartLis = $itemUl.children("li");
                if ($cartLis.length == 1) {
                  $(".jws-cart-panel").addClass("jws-cart-panel-empty");
                }
                // Replace cart/shop fragments
                self.shopReplaceFragments(json.fragments);
                // Trigger "added_to_cart" event to make sure the HTML5 "sessionStorage" fragment values are updated
                self.$body.trigger("added_to_cart", [
                  json.fragments,
                  json.cart_hash,
                ]);
              });
            }, 160);
            var url = window.location.protocol + "//" + window.location.host;
            $.get(
              url,
              function (response) {
                var $result_html = $(response).find(".free_ship_nhe").html();
                $(".free_ship_nhe").html($result_html);
                $(document.body).trigger("jws_ajax_filter_request_success", [
                  response,
                  url,
                ]);
              },
              "html"
            );
          } else {
            console.log("jws: Couldn't remove product from cart");
          }
        },
      });
    },
    /**
     *    Widget panel: Cart - Update quantity
     */
    widgetPanelCartUpdate: function ($quantityInput) {
      var self = this;
      // Is an Ajax request already running?
      if (self.cartPanelAjax) {
        self.cartPanelAjax.abort(); // Abort current Ajax request
      }
      // Show thumbnail loader
      if (!$quantityInput.closest("li").find(".loader").length) {
        $quantityInput
          .closest("li")
          .append(
            '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
          );
      }

      $quantityInput.closest("li").addClass("loading");
      // Ajax data
      var data = {
        action: "jws_cart_panel_update",
      };
      data[$quantityInput.attr("name")] = $quantityInput.val();
      self.cartPanelAjax = $.ajax({
        type: "POST",
        url: jws_script.ajax_url,
        data: data,
        cache: false,
        dataType: "json",
        complete: function (response) {
          //self.cartPanelAjax = null; // Reset Ajax state
          var json = response.responseJSON;
          if (json && json.status === "1") {
            self.shopReplaceFragments(json.fragments); // Replace cart/shop fragments
          }
          // Hide any visible thumbnail loaders
          $quantityInput.closest(".loading").removeClass("loading");
        },
      });
    },
    /**
     *    Shop: Replace fragments
     */
    shopReplaceFragments: function (fragments) {
      var $fragment;
      $.each(fragments, function (selector, fragment) {
        $fragment = $(fragment);
        if ($fragment.length) {
          $(selector).replaceWith($fragment);
        }
      });
    },
    /**
     *    Quantity inputs: Bind buttons
     */
    quantityInputsBindButtons: function ($container) {
      var self = this;
      // Add buttons
      // Note: Added these to the "../global/quantity-input.php" template instead (required for the Ajax Cart)
      /*
       *	Bind buttons click event
       *	Note: Modified code from WooCommerce core (v2.2.6)
       */
      $container
        .off("click.jwsQty")
        .on("click.jwsQty", ".jws-qty-plus, .jws-qty-minus", function (e) {
          // Get elements and values
          e.preventDefault();
          var $this = $(this),
            $qty = $this.closest(".quantity").find(".qty"),
            currentVal = parseFloat($qty.val()),
            max = parseFloat($qty.attr("max")),
            min = parseFloat($qty.attr("min")),
            step = $qty.attr("step");
          // Format values
          if (!currentVal || currentVal === "" || currentVal === "NaN")
            currentVal = 0;
          if (max === "" || max === "NaN") max = "";
          if (min === "" || min === "NaN") min = 0;
          if (
            step === "any" ||
            step === "" ||
            step === undefined ||
            parseFloat(step) === "NaN"
          )
            step = 1;
          // Change the value
          if ($this.hasClass("jws-qty-plus")) {
            if (max && (max == currentVal || currentVal > max)) {
              $qty.val(max);
            } else {
              $qty.val(currentVal + parseFloat(step));
              self.quantityInputsTriggerEvents($qty);
            }
          } else {
            if (min && (min == currentVal || currentVal < min)) {
              $qty.val(min);
            } else if (currentVal > 0) {
              $qty.val(currentVal - parseFloat(step));
              self.quantityInputsTriggerEvents($qty);
            }
          }
        });
      if ($("body").hasClass("woocommerce-cart")) {
        jQuery(document.body).on("updated_cart_totals", function () {
          var $qty = $(".quantity .qty");
          self.$document.trigger("jws_qty_change", $qty);
        });
      }
    },
    /**
     *    Quantity inputs: Trigger events
     */
    quantityInputsTriggerEvents: function ($qty) {
      var self = this;
      // Trigger quantity input "change" event
      $qty.trigger("change");
      // Trigger custom event
      if (!$("body").hasClass("woocommerce-cart")) {
        self.$document.trigger("jws_qty_change", $qty);
      }
    },
  };
  // Add core script to $.jwsTheme so it can be extended
  $.jwsTheme = jwsTheme.prototype;
  var mini_cart = function ($scope, $) {
    $scope
      .find(".jws_mini_cart")
      .eq(0)
      .each(function () {
        var seft = $(this),
          widget = seft.find(".jws-cart-nav"),
          body = seft.closest("body"),
          cartWidgetSide = seft,
          id = $(this).closest(".elementor-element").data("id"),
          popup_id = $(".jws-mini-cart-wrapper"),
          cartWidgetContent = seft.find(".jws_cart_content");
        widget.on("click", function (e) {
          if (!isCart() && !isCheckout()) e.preventDefault();
          if (isOpened(popup_id)) {
            closeWidget(id);
          } else {
            setTimeout(function () {
              openWidget();
            }, 10);
          }
        });
        body.on(
          "click touchstart",
          ".jws-cart-overlay , .cart-close",
          function () {
            if (isOpened()) {
              closeWidget();
            }
          }
        );
        $(document).on("keyup", function (e) {
          if (e.keyCode === 27 && isOpened()) closeWidget();
        });
        var closeWidget = function () {
          popup_id.removeClass("active");
          $("body").css({
            position: "",
            "margin-left": "",
            "margin-right": "",
            overflow: "",
            "padding-right": "",
          });

          if ($(".is-sticky").hasClass("active-sticky")) {
            $(".is-sticky").css({
              right: "",
            });
          }

          setTimeout(function () {
            $("body").removeClass("jws-cart-animating").css({
              width: "",
            });
          }, 300);
        };
        var openWidget = function () {
          if (isCart() || isCheckout() || popup_id.hasClass("active"))
            return false;
          var o = window.innerWidth - document.documentElement.clientWidth;

          $("body").css({
            "padding-right": o,
            overflow: "hidden",
          });

          if ($(".is-sticky").hasClass("active-sticky")) {
            $(".is-sticky")
              .removeClass("active-sticky")
              .addClass("no-active-sticky");
          }

          popup_id.addClass("active");
          $(".jws-offcanvas-show").removeClass("jws-offcanvas-show");
        };
        var isOpened = function () {
          return popup_id.hasClass("active");
        };
        var isCart = function () {
          return $("body").hasClass("woocommerce-cart");
        };
        var isCheckout = function () {
          return $("body").hasClass("woocommerce-checkout");
        };
        var o = "1";
        $("body").on("adding_to_cart", function (e, t) {
          o = "";
        });
        $("body").on("added_to_cart", function () {
          if (typeof $.magnificPopup !== "undefined") {
            $.magnificPopup.close();
          }

          if (o !== "1") {
            setTimeout(function () {
              openWidget();
            }, 100);
          }
        });
      });
  };
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/jws_mini_cart.default",
      mini_cart
    );
  });

  $(document).ready(function () {
    new jwsTheme();
  });
})(jQuery);
