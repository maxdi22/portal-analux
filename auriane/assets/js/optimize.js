(function ($) {
  "use strict";
  $(document).ready(function () {
    $(window).on("load", function () {
      $(".loading_ok").hide();
      setTimeout(function () {
        $(".jws_footer").click();
      }, 100);
    });
  });
})(jQuery);
