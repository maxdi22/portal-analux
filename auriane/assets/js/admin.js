(function ($) {
  "use strict";
  $(document).ready(function () {
    function variationGallery() {
      $(".jws-variation-gallery-wrapper").each(function () {
        var $this = $(this);
        var $galleryImages = $this.find(".jws-variation-gallery-images");
        var $imageGalleryIds = $this.find(".variation-gallery-ids");
        var galleryFrame;
        $this.on("click", ".jws-add-variation-gallery-image", function (event) {
          event.preventDefault();
          // If the media frame already exists, reopen it.
          if (galleryFrame) {
            galleryFrame.open();
            return;
          }
          // Create the media frame.
          galleryFrame = wp.media.frames.product_gallery = wp.media({
            states: [
              new wp.media.controller.Library({
                filterable: "all",
                multiple: true,
              }),
            ],
          });
          // When an image is selected, run a callback.
          galleryFrame.on("select", function () {
            var selection = galleryFrame.state().get("selection");
            var attachment_ids = $imageGalleryIds.val();
            selection.map(function (attachment) {
              attachment = attachment.toJSON();
              if (attachment.id) {
                var attachment_image =
                  attachment.sizes && attachment.sizes.thumbnail
                    ? attachment.sizes.thumbnail.url
                    : attachment.url;
                attachment_ids = attachment_ids
                  ? attachment_ids + "," + attachment.id
                  : attachment.id;
                $galleryImages.append(
                  '<li class="image" data-attachment_id="' +
                    attachment.id +
                    '"><img src="' +
                    attachment_image +
                    '"><a href="#" class="delete jws-remove-variation-gallery-image"><span class="dashicons dashicons-dismiss"></span></a></li>'
                );
                $this.trigger("jws_variation_gallery_image_added");
              }
            });
            $imageGalleryIds.val(attachment_ids);
            $this
              .parents(".woocommerce_variation")
              .eq(0)
              .addClass("variation-needs-update");
            $("#variable_product_options").find("input").eq(0).change();
          });
          // Finally, open the modal.
          galleryFrame.open();
        });
        // Image ordering.
        if (typeof $galleryImages.sortable !== "undefined") {
          $galleryImages.sortable({
            items: "li.image",
            cursor: "move",
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: "clone",
            opacity: 0.65,
            placeholder: "wc-metabox-sortable-placeholder",
            start: function (event, ui) {
              ui.item.css("background-color", "#f6f6f6");
            },
            stop: function (event, ui) {
              ui.item.removeAttr("style");
            },
            update: function () {
              var attachment_ids = "";
              $galleryImages.find("li.image").each(function () {
                var attachment_id = $(this).attr("data-attachment_id");
                attachment_ids = attachment_ids + attachment_id + ",";
              });
              $imageGalleryIds.val(attachment_ids);
              $this
                .parents(".woocommerce_variation")
                .eq(0)
                .addClass("variation-needs-update");
              $("#variable_product_options").find("input").eq(0).change();
            },
          });
        }
        // Remove images.
        $(document).on(
          "click",
          ".jws-remove-variation-gallery-image",
          function (event) {
            event.preventDefault();
            $(this).parent().remove();
            var attachment_ids = "";
            $galleryImages.find("li.image").each(function () {
              var attachment_id = $(this).attr("data-attachment_id");
              attachment_ids = attachment_ids + attachment_id + ",";
            });
            $imageGalleryIds.val(attachment_ids);
            $this
              .parents(".woocommerce_variation")
              .eq(0)
              .addClass("variation-needs-update");
            $("#variable_product_options").find("input").eq(0).change();
          }
        );
      });
    }
    $("#woocommerce-product-data").on(
      "woocommerce_variations_loaded",
      function () {
        variationGallery();
      }
    );
    $("#variable_product_options").on(
      "woocommerce_variations_added",
      function () {
        variationGallery();
      }
    );

    function field_images() {
      $(".misha_upload_images_button").on("click", function (e) {
        /* on button click*/ e.preventDefault();
        var button = $(this),
          hiddenfield = button.prev(),
          hiddenfieldvalue =
            hiddenfield.val() /* the array of added image IDs */,
          custom_uploader = wp
            .media({
              title: "Insert images" /* popup title */,
              library: { type: "image" },
              button: { text: "Use these images" } /* "Insert" button text */,
              multiple: false,
            })
            .on("select", function () {
              var attachments = custom_uploader
                  .state()
                  .get("selection")
                  .map(function (a) {
                    a.toJSON();
                    return a;
                  }),
                thesamepicture = false;

              /* if you don't want the same images to be added multiple time */
              if (attachments[0].id != hiddenfieldvalue) {
                /* add HTML element with an image */
                button
                  .parents(".term-image-wrap")
                  .find("ul.misha_images_mtb")
                  .html(
                    '<li data-id="' +
                      attachments[0].id +
                      '"><span><img src="' +
                      attachments[0].attributes.url +
                      '"></span><a href="#" class="misha_images_remove">�</a></li>'
                  );
                /* add an image ID to the array of all images */
                hiddenfieldvalue = attachments[0].id;
              } else {
                thesamepicture = true;
              }

              /* add the IDs to the hidden field value */
              hiddenfield.val(hiddenfieldvalue);
              /* you can print a message for users if you want to let you know about the same images */
              if (thesamepicture == true)
                alert("The same images are not allowed.");
            })
            .open();
      });
      /*
       * Remove certain images
       */
      $("body").on("click", ".misha_images_remove", function () {
        var images = $(this).parent().parent(),
          hiddenfield = images.parent().next();

        $(this).parent().remove();

        /* add the IDs to the hidden field value */
        hiddenfield.val("");

        return false;
      });
    }
    field_images();
  });
})(jQuery);
